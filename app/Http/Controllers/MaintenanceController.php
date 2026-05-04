<?php

namespace App\Http\Controllers;

use App\Models\DataServer;
use App\Models\ServerMaintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function create($serverId)
    {
        $server = DataServer::findOrFail($serverId);
        if ($server->status_server === 'MATI') {
            return redirect()->route('data-server.show', $serverId)->with('error', 'Server dalam keadaan MATI, tidak bisa menambah maintenance.');
        }
        return view('data-server.maintenance-create', compact('server'))->with('activeMenu', 'data-server');
    }

    public function store(Request $request, $serverId)
    {
        $request->validate([
            'waktu_pemeliharaan' => 'required|string',
            'temuan' => 'required|string',
            'dicek_oleh' => 'required|string',
            'kondisi' => 'required|in:HIDUP,MATI',
            'status' => 'required|in:PROBLEM,AMAN',
            'gambar' => 'nullable|image|mimes:jpeg,jpg|max:2048',
        ]);

        $data = $request->only('waktu_pemeliharaan', 'temuan', 'dicek_oleh', 'kondisi', 'status');
        $data['server_id'] = $serverId;
        $data['created_by'] = auth()->id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('maintenance_images', 'public');
        }

        ServerMaintenance::create($data);

        return redirect()->route('data-server.show', $serverId)->with('success', 'History pemeliharaan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $maintenance = ServerMaintenance::with('server')->findOrFail($id);
        return view('data-server.maintenance-edit', compact('maintenance'))->with('activeMenu', 'data-server');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'waktu_pemeliharaan' => 'required|string',
            'temuan' => 'required|string',
            'dicek_oleh' => 'required|string',
            'kondisi' => 'required|in:HIDUP,MATI',
            'status' => 'required|in:PROBLEM,AMAN',
        ]);

        $maintenance = ServerMaintenance::findOrFail($id);
        $data = $request->only('waktu_pemeliharaan', 'temuan', 'dicek_oleh', 'kondisi', 'status');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('maintenance_images', 'public');
        }

        $maintenance->update($data);

        return redirect()->route('data-server.show', $maintenance->server_id)->with('success', 'History pemeliharaan berhasil diupdate.');
    }
}
