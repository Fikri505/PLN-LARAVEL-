<?php

namespace App\Http\Controllers;

use App\Models\ItSupportJateng;
use Illuminate\Http\Request;

class ItSupportJatengController extends Controller
{
    public function index(Request $request)
    {
        $query = ItSupportJateng::query();
        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->where(fn($q) => $q->where('nama', 'like', $like)->orWhere('email', 'like', $like)->orWhere('penempatan', 'like', $like));
        }
        $items = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('it-support-jateng.index', compact('items'))->with('activeMenu', 'it-support-jateng');
    }

    public function create() { return view('it-support-jateng.create')->with('activeMenu', 'it-support-jateng'); }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        ItSupportJateng::create($request->only('nama', 'email', 'no_hp', 'penempatan', 'ops_sti'));
        return redirect()->route('it-support-jateng.index')->with('success', 'Data IT Support berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = ItSupportJateng::findOrFail($id);
        return view('it-support-jateng.edit', compact('item'))->with('activeMenu', 'it-support-jateng');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        ItSupportJateng::findOrFail($id)->update($request->only('nama', 'email', 'no_hp', 'penempatan', 'ops_sti'));
        return redirect()->route('it-support-jateng.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        ItSupportJateng::findOrFail($id)->delete();
        return redirect()->route('it-support-jateng.index')->with('success', 'Data berhasil dihapus.');
    }
}
