@extends('layouts.app')
@section('title', 'Tambah Mutasi Perangkat')
@section('content')
<div class="w-full">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">🔄 Mutasi — {{ $perangkat->nama_barang }}</h2>
            <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            {{-- Info Perangkat --}}
            <div class="bg-slate-50 rounded-lg p-4 mb-4 text-sm space-y-1">
                <p><span class="font-semibold">Nama:</span> {{ $perangkat->nama_barang }}</p>
                <p><span class="font-semibold">Type:</span> {{ $perangkat->type_barang ?: '-' }}</p>
                <p><span class="font-semibold">Stok Saat Ini:</span>
                    <strong class="text-blue-600">{{ $perangkat->jumlah }}</strong> unit
                </p>
                <p><span class="font-semibold">Kondisi:</span> {{ ucfirst($perangkat->kondisi) }}</p>
            </div>

            <form method="POST" action="{{ route('mutasi-perangkat.store', $perangkat->id) }}" class="space-y-4">
                @csrf

                {{-- Tipe Mutasi --}}
                <div>
                    <label class="form-label">Tipe Mutasi *</label>
                    <div class="flex gap-4 mt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="masuk"
                                   {{ old('tipe', 'masuk') === 'masuk' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600">
                            <span class="text-sm font-medium text-green-700">📥 Masuk (tambah stok)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="keluar"
                                   {{ old('tipe') === 'keluar' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600">
                            <span class="text-sm font-medium text-red-700">📤 Keluar (kurangi stok)</span>
                        </label>
                    </div>
                    @error('tipe')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Jumlah Mutasi *</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                               class="form-input" required>
                        <p class="text-xs text-slate-400 mt-1">Stok tersedia: {{ $perangkat->jumlah }} unit</p>
                        @error('jumlah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Kondisi *</label>
                        <select name="kondisi" class="form-select" required>
                            <option value="baru" {{ old('kondisi') === 'baru' ? 'selected' : '' }}>✅ Baru</option>
                            <option value="normal" {{ old('kondisi', 'normal') === 'normal' ? 'selected' : '' }}>🔄 Normal</option>
                        </select>
                        @error('kondisi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-input"
                              placeholder="Contoh: Pengiriman ke Divisi IT, pinjam untuk proyek X...">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Simpan Mutasi</button>
                    <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection