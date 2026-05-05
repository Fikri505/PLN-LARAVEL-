@extends('layouts.app')
@section('title', 'Tambah Stock Perangkat')
@section('content')
<div class="w-full">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">📦 Tambah Stock Perangkat</h2>
            <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('stock-perangkat.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div><label class="form-label">Nama Barang *</label><input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-input" required></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Type Barang</label><input type="text" name="type_barang" value="{{ old('type_barang') }}" class="form-input"></div>
                    <div><label class="form-label">Supplai</label><input type="text" name="supplai" value="{{ old('supplai') }}" class="form-input"></div>
                </div>
                <div><label class="form-label">Kondisi *</label>
                    <select name="kondisi" class="form-select" required>
                        <option value="BAIK" {{ old('kondisi') === 'BAIK' ? 'selected' : '' }}>✅ BAIK</option>
                        <option value="RUSAK" {{ old('kondisi') === 'RUSAK' ? 'selected' : '' }}>❌ RUSAK</option>
                        <option value="PERLU SERVICE" {{ old('kondisi') === 'PERLU SERVICE' ? 'selected' : '' }}>⚠️ PERLU SERVICE</option>
                    </select>
                </div>
                <div><label class="form-label">Keterangan</label><textarea name="keterangan" rows="3" class="form-input">{{ old('keterangan') }}</textarea></div>
                <div><label class="form-label">Foto (opsional)</label><input type="file" name="foto" accept="image/*" class="form-input !p-2"></div>
                <div class="flex gap-3"><button type="submit" class="btn btn-primary">💾 Simpan</button><a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary">Batal</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
