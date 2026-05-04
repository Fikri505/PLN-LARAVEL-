<?php

namespace App\Http\Controllers;

use App\Models\ZoomBooking;
use App\Models\ZoomLink;
use App\Models\ZoomUnit;
use Illuminate\Http\Request;

class BookingZoomController extends Controller
{
    public function index(Request $request)
    {
        ZoomBooking::autoRelease();

        $query = ZoomBooking::with('creator');

        if ($request->filled('filter_unit')) $query->where('unit', $request->filter_unit);
        if ($request->filled('filter_zoom')) $query->where('zoom_link', $request->filter_zoom);
        if ($request->filled('filter_from')) $query->whereRaw("COALESCE(start_datetime, CONCAT(booking_date,' 00:00:00')) >= ?", [$request->filter_from . ' 00:00:00']);
        if ($request->filled('filter_to')) $query->whereRaw("COALESCE(start_datetime, CONCAT(booking_date,' 23:59:59')) <= ?", [$request->filter_to . ' 23:59:59']);

        $rows = $query->orderBy('booking_date', 'desc')->orderBy('id', 'desc')->get();

        $zoomLinksAll = ZoomBooking::distinct()->whereNotNull('zoom_link')->pluck('zoom_link')->sort()->values();
        $zoomLinksActive = ZoomLink::active()->pluck('email');
        $unitsAll = ZoomBooking::distinct()->whereNotNull('unit')->where('unit', '!=', '')->pluck('unit')->sort()->values();

        $isFiltered = $request->anyFilled(['filter_unit', 'filter_zoom', 'filter_from', 'filter_to']);

        return view('booking-zoom.index', compact('rows', 'zoomLinksAll', 'zoomLinksActive', 'unitsAll', 'isFiltered'))
            ->with('activeMenu', 'booking-zoom');
    }

    public function create(Request $request)
    {
        $zoomOptions = ZoomLink::active()->pluck('email');
        $unitOptions = ZoomUnit::active()->pluck('name');

        return view('booking-zoom.create', compact('zoomOptions', 'unitOptions'))
            ->with('activeMenu', 'booking-zoom');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'zoom_link' => 'required|string',
            'unit' => 'required|string',
        ]);

        $start = \Carbon\Carbon::parse($request->start_datetime);
        $end = \Carbon\Carbon::parse($request->end_datetime);

        ZoomBooking::create([
            'booking_date' => $start->toDateString(),
            'booking_time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
            'start_datetime' => $start,
            'end_datetime' => $end,
            'zoom_link' => $request->zoom_link,
            'unit' => $request->unit,
            'keterangan' => $request->keterangan,
            'kondisi' => 'DIPAKAI',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('booking-zoom.index')->with('success', 'Booking zoom berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        ZoomBooking::findOrFail($id)->delete();
        return redirect()->route('booking-zoom.index')->with('success', 'Booking berhasil dihapus.');
    }

    public function cekKetersediaan(Request $request)
    {
        ZoomBooking::autoRelease();
        $zoomLinksActive = ZoomLink::active()->pluck('email');

        $checked = false;
        $results = collect();
        $filterFrom = $request->input('from', now()->toDateString());
        $filterTo = $request->input('to', now()->toDateString());

        if ($request->filled('from') && $request->filled('to')) {
            $checked = true;
            $from = \Carbon\Carbon::parse($filterFrom)->startOfDay();
            $to = \Carbon\Carbon::parse($filterTo)->endOfDay();

            foreach ($zoomLinksActive as $email) {
                // Find bookings that overlap with the selected date range
                $bookings = ZoomBooking::where('zoom_link', $email)
                    ->where(function ($q) use ($from, $to) {
                        $q->where(function ($q2) use ($from, $to) {
                            // Has start/end datetime — check overlap
                            $q2->whereNotNull('start_datetime')
                               ->whereNotNull('end_datetime')
                               ->where('start_datetime', '<', $to)
                               ->where('end_datetime', '>', $from);
                        })->orWhere(function ($q2) use ($from, $to) {
                            // Legacy booking_date only
                            $q2->whereNull('start_datetime')
                               ->whereBetween('booking_date', [$from->toDateString(), $to->toDateString()]);
                        });
                    })
                    ->with('creator')
                    ->orderBy('start_datetime', 'asc')
                    ->get();

                $results->push([
                    'email' => $email,
                    'status' => $bookings->isEmpty() ? 'KOSONG' : 'DIPAKAI',
                    'bookings' => $bookings,
                ]);
            }
        }

        return view('booking-zoom.ketersediaan', compact(
            'zoomLinksActive', 'checked', 'results', 'filterFrom', 'filterTo'
        ))->with('activeMenu', 'booking-zoom');
    }

    public function apiBookings(Request $request)
    {
        $bookings = ZoomBooking::with('creator')->orderBy('booking_date', 'desc')->get();
        return response()->json($bookings);
    }
}
