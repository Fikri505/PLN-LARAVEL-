@extends('layouts.app')
@section('title', 'Input Data Server')

@section('content')
<div class="max-w-4xl">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">🖥️ Input Data Server Baru</h2>
            <a href="{{ route('data-server.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('data-server.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-4">
                    <p class="text-sm font-bold text-slate-700">📋 Informasi Utama</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="form-label">IND *</label><input type="text" name="ind" value="{{ old('ind') }}" class="form-input" required></div>
                        <div><label class="form-label">IP Address *</label><input type="text" name="ip" value="{{ old('ip') }}" class="form-input" required></div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status_server" class="form-select">
                                <option value="HIDUP" {{ old('status_server', 'HIDUP') === 'HIDUP' ? 'selected' : '' }}>🟢 HIDUP</option>
                                <option value="MATI" {{ old('status_server') === 'MATI' ? 'selected' : '' }}>🔴 MATI</option>
                            </select>
                        </div>
                    </div>
                    <div><label class="form-label">Fungsi Server *</label><input type="text" name="fungsi_server" value="{{ old('fungsi_server') }}" class="form-input" required></div>
                    <div><label class="form-label">Detail</label><textarea name="detail" rows="2" class="form-input">{{ old('detail') }}</textarea></div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-4">
                    <p class="text-sm font-bold text-slate-700">🔧 Spesifikasi Hardware</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="form-label">Merk</label><input type="text" name="merk" value="{{ old('merk') }}" class="form-input"></div>
                        <div><label class="form-label">Type</label><input type="text" name="type" value="{{ old('type') }}" class="form-input"></div>
                        <div><label class="form-label">Sistem Operasi</label><input type="text" name="system_operasi" value="{{ old('system_operasi') }}" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div><label class="form-label">Proc. Merk</label><input type="text" name="processor_merk" value="{{ old('processor_merk') }}" class="form-input"></div>
                        <div><label class="form-label">Proc. Type</label><input type="text" name="processor_type" value="{{ old('processor_type') }}" class="form-input"></div>
                        <div><label class="form-label">Kecepatan (GHz)</label><input type="text" name="processor_kecepatan" value="{{ old('processor_kecepatan') }}" class="form-input"></div>
                        <div><label class="form-label">Core</label><input type="text" name="processor_core" value="{{ old('processor_core') }}" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="form-label">RAM Jenis</label><input type="text" name="ram_jenis" value="{{ old('ram_jenis') }}" class="form-input"></div>
                        <div><label class="form-label">RAM Kapasitas</label><input type="text" name="ram_kapasitas" value="{{ old('ram_kapasitas') }}" class="form-input"></div>
                        <div><label class="form-label">Jumlah Keping</label><input type="text" name="ram_jumlah_keping" value="{{ old('ram_jumlah_keping') }}" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="form-label">Storage Jenis</label><input type="text" name="storage_jenis" value="{{ old('storage_jenis') }}" class="form-input"></div>
                        <div><label class="form-label">Jumlah Unit</label><input type="text" name="storage_jumlah" value="{{ old('storage_jumlah') }}" class="form-input"></div>
                        <div><label class="form-label">Kapasitas Total</label><input type="text" name="storage_kapasitas_total" value="{{ old('storage_kapasitas_total') }}" class="form-input"></div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-4">
                    <p class="text-sm font-bold text-slate-700">📎 Lainnya</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="form-label">Server Fisik</label><input type="text" name="server_fisik" value="{{ old('server_fisik') }}" class="form-input"></div>
                        <div><label class="form-label">Keterangan Tambahan</label><input type="text" name="keterangan_tambahan" value="{{ old('keterangan_tambahan') }}" class="form-input"></div>
                    </div>
                    <div>
                        <label class="form-label">Gambar (JPEG/JPG, maks 2MB)</label>
                        <input type="file" name="gambar" accept=".jpg,.jpeg" class="form-input !p-2">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('data-server.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
