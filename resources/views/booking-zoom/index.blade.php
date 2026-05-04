@extends('layouts.app')
@section('title', 'Booking Zoom')

@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold">🎥 Booking Jadwal Zoom</h2>
            <p class="text-xs text-text-muted mt-1">
                Total: <strong>{{ $rows->count() }}</strong> booking
                @if($isFiltered) <span class="badge badge-info ml-2">🔍 Filter aktif</span> @endif
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('booking-zoom.ketersediaan') }}" class="btn btn-success btn-sm">🔍 Cek Ketersediaan</a>
            <a href="{{ route('booking-zoom.create') }}" class="btn btn-primary btn-sm">+ Booking Baru</a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('booking-zoom.index') }}">
        <div class="bg-slate-50/80 border-b border-slate-100 px-6 py-4">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">📋 Filter Riwayat Booking</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Unit</label>
                    <select name="filter_unit" class="form-select !py-2 text-xs">
                        <option value="">Semua Unit</option>
                        @foreach($unitsAll as $u)
                            <option value="{{ $u }}" {{ request('filter_unit') == $u ? 'selected' : '' }}>{{ strtoupper($u) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Zoom Link</label>
                    <select name="filter_zoom" class="form-select !py-2 text-xs">
                        <option value="">Semua Zoom</option>
                        @foreach($zoomLinksAll as $zl)
                            <option value="{{ $zl }}" {{ request('filter_zoom') == $zl ? 'selected' : '' }}>{{ $zl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Dari</label>
                    <input type="date" name="filter_from" value="{{ request('filter_from') }}" class="form-input !py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Sampai</label>
                    <input type="date" name="filter_to" value="{{ request('filter_to') }}" class="form-input !py-2 text-xs">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-1">🔍 Filter</button>
                    @if($isFiltered)
                        <a href="{{ route('booking-zoom.index') }}" class="btn btn-secondary btn-sm">✕</a>
                    @endif
                </div>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th><th>Tanggal & Jam</th><th>Unit</th><th>Link Zoom</th>
                    <th>Keterangan</th><th>Dibuat Oleh</th><th>Dibuat</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $r)
                <tr>
                    <td class="text-center font-medium">{{ $i + 1 }}</td>
                    <td>
                        @if($r->start_datetime && $r->end_datetime)
                            @php
                                $start = $r->start_datetime;
                                $end = $r->end_datetime;
                                $diff = $start->diff($end);
                                $sameDay = $start->toDateString() === $end->toDateString();
                                $dur = '';
                                if ($diff->days > 0) $dur = $diff->days . ' hr ' . ($diff->h ? $diff->h . ' jam' : '');
                                elseif ($diff->h > 0 && $diff->i > 0) $dur = $diff->h . ' jam ' . $diff->i . ' mnt';
                                elseif ($diff->h > 0) $dur = $diff->h . ' jam';
                                elseif ($diff->i > 0) $dur = $diff->i . ' menit';
                            @endphp
                            <div>
                                <span class="font-bold text-sm text-slate-800 block">{{ $start->format('d M Y') }}</span>
                                <span class="text-xs text-slate-500">
                                    {{ $start->format('H:i') }}
                                    @if(!$sameDay) → {{ $end->format('d M Y') }} {{ $end->format('H:i') }}
                                    @else – {{ $end->format('H:i') }} @endif
                                </span>
                                @if($dur)
                                    <span class="inline-block bg-slate-100 text-slate-500 rounded-lg px-2 py-0.5 text-[10px] font-semibold mt-1">⏱ {{ $dur }}</span>
                                @endif
                            </div>
                        @else
                            <div>
                                <span class="font-bold text-sm block">{{ $r->booking_date->format('d M Y') }}</span>
                                <span class="text-xs text-slate-500">{{ $r->booking_time }}</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($r->unit)
                            <span class="badge badge-info">{{ strtoupper($r->unit) }}</span>
                        @else <span class="text-slate-300">—</span> @endif
                    </td>
                    <td><span class="inline-block bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-semibold">{{ $r->zoom_link }}</span></td>
                    <td class="max-w-[180px] truncate text-xs text-slate-500">{{ $r->keterangan ?: '—' }}</td>
                    <td class="text-xs">{{ $r->creator?->username ?? '-' }}</td>
                    <td class="text-xs text-slate-400">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') : '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('booking-zoom.destroy', $r->id) }}" onsubmit="return confirm('Yakin hapus booking ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-12 text-text-muted">
                    {{ $isFiltered ? '😕 Tidak ada data yang sesuai filter.' : 'Belum ada data booking zoom.' }}
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
