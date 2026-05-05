@extends('layouts.app')
@section('title', 'Data Jadwal')

@section('content')
<div class="card animate-fade-in-up">
    <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold">📅 Data Jadwal Pemesanan Ruangan</h2>
            <p class="text-xs text-text-muted mt-1">Daftar semua jadwal pemesanan ruangan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('data-jadwal.export') }}" class="btn btn-success btn-sm">📥 Export CSV</a>
            <a href="{{ route('data-jadwal.create') }}" class="btn btn-primary btn-sm">+ Tambah Jadwal</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th><th>TRX ID</th><th>Tanggal</th><th>PIC Acara</th><th>Nama Acara</th>
                    <th>Ruangan</th><th>Pelaksanaan</th><th>Status</th><th>Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $row)
                <tr>
                    <td class="text-center font-medium">{{ $rows->firstItem() + $i }}</td>
                    <td><span class="badge badge-info font-mono text-[10px]">{{ $row->transaction_id }}</span></td>
                    <td class="text-xs">{{ \Carbon\Carbon::parse($row->start_date)->format('d M Y') }}</td>
                    <td>{{ $row->pic_acara }}</td>
                    <td class="font-medium">{{ $row->nama_acara }}</td>
                    <td>{{ $row->meeting_room }}</td>
                    <td><span class="badge badge-neutral">{{ $row->pelaksanaan }}</span></td>
                    <td><span class="badge badge-neutral">{{ $row->standby_status }}</span></td>
                    <td>
                        <span class="badge {{ $row->tindak_lanjut === 'SOLVED' ? 'badge-success' : 'badge-danger' }}">
                            {{ $row->tindak_lanjut }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center opacity-70">
                            <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-slate-500 font-medium text-lg">Belum Ada Data</p>
                            <p class="text-slate-400 text-sm mt-1">Silakan tambah jadwal baru untuk memulai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rows->hasPages())
    <div class="card-body border-t border-slate-100">
        {{ $rows->links() }}
    </div>
    @endif
</div>
@endsection
