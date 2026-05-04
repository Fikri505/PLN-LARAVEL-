@extends('layouts.app')
@section('title', 'Edit Server: ' . $server->ind)
@section('content')
<div class="max-w-4xl">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">✏️ Edit — {{ $server->ind }}</h2>
            <a href="{{ route('data-server.show', $server->id) }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('data-server.update', $server->id) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">IND *</label><input type="text" name="ind" value="{{ old('ind', $server->ind) }}" class="form-input" required></div>
                    <div><label class="form-label">IP *</label><input type="text" name="ip" value="{{ old('ip', $server->ip) }}" class="form-input" required></div>
                    <div><label class="form-label">Status</label>
                        <select name="status_server" class="form-select">
                            <option value="HIDUP" {{ old('status_server', $server->status_server) === 'HIDUP' ? 'selected' : '' }}>HIDUP</option>
                            <option value="MATI" {{ old('status_server', $server->status_server) === 'MATI' ? 'selected' : '' }}>MATI</option>
                        </select>
                    </div>
                </div>
                <div><label class="form-label">Fungsi Server *</label><input type="text" name="fungsi_server" value="{{ old('fungsi_server', $server->fungsi_server) }}" class="form-input" required></div>
                <div><label class="form-label">Detail</label><textarea name="detail" rows="2" class="form-input">{{ old('detail', $server->detail) }}</textarea></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">Merk</label><input type="text" name="merk" value="{{ old('merk', $server->merk) }}" class="form-input"></div>
                    <div><label class="form-label">Type</label><input type="text" name="type" value="{{ old('type', $server->type) }}" class="form-input"></div>
                    <div><label class="form-label">OS</label><input type="text" name="system_operasi" value="{{ old('system_operasi', $server->system_operasi) }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div><label class="form-label">Proc Merk</label><input type="text" name="processor_merk" value="{{ old('processor_merk', $server->processor_merk) }}" class="form-input"></div>
                    <div><label class="form-label">Proc Type</label><input type="text" name="processor_type" value="{{ old('processor_type', $server->processor_type) }}" class="form-input"></div>
                    <div><label class="form-label">GHz</label><input type="text" name="processor_kecepatan" value="{{ old('processor_kecepatan', $server->processor_kecepatan) }}" class="form-input"></div>
                    <div><label class="form-label">Core</label><input type="text" name="processor_core" value="{{ old('processor_core', $server->processor_core) }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">RAM Jenis</label><input type="text" name="ram_jenis" value="{{ old('ram_jenis', $server->ram_jenis) }}" class="form-input"></div>
                    <div><label class="form-label">RAM Kapasitas</label><input type="text" name="ram_kapasitas" value="{{ old('ram_kapasitas', $server->ram_kapasitas) }}" class="form-input"></div>
                    <div><label class="form-label">Keping</label><input type="text" name="ram_jumlah_keping" value="{{ old('ram_jumlah_keping', $server->ram_jumlah_keping) }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">Storage Jenis</label><input type="text" name="storage_jenis" value="{{ old('storage_jenis', $server->storage_jenis) }}" class="form-input"></div>
                    <div><label class="form-label">Jumlah</label><input type="text" name="storage_jumlah" value="{{ old('storage_jumlah', $server->storage_jumlah) }}" class="form-input"></div>
                    <div><label class="form-label">Total</label><input type="text" name="storage_kapasitas_total" value="{{ old('storage_kapasitas_total', $server->storage_kapasitas_total) }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Server Fisik</label><input type="text" name="server_fisik" value="{{ old('server_fisik', $server->server_fisik) }}" class="form-input"></div>
                    <div><label class="form-label">Keterangan</label><input type="text" name="keterangan_tambahan" value="{{ old('keterangan_tambahan', $server->keterangan_tambahan) }}" class="form-input"></div>
                </div>
                <div><label class="form-label">Gambar</label><input type="file" name="gambar" accept=".jpg,.jpeg" class="form-input !p-2"></div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <a href="{{ route('data-server.show', $server->id) }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
