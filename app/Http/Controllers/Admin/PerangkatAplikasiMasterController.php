<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerangkatAplikasiMasterController extends Controller
{
    private array $tables = [
        'jenis_perangkat' => 'master_pa_jenis_perangkat',
        'brand'           => 'master_pa_brand',
        'lokasi'          => 'master_pa_lokasi',
        'bidang'          => 'master_pa_bidang',
        'msb'             => 'master_pa_msb',
    ];

    private array $tabLabels = [
        'jenis_perangkat' => 'Jenis Perangkat',
        'brand'           => 'Brand',
        'lokasi'          => 'Lokasi',
        'bidang'          => 'Bidang',
        'msb'             => 'MSB/Sub Bidang',
    ];

    public function index(Request $request)
    {
        $section = $request->get('section', 'jenis_perangkat');
        if (!array_key_exists($section, $this->tables)) {
            $section = 'jenis_perangkat';
        }

        $perPage = in_array((int) $request->get('per_page', 10), [10, 25, 50, 100])
            ? (int) $request->get('per_page', 10)
            : 10;

        $table = $this->tables[$section];
        $items = DB::table($table)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($perPage)
            ->appends([
                'section'  => $section,
                'per_page' => $perPage,
            ]);

        return view('admin.perangkat-aplikasi.index', [
            'items'     => $items,
            'section'   => $section,
            'tabLabels' => $this->tabLabels,
        ])->with('activeMenu', 'master-perangkat-aplikasi');
    }

    public function create(Request $request)
    {
        $section = $request->get('section', 'jenis_perangkat');
        if (!array_key_exists($section, $this->tables)) {
            $section = 'jenis_perangkat';
        }

        return view('admin.perangkat-aplikasi.create', [
            'section'   => $section,
            'tabLabels' => $this->tabLabels,
        ])->with('activeMenu', 'master-perangkat-aplikasi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        $table = $this->tables[$request->type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        $max = DB::table($table)->max('sort_order') ?? 0;
        DB::table($table)->insert([
            'name'       => $request->name,
            'sort_order' => $max + 1,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.master-perangkat-aplikasi.index', ['section' => $request->type])
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $section = $request->get('type', 'jenis_perangkat');
        if (!array_key_exists($section, $this->tables)) {
            $section = 'jenis_perangkat';
        }

        $table = $this->tables[$section];
        $item  = DB::table($table)->where('id', $id)->first();

        if (!$item) {
            return redirect()->route('admin.master-perangkat-aplikasi.index', ['section' => $section])
                ->with('error', 'Data tidak ditemukan.');
        }

        return view('admin.perangkat-aplikasi.edit', [
            'item'      => $item,
            'section'   => $section,
            'tabLabels' => $this->tabLabels,
        ])->with('activeMenu', 'master-perangkat-aplikasi');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        $table = $this->tables[$request->type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        DB::table($table)->where('id', $id)->update([
            'name'       => $request->name,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.master-perangkat-aplikasi.index', ['section' => $request->type])
            ->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id)
    {
        $type  = request('type');
        $table = $this->tables[$type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        DB::table($table)->where('id', $id)->delete();

        return redirect()->route('admin.master-perangkat-aplikasi.index', ['section' => $type])
            ->with('success', 'Data berhtype  = reque');
    }

    public function toggle($type, $id)
    {
        $table = $this->tables[$type] ?? null;
        if (!$table) return back()->with('error', 'Tipe tidak valid.');

        $current = DB::table($table)->where('id', $id)->value('is_active');
        DB::table($table)->where('id', $id)->update(['is_active' => !$current]);

        return redirect()->route('admin.master-perangkat-aplikasi.index', ['section' => $type])
            ->with('success', 'Status berhasil diubah.');
    }
}