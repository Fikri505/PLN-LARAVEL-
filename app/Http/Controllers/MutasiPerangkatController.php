<?php

namespace App\Http\Controllers;

use App\Models\MutasiPerangkat;
use App\Models\StockPerangkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiPerangkatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', 10);
        if (!in_array($perPage, [5, 10, 15, 20, 30])) {
            $perPage = 10;
        }

        $query = MutasiPerangkat::with(['perangkat', 'creator']);

        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->where(function ($q) use ($like) {
                $q->whereHas('perangkat', fn($q) =>
                    $q->where('nama_barang', 'like', $like)
                      ->orWhere('type_barang', 'like', $like)
                )->orWhere('keterangan', 'like', $like)
                 ->orWhere('tipe', 'like', $like);
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('stock_id')) {
            $query->where('stock_perangkat_id', $request->stock_id);
        }

        $mutasi = $query->orderBy('created_at', 'desc')
                        ->paginate($perPage)
                        ->withQueryString();

        $filterStock = null;
        if ($request->filled('stock_id')) {
            $filterStock = StockPerangkat::find($request->stock_id);
        }

        return view('mutasi-perangkat.index', compact('mutasi', 'filterStock', 'perPage'))
               ->with('activeMenu', 'stock-perangkat');
    }

    public function create($stockId)
    {
        $perangkat = StockPerangkat::findOrFail($stockId);
        return view('mutasi-perangkat.create', compact('perangkat'))
               ->with('activeMenu', 'stock-perangkat');
    }

    public function store(Request $request, $stockId)
    {
        $request->validate([
            'tipe'       => 'required|in:masuk,keluar',
            'jumlah'     => 'required|integer|min:1',
            'kondisi'    => 'required|in:baru,normal',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $stockId) {
                $stock = StockPerangkat::lockForUpdate()->findOrFail($stockId);

                if ($request->tipe === 'keluar' && $stock->jumlah < $request->jumlah) {
                    throw new \Exception('Stok tidak mencukupi. Stok saat ini: ' . $stock->jumlah);
                }

                MutasiPerangkat::create([
                    'stock_perangkat_id' => $stockId,
                    'tipe'               => $request->tipe,
                    'jumlah'             => $request->jumlah,
                    'kondisi'            => $request->kondisi,
                    'keterangan'         => $request->keterangan,
                    'created_by'         => auth()->id(),
                ]);

                $stock->jumlah = $request->tipe === 'masuk'
                    ? $stock->jumlah + $request->jumlah
                    : $stock->jumlah - $request->jumlah;

                $stock->kondisi = $request->kondisi;
                $stock->save();
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('mutasi-perangkat.index')
                         ->with('success', 'Mutasi berhasil dicatat dan stok diperbarui.');
    }

    public function destroy($id)
    {
        MutasiPerangkat::findOrFail($id)->delete();
        return redirect()->route('mutasi-perangkat.index')
                         ->with('success', 'Mutasi berhasil dihapus.');
    }
}