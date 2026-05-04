@extends('layouts.app')
@section('title', 'Input Pemeliharaan — ' . $server->ind)
@section('content')
<div class="max-w-3xl">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">🔧 Input History Pemeliharaan</h2>
            <a href="{{ route('data-server.show', $server->id) }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-5"><span>🖥️</span> Server: <strong>{{ $server->ind }}</strong> — {{ $server->fungsi_server }}</div>
            <form method="POST" action="{{ route('maintenance.store', $server->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div><label class="form-label">Waktu Pemeliharaan *</label><input type="text" name="waktu_pemeliharaan" value="{{ old('waktu_pemeliharaan') }}" class="form-input" placeholder="Contoh: 15 Januari 2026" required></div>
                <div><label class="form-label">Temuan *</label><textarea name="temuan" rows="3" class="form-input" required>{{ old('temuan') }}</textarea></div>
                <div><label class="form-label">Dicek Oleh *</label><input type="text" name="dicek_oleh" value="{{ old('dicek_oleh') }}" class="form-input" required></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Kondisi *</label>
                        <select name="kondisi" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="HIDUP" {{ old('kondisi') === 'HIDUP' ? 'selected' : '' }}>🟢 HIDUP</option>
                            <option value="MATI" {{ old('kondisi') === 'MATI' ? 'selected' : '' }}>🔴 MATI</option>
                        </select>
                    </div>
                    <div><label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="AMAN" {{ old('status') === 'AMAN' ? 'selected' : '' }}>✅ AMAN</option>
                            <option value="PROBLEM" {{ old('status') === 'PROBLEM' ? 'selected' : '' }}>⚠️ PROBLEM</option>
                        </select>
                    </div>
                </div>
                <div><label class="form-label">Gambar (JPEG, maks 2MB)</label><input type="file" name="gambar" accept=".jpg,.jpeg" class="form-input !p-2"></div>
                <div class="flex gap-3"><button type="submit" class="btn btn-primary">💾 Simpan</button><a href="{{ route('data-server.show', $server->id) }}" class="btn btn-secondary">Batal</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
