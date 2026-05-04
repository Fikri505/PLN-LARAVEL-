<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ZoomBooking;
use App\Models\ZoomLink;
use App\Models\MeetingRoom;
use App\Models\PicItSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-release expired zoom bookings
        ZoomBooking::autoRelease();

        // Schedule stats
        $rows = Schedule::orderBy('created_at', 'desc')->get();
        $thisMonth = now()->format('Y-m');
        $monthCount = $rows->filter(fn($r) => str_starts_with($r->created_at, $thisMonth))->count();
        $solvedCount = $rows->where('tindak_lanjut', 'SOLVED')->count();

        // Zoom stats
        $zoomTotal = ZoomBooking::count();

        $zoomMonth = ZoomBooking::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [now()->format('Y-m')])->count();

        $zoomActiveTotal = ZoomLink::where('is_active', true)->count();
        $today = now()->toDateString();
        $zoomBookedToday = ZoomBooking::selectRaw('COUNT(DISTINCT zoom_link) as cnt')
            ->whereRaw("(
                (start_datetime IS NOT NULL AND end_datetime IS NOT NULL AND DATE(start_datetime) <= ? AND DATE(end_datetime) >= ?)
                OR (start_datetime IS NULL AND booking_date = ?)
            )", [$today, $today, $today])
            ->value('cnt');
        $zoomAvailableToday = max(0, $zoomActiveTotal - $zoomBookedToday);

        // Load form options for schedule entry
        $picItOptions = PicItSupport::active()->pluck('name');
        $meetingRooms = MeetingRoom::active()->pluck('name');

        return view('dashboard.index', compact(
            'rows', 'monthCount', 'solvedCount',
            'zoomTotal', 'zoomMonth', 'zoomActiveTotal', 'zoomAvailableToday',
            'picItOptions', 'meetingRooms'
        ));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pic_acara' => 'required|string|max:150',
            'nama_acara' => 'required|string|max:255',
            'meeting_room' => 'required|string',
            'pelaksanaan' => 'required|in:ONLINE,OFFLINE,HYBRID',
            'standby_status' => 'required|in:STANDBY,ON CALL',
            'tindak_lanjut' => 'required|in:SOLVED,UNSOLVED',
        ]);

        // Generate unique transaction ID
        do {
            $rand = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $transactionId = 'TRX-' . date('Ymd') . '-' . $rand;
        } while (Schedule::where('transaction_id', $transactionId)->exists());

        $picItSupport = $request->input('pic_it_support', []);
        $validPics = PicItSupport::active()->pluck('name')->toArray();
        $picItSupport = array_values(array_intersect($picItSupport, $validPics));

        Schedule::create([
            'transaction_id' => $transactionId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pic_acara' => $request->pic_acara,
            'nama_acara' => $request->nama_acara,
            'pic_it_support' => json_encode($picItSupport, JSON_UNESCAPED_UNICODE),
            'meeting_room' => $request->meeting_room,
            'pelaksanaan' => $request->pelaksanaan,
            'standby_status' => $request->standby_status,
            'kebutuhan_detail' => $request->kebutuhan_detail,
            'tindak_lanjut' => $request->tindak_lanjut,
        ]);

        return redirect()->route('dashboard')->with('success', 'Data jadwal berhasil disimpan.');
    }
}
