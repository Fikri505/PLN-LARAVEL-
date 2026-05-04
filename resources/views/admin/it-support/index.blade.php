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
                    @foreach($items as $i => $item)
                    <tr>
                        <td class="text-center">{{ $items->firstItem() + $i }}</td>
                        <td class="font-semibold">{{ $item->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.it-support.toggle', $item->id) }}" class="inline">@csrf
                                <button class="badge {{ $item->is_active ? 'badge-success' : 'badge-danger' }} cursor-pointer hover:opacity-80">{{ $item->is_active ? '✅ Aktif' : '❌ Nonaktif' }}</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.it-support.destroy', $item->id) }}" onsubmit="return confirm('Hapus?')" class="inline">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm !px-2">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($items->hasPages())<div class="card-body border-t border-slate-100">{{ $items->links() }}</div>@endif
    </div>
</div>
@endsection
