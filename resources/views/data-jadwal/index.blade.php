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
                <tr><td colspan="9" class="text-center py-10 text-text-muted">Belum ada data jadwal</td></tr>
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
