<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\MeetingRoom;
use App\Models\PicItSupport;
use Illuminate\Http\Request;

class DataJadwalController extends Controller
{
    public function index()
    {
        $rows = Schedule::orderBy('created_at', 'desc')->paginate(10);
        return view('data-jadwal.index', compact('rows'))->with('activeMenu', 'data-jadwal');
    }

    public function create()
    {
        $picItOptions = PicItSupport::active()->pluck('name');
        $meetingRooms = MeetingRoom::active()->pluck('name');
        return view('data-jadwal.create', compact('picItOptions', 'meetingRooms'))->with('activeMenu', 'data-jadwal');
    }

    public function store(Request $request)
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

        do {
            $rand = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $tid = 'TRX-' . date('Ymd') . '-' . $rand;
        } while (Schedule::where('transaction_id', $tid)->exists());

        $picIt = array_values(array_intersect(
            $request->input('pic_it_support', []),
            PicItSupport::active()->pluck('name')->toArray()
        ));

        Schedule::create([
            'transaction_id' => $tid,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pic_acara' => $request->pic_acara,
            'nama_acara' => $request->nama_acara,
            'pic_it_support' => json_encode($picIt, JSON_UNESCAPED_UNICODE),
            'meeting_room' => $request->meeting_room,
            'pelaksanaan' => $request->pelaksanaan,
            'standby_status' => $request->standby_status,
            'kebutuhan_detail' => $request->kebutuhan_detail,
            'tindak_lanjut' => $request->tindak_lanjut,
        ]);

        return redirect()->route('data-jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function export()
    {
        return app(ExportController::class)->dataJadwal();
    }
}
