<?php

namespace App\Http\Controllers;

use App\Models\MutasiPerangkat;
use App\Models\StockPerangkat;
use Illuminate\Http\Request;

class MutasiPerangkatController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiPerangkat::with(['perangkat', 'creator']);

        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->whereHas('perangkat', fn($q) =>
                $q->where('nama_barang', 'like', $like)
                  ->orWhere('type_barang', 'like', $like)
            )->orWhere('keterangan', 'like', $like);
        }

        $mutasi = $query->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        return view('mutasi-perangkat.index', compact('mutasi'))
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
            'jumlah'     => 'required|integer|min:1',
            'kondisi'    => 'required|in:baru,normal',
            'keterangan' => 'nullable|string',
        ]);

        MutasiPerangkat::create([
            'stock_perangkat_id' => $stockId,
            'jumlah'             => $request->jumlah,
            'kondisi'            => $request->kondisi,
            'keterangan'         => $request->keterangan,
            'created_by'         => auth()->id(),
        ]);

        return redirect()->route('mutasi-perangkat.index')
                         ->with('success', 'Mutasi berhasil dicatat.');
    }

    public function destroy($id)
    {
        MutasiPerangkat::findOrFail($id)->delete();

        return redirect()->route('mutasi-perangkat.index')
                         ->with('success', 'Mutasi berhasil dihapus.');
    }
}