<?php

namespace App\Http\Controllers;

use App\Models\PerangkatAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerangkatAplikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = PerangkatAplikasi::with('creator');
        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->where(fn($q) => $q->where('jenis_perangkat', 'like', $like)->orWhere('url', 'like', $like)->orWhere('ip', 'like', $like)->orWhere('brand', 'like', $like)->orWhere('lokasi', 'like', $like));
        }
        $items = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('perangkat-aplikasi.index', compact('items'))->with('activeMenu', 'perangkat-aplikasi');
    }

    public function create()
    {
        $options = $this->getMasterOptions();
        return view('perangkat-aplikasi.create', $options)->with('activeMenu', 'perangkat-aplikasi');
    }

    public function store(Request $request)
    {
        $request->validate(['jenis_perangkat' => 'required|string|max:255']);
        $data = $request->only([
            'jenis_perangkat', 'url', 'ip', 'brand', 'type', 'server', 'os',
            'lokasi', 'bidang', 'msb_sub_bidang', 'firmware_patch', 'database_patch',
            'network_device_patch', 'application_patch', 'os_patch', 'library_dependency_patch', 'pemilik_aset',
        ]);
        $data['created_by'] = auth()->id();
        PerangkatAplikasi::create($data);
        return redirect()->route('perangkat-aplikasi.index')->with('success', 'Perangkat aplikasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = PerangkatAplikasi::findOrFail($id);
        $options = $this->getMasterOptions();
        return view('perangkat-aplikasi.edit', array_merge($options, compact('item')))->with('activeMenu', 'perangkat-aplikasi');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['jenis_perangkat' => 'required|string|max:255']);
        $item = PerangkatAplikasi::findOrFail($id);
        $item->update($request->only([
            'jenis_perangkat', 'url', 'ip', 'brand', 'type', 'server', 'os',
            'lokasi', 'bidang', 'msb_sub_bidang', 'firmware_patch', 'database_patch',
            'network_device_patch', 'application_patch', 'os_patch', 'library_dependency_patch', 'pemilik_aset',
        ]));
        return redirect()->route('perangkat-aplikasi.index')->with('success', 'Perangkat aplikasi berhasil diupdate.');
    }

    public function destroy($id)
    {
        PerangkatAplikasi::findOrFail($id)->delete();
        return redirect()->route('perangkat-aplikasi.index')->with('success', 'Perangkat aplikasi berhasil dihapus.');
    }

    private function getMasterOptions(): array
    {
        return [
            'jenisOptions' => DB::table('master_pa_jenis_perangkat')->where('is_active', true)->orderBy('sort_order')->pluck('name'),
            'brandOptions' => DB::table('master_pa_brand')->where('is_active', true)->orderBy('sort_order')->pluck('name'),
            'lokasiOptions' => DB::table('master_pa_lokasi')->where('is_active', true)->orderBy('sort_order')->pluck('name'),
            'bidangOptions' => DB::table('master_pa_bidang')->where('is_active', true)->orderBy('sort_order')->pluck('name'),
            'msbOptions' => DB::table('master_pa_msb')->where('is_active', true)->orderBy('sort_order')->pluck('name'),
        ];
    }
}
