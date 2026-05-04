<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerangkatAplikasiMasterController extends Controller
{
    private array $tables = [
        'jenis_perangkat' => 'master_pa_jenis_perangkat',
        'brand' => 'master_pa_brand',
        'lokasi' => 'master_pa_lokasi',
        'bidang' => 'master_pa_bidang',
        'msb' => 'master_pa_msb',
    ];

    public function index()
    {
        $data = [];
        foreach ($this->tables as $key => $table) {
            $data[$key] = DB::table($table)->orderBy('sort_order')->orderBy('id')->paginate(10, ['*'], "page_{$key}");
        }
        return view('admin.perangkat-aplikasi.index', $data)->with('activeMenu', 'master-perangkat-aplikasi');
    }

    public function store(Request $request)
    {
        $request->validate(['type' => 'required|string', 'name' => 'required|string|max:255']);
        $table = $this->tables[$request->type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        $max = DB::table($table)->max('sort_order') ?? 0;
        DB::table($table)->insert(['name' => $request->name, 'sort_order' => $max + 1, 'created_at' => now()]);
        return back()->with('success', "Data berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $request->validate(['type' => 'required|string', 'name' => 'required|string|max:255']);
        $table = $this->tables[$request->type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        DB::table($table)->where('id', $id)->update(['name' => $request->name]);
        return back()->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $type = request('type');
        $table = $this->tables[$type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        DB::table($table)->where('id', $id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function toggle($type, $id)
    {
        $table = $this->tables[$type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        $current = DB::table($table)->where('id', $id)->value('is_active');
        DB::table($table)->where('id', $id)->update(['is_active' => !$current]);
        return back()->with('success', 'Status berhasil diubah.');
    }
}
