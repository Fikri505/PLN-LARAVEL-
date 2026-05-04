<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $items = MeetingRoom::orderBy('sort_order')->orderBy('id')->paginate(10);
        return view('admin.ruangan.index', compact('items'))->with('activeMenu', 'master-ruangan');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:150|unique:meeting_rooms']);
        $max = MeetingRoom::max('sort_order') ?? 0;
        MeetingRoom::create(['name' => $request->name, 'sort_order' => $max + 1]);
        return back()->with('success', "Ruangan '{$request->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:150|unique:meeting_rooms,name,' . $id]);
        MeetingRoom::findOrFail($id)->update(['name' => $request->name]);
        return back()->with('success', 'Ruangan berhasil diupdate.');
    }

    public function destroy($id)
    {
        MeetingRoom::findOrFail($id)->delete();
        return back()->with('success', 'Ruangan berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $item = MeetingRoom::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();
        return back()->with('success', 'Status ruangan berhasil diubah.');
    }
}
