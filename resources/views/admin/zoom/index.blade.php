@extends('layouts.app')
@section('title', 'Master Zoom')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Zoom Units --}}
    <div class="space-y-4">
        <div class="card animate-fade-in-up">
            <div class="card-header"><h2 class="text-base font-bold">🏢 Tambah Unit</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.zoom.unit.store') }}" class="flex gap-2 items-end">
                    @csrf
                    <div class="flex-1"><label class="form-label text-xs">Nama Unit *</label><input type="text" name="unit_name" class="form-input" required></div>
                    <button type="submit" class="btn btn-primary btn-sm">+</button>
                </form>
            </div>
        </div>
        <div class="card animate-fade-in-up">
            <div class="card-header"><h3 class="text-sm font-bold">📋 Daftar Unit ({{ $units->total() }})</h3></div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr><th>No</th><th>Nama</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($units as $i => $u)
                        <tr>
                            <td class="text-center text-xs">{{ $units->firstItem() + $i }}</td>
                            <td class="text-sm font-medium">{{ $u->name }}</td>
                            <td><form method="POST" action="{{ route('admin.zoom.unit.toggle', $u->id) }}" class="inline">@csrf<button class="badge {{ $u->is_active ? 'badge-success' : 'badge-danger' }} cursor-pointer hover:opacity-80 text-[10px]">{{ $u->is_active ? '✅' : '❌' }}</button></form></td>
                            <td><form method="POST" action="{{ route('admin.zoom.unit.destroy', $u->id) }}" onsubmit="return confirm('Hapus?')" class="inline">@csrf @method('DELETE')<button class="btn btn-danger btn-sm !px-2 !py-1 text-xs">🗑️</button></form></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($units->hasPages())<div class="p-3 border-t border-slate-100">{{ $units->links() }}</div>@endif
        </div>
    </div>

    {{-- Zoom Links --}}
    <div class="space-y-4">
        <div class="card animate-fade-in-up">
            <div class="card-header"><h2 class="text-base font-bold">🔗 Tambah Link Zoom</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.zoom.link.store') }}" class="flex gap-2 items-end">
                    @csrf
                    <div class="flex-1"><label class="form-label text-xs">Email Zoom *</label><input type="email" name="link_email" class="form-input" required></div>
                    <button type="submit" class="btn btn-primary btn-sm">+</button>
                </form>
            </div>
        </div>
        <div class="card animate-fade-in-up">
            <div class="card-header"><h3 class="text-sm font-bold">📋 Daftar Link ({{ $links->total() }})</h3></div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr><th>No</th><th>Email</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($links as $i => $l)
                        <tr>
                            <td class="text-center text-xs">{{ $links->firstItem() + $i }}</td>
                            <td class="text-sm font-medium">{{ $l->email }}</td>
                            <td><form method="POST" action="{{ route('admin.zoom.link.toggle', $l->id) }}" class="inline">@csrf<button class="badge {{ $l->is_active ? 'badge-success' : 'badge-danger' }} cursor-pointer hover:opacity-80 text-[10px]">{{ $l->is_active ? '✅' : '❌' }}</button></form></td>
                            <td><form method="POST" action="{{ route('admin.zoom.link.destroy', $l->id) }}" onsubmit="return confirm('Hapus?')" class="inline">@csrf @method('DELETE')<button class="btn btn-danger btn-sm !px-2 !py-1 text-xs">🗑️</button></form></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($links->hasPages())<div class="p-3 border-t border-slate-100">{{ $links->links() }}</div>@endif
        </div>
    </div>
</div>
@endsection
