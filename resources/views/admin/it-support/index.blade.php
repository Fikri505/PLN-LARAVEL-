@extends('layouts.app')
@section('title', 'Master IT Support')
@section('content')
<div class="space-y-6">
    <div class="card animate-fade-in-up">
        <div class="card-header"><h2 class="text-lg font-bold">👨‍💻 Tambah PIC IT Support</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.it-support.store') }}" class="flex gap-3 items-end">
                @csrf
                <div class="flex-1"><label class="form-label text-xs">Nama PIC *</label><input type="text" name="name" class="form-input" required></div>
                <button type="submit" class="btn btn-primary">+ Tambah</button>
            </form>
        </div>
    </div>
    <div class="card animate-fade-in-up">
        <div class="card-header"><h2 class="text-lg font-bold">📋 Daftar PIC IT Support</h2></div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>No</th><th>Nama</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($items as $i => $item)
                    <tr>
                        <td class="text-center">{{ $items->firstItem() + $i }}</td>
                        <td class="font-semibold">{{ $item->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.it-support.toggle', $item->id) }}" class="inline">@csrf
                                <button class="badge {{ $item->is_active ? 'badge-success' : 'badge-danger' }} cursor-pointer hover:opacity-80">{{ $item->is_active ? '✅ Aktif' : '❌ Nonaktif' }}</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.it-support.destroy', $item->id) }}" class="inline delete-form">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm !px-2" data-tippy-content="Hapus PIC">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center opacity-70">
                                <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-slate-500 font-medium text-lg">Belum Ada Data</p>
                                <p class="text-slate-400 text-sm mt-1">Silakan tambah data baru untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())<div class="card-body border-t border-slate-100">{{ $items->links() }}</div>@endif
    </div>
</div>
@endsection
