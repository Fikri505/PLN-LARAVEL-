<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PicItSupport;
use Illuminate\Http\Request;

class ItSupportMasterController extends Controller
{
    public function index()
    {
        $items = PicItSupport::orderBy('sort_order')->orderBy('id')->paginate(10);
        return view('admin.it-support.index', compact('items'))->with('activeMenu', 'master-it-support');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:pic_it_support']);
        $max = PicItSupport::max('sort_order') ?? 0;
        PicItSupport::create(['name' => $request->name, 'sort_order' => $max + 1]);
        return back()->with('success', "PIC '{$request->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100|unique:pic_it_support,name,' . $id]);
        PicItSupport::findOrFail($id)->update(['name' => $request->name]);
        return back()->with('success', 'PIC berhasil diupdate.');
    }

    public function destroy($id)
    {
        PicItSupport::findOrFail($id)->delete();
        return back()->with('success', 'PIC berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $item = PicItSupport::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();
        return back()->with('success', 'Status PIC berhasil diubah.');
    }
}
