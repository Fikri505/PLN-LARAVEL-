@extends('layouts.app')
@section('title', 'Tambah IT Support')
@section('content')
<div class="w-full">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">👨‍💻 Tambah IT Support Jateng</h2>
            <a href="{{ route('it-support-jateng.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('it-support-jateng.store') }}" class="space-y-4">
                @csrf
                <div><label class="form-label">Nama *</label><input type="text" name="nama" value="{{ old('nama') }}" class="form-input" required></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-input"></div>
                    <div><label class="form-label">No HP</label><input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Penempatan</label><input type="text" name="penempatan" value="{{ old('penempatan') }}" class="form-input"></div>
                    <div><label class="form-label">OPS/STI</label><input type="text" name="ops_sti" value="{{ old('ops_sti') }}" class="form-input"></div>
                </div>
                <div class="flex gap-3"><button type="submit" class="btn btn-primary">💾 Simpan</button><a href="{{ route('it-support-jateng.index') }}" class="btn btn-secondary">Batal</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
