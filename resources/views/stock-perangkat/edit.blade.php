@extends('layouts.app')
@section('title', 'Edit Stock Perangkat')
@section('content')
<div class="max-w-2xl">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">✏️ Edit — {{ $item->nama_barang }}</h2>
            <a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('stock-perangkat.update', $item->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div><label class="form-label">Nama Barang *</label><input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" class="form-input" required></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Type</label><input type="text" name="type_barang" value="{{ old('type_barang', $item->type_barang) }}" class="form-input"></div>
                    <div><label class="form-label">Supplai</label><input type="text" name="supplai" value="{{ old('supplai', $item->supplai) }}" class="form-input"></div>
                </div>
                <div><label class="form-label">Kondisi *</label>
                    <select name="kondisi" class="form-select" required>
                        @foreach(['BAIK','RUSAK','PERLU SERVICE'] as $k)
                            <option value="{{ $k }}" {{ old('kondisi', $item->kondisi) === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label">Keterangan</label><textarea name="keterangan" rows="3" class="form-input">{{ old('keterangan', $item->keterangan) }}</textarea></div>
                <div><label class="form-label">Foto</label><input type="file" name="foto" accept="image/*" class="form-input !p-2"></div>
                <div class="flex gap-3"><button type="submit" class="btn btn-primary">💾 Update</button><a href="{{ route('stock-perangkat.index') }}" class="btn btn-secondary">Batal</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
