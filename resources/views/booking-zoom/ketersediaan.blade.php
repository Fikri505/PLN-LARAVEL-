@extends('layouts.app')
@section('title', 'Cek Ketersediaan Zoom')

@section('content')
<div class="space-y-6">
    {{-- Date Range Filter --}}
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">🔍 Cek Ketersediaan Zoom</h2>
                <p class="text-xs text-text-muted mt-1">Pilih rentang tanggal untuk mengecek ketersediaan akun Zoom</p>
            </div>
            <a href="{{ route('booking-zoom.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('booking-zoom.ketersediaan') }}" class="space-y-5">
                <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    🔍 Pilih Tanggal untuk Cek Ketersediaan
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_1fr_auto] gap-4 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1.5">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ $filterFrom }}" class="form-input" required>
                    </div>
                    <div class="hidden sm:flex items-center text-slate-300 text-xl pt-5">→</div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ $filterTo }}" class="form-input" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-full">🔍 Cek Ketersediaan</button>
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-xs text-blue-700 flex items-start gap-2">
                    <span>💡</span>
                    <span>Data booking diambil <strong>secara langsung dari database</strong> setiap kali tombol ditekan — kondisi selalu up-to-date.</span>
                </div>
            </form>
        </div>
    </div>

    {{-- Results Table --}}
    @if($checked)
    <div class="card animate-fade-in-up">
        <div class="card-header">
            <h3 class="text-base font-bold">
                📋 Ketersediaan Zoom
                <span class="text-sm font-normal text-slate-500 ml-2">
                    {{ \Carbon\Carbon::parse($filterFrom)->format('d M Y') }}
                    @if($filterFrom !== $filterTo)
                        → {{ \Carbon\Carbon::parse($filterTo)->format('d M Y') }}
                    @endif
                </span>
            </h3>
            @php
                $kosongCount = $results->where('status', 'KOSONG')->count();
                $dipakaiCount = $results->where('status', 'DIPAKAI')->count();
            @endphp
            <p class="text-xs text-text-muted mt-1">
                <span class="text-emerald-600 font-semibold">{{ $kosongCount }} Kosong</span>
                · <span class="text-red-500 font-semibold">{{ $dipakaiCount }} Dipakai</span>
                · Total {{ $results->count() }} akun
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Akun Zoom</th>
                        <th>Status</th>
                        <th>Detail Booking (Rentang Tanggal)</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $i => $row)
                    <tr>
                        <td class="text-center font-medium">{{ $i + 1 }}</td>
                        <td class="text-sm">{{ $row['email'] }}</td>
                        <td>
                            <span class="badge {{ $row['status'] === 'KOSONG' ? 'badge-success' : 'badge-danger' }}">
                                {{ $row['status'] === 'KOSONG' ? '🟢 Kosong' : '🔴 Dipakai' }}
                            </span>
                        </td>
                        <td class="text-xs">
                            @if($row['bookings']->isEmpty())
                                <span class="inline-flex items-center gap-1 text-emerald-600">
                                    ✅ Tidak ada booking pada rentang ini
                                </span>
                            @else
                                <div class="space-y-1.5">
                                    @foreach($row['bookings'] as $b)
                                        <div class="bg-red-50 border border-red-200 rounded-lg px-2.5 py-1.5 text-xs">
                                            @if($b->start_datetime && $b->end_datetime)
                                                <span class="font-semibold text-red-700">
                                                    {{ $b->start_datetime->format('d M Y H:i') }} – {{ $b->end_datetime->format('d M Y H:i') }}
                                                </span>
                                            @else
                                                <span class="font-semibold text-red-700">
                                                    {{ $b->booking_date->format('d M Y') }} {{ $b->booking_time }}
                                                </span>
                                            @endif
                                            @if($b->unit)
                                                <span class="text-slate-500 ml-1">({{ $b->unit }})</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-xs text-slate-500 max-w-[150px]">
                            @if($row['bookings']->isNotEmpty())
                                @foreach($row['bookings'] as $b)
                                    @if($b->keterangan)
                                        <p>{{ Str::limit($b->keterangan, 40) }}</p>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td>
                            @if($row['status'] === 'KOSONG')
                                <a href="{{ route('booking-zoom.create', [
                                    'zoom_link' => $row['email'],
                                    'start_datetime' => \Carbon\Carbon::parse($filterFrom . ' ' . now()->format('H:i'))->format('Y-m-d\TH:i'),
                                    'end_datetime' => \Carbon\Carbon::parse($filterTo . ' ' . now()->addHour()->format('H:i'))->format('Y-m-d\TH:i'),
                                ]) }}" class="btn btn-primary btn-sm">
                                    📌 Booking
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
