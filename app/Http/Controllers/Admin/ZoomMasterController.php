<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZoomUnit;
use App\Models\ZoomLink;
use Illuminate\Http\Request;

class ZoomMasterController extends Controller
{
    public function index()
    {
        $units = ZoomUnit::orderBy('sort_order')->orderBy('id')->paginate(10, ['*'], 'page_unit');
        $links = ZoomLink::orderBy('sort_order')->orderBy('id')->paginate(10, ['*'], 'page_link');
        return view('admin.zoom.index', compact('units', 'links'))->with('activeMenu', 'master-zoom');
    }

    // Unit CRUD
    public function storeUnit(Request $request)
    {
        $request->validate(['unit_name' => 'required|string|max:120|unique:zoom_units,name']);
        $max = ZoomUnit::max('sort_order') ?? 0;
        ZoomUnit::create(['name' => $request->unit_name, 'sort_order' => $max + 1]);
        return back()->with('success', "Unit '{$request->unit_name}' berhasil ditambahkan.");
    }

    public function updateUnit(Request $request, $id)
    {
        $request->validate(['unit_name' => 'required|string|max:120|unique:zoom_units,name,' . $id]);
        ZoomUnit::findOrFail($id)->update(['name' => $request->unit_name]);
        return back()->with('success', 'Unit berhasil diupdate.');
    }

    public function destroyUnit($id)
    {
        ZoomUnit::findOrFail($id)->delete();
        return back()->with('success', 'Unit berhasil dihapus.');
    }

    public function toggleUnit($id)
    {
        $unit = ZoomUnit::findOrFail($id);
        $unit->is_active = !$unit->is_active;
        $unit->save();
        return back()->with('success', 'Status unit berhasil diubah.');
    }

    // Link CRUD
    public function storeLink(Request $request)
    {
        $request->validate(['link_email' => 'required|email|max:180|unique:zoom_links,email']);
        $max = ZoomLink::max('sort_order') ?? 0;
        ZoomLink::create(['email' => $request->link_email, 'sort_order' => $max + 1]);
        return back()->with('success', "Link Zoom '{$request->link_email}' berhasil ditambahkan.");
    }

    public function updateLink(Request $request, $id)
    {
        $request->validate(['link_email' => 'required|email|max:180|unique:zoom_links,email,' . $id]);
        ZoomLink::findOrFail($id)->update(['email' => $request->link_email]);
        return back()->with('success', 'Link Zoom berhasil diupdate.');
    }

    public function destroyLink($id)
    {
        ZoomLink::findOrFail($id)->delete();
        return back()->with('success', 'Link Zoom berhasil dihapus.');
    }

    public function toggleLink($id)
    {
        $link = ZoomLink::findOrFail($id);
        $link->is_active = !$link->is_active;
        $link->save();
        return back()->with('success', 'Status link berhasil diubah.');
    }
}
