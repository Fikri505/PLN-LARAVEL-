@extends('layouts.app')
@section('title', 'Detail Server: ' . $server->ind)
@section('content')
<div class="space-y-6">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">🖥️ {{ $server->ind }} — {{ $server->fungsi_server }}</h2>
            <div class="flex gap-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('data-server.edit', $server->id) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                @endif
                <a href="{{ route('data-server.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">IND</span><span class="text-sm font-semibold">{{ $server->ind }}</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">IP Address</span><code class="bg-slate-100 px-2 py-0.5 rounded text-xs">{{ $server->ip }}</code></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Fungsi</span><span class="text-sm font-semibold">{{ $server->fungsi_server }}</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Merk / Type</span><span class="text-sm">{{ $server->merk }} {{ $server->type }}</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">OS</span><span class="text-sm">{{ $server->system_operasi ?: '-' }}</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Status</span><span class="badge {{ $server->status_server === 'HIDUP' ? 'badge-success' : 'badge-danger' }}">{{ $server->status_server }}</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Server Fisik</span><span class="text-sm">{{ $server->server_fisik ?: '-' }}</span></div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Processor</span><span class="text-sm">{{ $server->processor_merk }} {{ $server->processor_type }} ({{ $server->processor_kecepatan }} GHz, {{ $server->processor_core }} cores)</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">RAM</span><span class="text-sm">{{ $server->ram_jenis }} {{ $server->ram_kapasitas }} ({{ $server->ram_jumlah_keping }} keping)</span></div>
                    <div class="flex justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Storage</span><span class="text-sm">{{ $server->storage_jenis }} {{ $server->storage_kapasitas_total }} ({{ $server->storage_jumlah }} unit)</span></div>
                    @if($server->detail)<div class="py-2"><span class="text-sm text-slate-500 block mb-1">Detail</span><p class="text-sm text-slate-700">{{ $server->detail }}</p></div>@endif
                    @if($server->gambar)
                        <div class="py-2"><span class="text-sm text-slate-500 block mb-2">Gambar</span><img src="{{ asset('storage/' . $server->gambar) }}" class="rounded-xl max-h-48 border"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance History --}}
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-base font-bold">🔧 History Pemeliharaan</h3>
            @if($server->status_server === 'HIDUP')
                <a href="{{ route('maintenance.create', $server->id) }}" class="btn btn-success btn-sm">+ Tambah</a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>No</th><th>Waktu</th><th>Temuan</th><th>Dicek Oleh</th><th>Kondisi</th><th>Status</th><th>Gambar</th></tr></thead>
                <tbody>
                    @forelse($server->maintenances->sortByDesc('created_at') as $i => $m)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-xs">{{ $m->waktu_pemeliharaan }}</td>
                        <td class="text-xs max-w-[200px]">{{ Str::limit($m->temuan, 80) }}</td>
                        <td class="text-xs">{{ $m->dicek_oleh }}</td>
                        <td><span class="badge {{ $m->kondisi === 'HIDUP' ? 'badge-success' : 'badge-danger' }}">{{ $m->kondisi }}</span></td>
                        <td><span class="badge {{ $m->status === 'AMAN' ? 'badge-success' : 'badge-warning' }}">{{ $m->status }}</span></td>
                        <td>@if($m->gambar)<img src="{{ asset('storage/' . $m->gambar) }}" class="w-12 h-9 object-cover rounded border">@else — @endif</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-text-muted">Belum ada history pemeliharaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
