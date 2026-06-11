<?php

namespace App\Http\Controllers;

use App\Models\StockPerangkat;
use Illuminate\Http\Request;

class StockPerangkatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('perPage', 10);
        if (!in_array($perPage, [5, 10, 15, 20, 30])) {
            $perPage = 10;
        }

        $query = StockPerangkat::with('creator');

        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->where(fn($q) =>
                $q->where('nama_barang', 'like', $like)
                  ->orWhere('type_barang', 'like', $like)
                  ->orWhere('kondisi', 'like', $like)
                  ->orWhere('keterangan', 'like', $like)
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('created_at', 'desc')
                       ->paginate($perPage)
                       ->withQueryString();

        return view('stock-perangkat.index', compact('items', 'perPage'))
               ->with('activeMenu', 'stock-perangkat');
    }

    public function create()
    {
        return view('stock-perangkat.create')->with('activeMenu', 'stock-perangkat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah'      => 'required|integer|min:1',
            'status'      => 'required|in:aktif,non-aktif',
            'kondisi'     => 'required|in:baru,normal',
        ]);

        $data = $request->only('nama_barang', 'type_barang', 'jumlah', 'status', 'kondisi', 'keterangan');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('stock_images', 'public');
        }

        StockPerangkat::create($data);

        return redirect()->route('stock-perangkat.index')
                         ->with('success', 'Stock perangkat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = StockPerangkat::findOrFail($id);
        return view('stock-perangkat.edit', compact('item'))->with('activeMenu', 'stock-perangkat');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'status'      => 'required|in:aktif,non-aktif',
            'kondisi'     => 'required|in:baru,normal',
        ]);

        $item = StockPerangkat::findOrFail($id);
        $data = $request->only('nama_barang', 'type_barang', 'status', 'kondisi', 'keterangan');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('stock_images', 'public');
        }

        $item->update($data);

        return redirect()->route('stock-perangkat.index')
                         ->with('success', 'Stock perangkat berhasil diupdate.');
    }

    public function destroy($id)
    {
        StockPerangkat::findOrFail($id)->delete();
        return redirect()->route('stock-perangkat.index')
                         ->with('success', 'Stock perangkat berhasil dihapus.');
    }
}