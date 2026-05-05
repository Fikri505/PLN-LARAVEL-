@extends('layouts.app')
@section('title', 'Entry Jadwal Baru')

@section('content')
<div class="w-full">
    <div class="card animate-fade-in-up">
        <div class="card-header">
            <h2 class="text-lg font-bold">📅 Entry Jadwal Pemesanan Ruangan</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('data-jadwal.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Start Date *</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">End Date *</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">PIC Acara *</label>
                        <input type="text" name="pic_acara" value="{{ old('pic_acara') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Acara *</label>
                        <input type="text" name="nama_acara" value="{{ old('nama_acara') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Ruang Rapat *</label>
                        <select name="meeting_room" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($meetingRooms as $room)
                                <option value="{{ $room }}" {{ old('meeting_room') === $room ? 'selected' : '' }}>{{ $room }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Pelaksanaan *</label>
                        <select name="pelaksanaan" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['ONLINE', 'OFFLINE', 'HYBRID'] as $opt)
                                <option value="{{ $opt }}" {{ old('pelaksanaan') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Standby / On Call *</label>
                        <select name="standby_status" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['STANDBY', 'ON CALL'] as $opt)
                                <option value="{{ $opt }}" {{ old('standby_status') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tindak Lanjut *</label>
                        <select name="tindak_lanjut" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach(['SOLVED', 'UNSOLVED'] as $opt)
                                <option value="{{ $opt }}" {{ old('tindak_lanjut') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">PIC IT Support</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 p-3 bg-slate-50 rounded-xl">
                        @foreach($picItOptions as $pic)
                        <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-white p-2 rounded-lg transition">
                            <input type="checkbox" name="pic_it_support[]" value="{{ $pic }}" class="rounded text-pln-blue focus:ring-pln-blue">
                            <span>{{ $pic }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="form-label">Detail Kebutuhan</label>
                    <textarea name="kebutuhan_detail" rows="3" class="form-input">{{ old('kebutuhan_detail') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('data-jadwal.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
