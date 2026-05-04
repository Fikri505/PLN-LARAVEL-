<?php

namespace App\Http\Controllers;

use App\Models\DataServer;
use Illuminate\Http\Request;

class DataServerController extends Controller
{
    public function index(Request $request)
    {
        $query = DataServer::query();

        if ($request->filled('q')) {
            $like = '%' . $request->q . '%';
            $query->where(function ($q) use ($like) {
                $q->where('ind', 'like', $like)
                  ->orWhere('fungsi_server', 'like', $like)
                  ->orWhere('ip', 'like', $like)
                  ->orWhere('merk', 'like', $like)
                  ->orWhere('type', 'like', $like)
                  ->orWhere('system_operasi', 'like', $like)
                  ->orWhere('server_fisik', 'like', $like)
                  ->orWhere('status_server', 'like', $like);
            });
        }

        $servers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('data-server.index', compact('servers'))->with('activeMenu', 'data-server');
    }

    public function create()
    {
        return view('data-server.create')->with('activeMenu', 'data-server');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ind' => 'required|string|max:100',
            'fungsi_server' => 'required|string|max:255',
            'ip' => 'required|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,jpg|max:2048',
        ]);

        $data = $request->except('gambar', '_token', 'action');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('server_images', 'public');
        }

        DataServer::create($data);

        return redirect()->route('data-server.index')->with('success', 'Data server berhasil ditambahkan.');
    }

    public function show($id)
    {
        $server = DataServer::with('maintenances.creator')->findOrFail($id);
        return view('data-server.show', compact('server'))->with('activeMenu', 'data-server');
    }

    public function edit($id)
    {
        $server = DataServer::findOrFail($id);
        return view('data-server.edit', compact('server'))->with('activeMenu', 'data-server');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ind' => 'required|string|max:100',
            'fungsi_server' => 'required|string|max:255',
            'ip' => 'required|string|max:50',
            'gambar' => 'nullable|image|mimes:jpeg,jpg|max:2048',
        ]);

        $server = DataServer::findOrFail($id);
        $data = $request->except('gambar', '_token', '_method', 'action');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('server_images', 'public');
        }

        $server->update($data);

        return redirect()->route('data-server.index')->with('success', 'Data server berhasil diupdate.');
    }

    public function destroy($id)
    {
        DataServer::findOrFail($id)->delete();
        return redirect()->route('data-server.index')->with('success', 'Data server berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $server = DataServer::findOrFail($id);
        $server->status_server = $server->status_server === 'HIDUP' ? 'MATI' : 'HIDUP';
        $server->save();

        return back()->with('success', 'Status server berhasil diubah.');
    }
}
