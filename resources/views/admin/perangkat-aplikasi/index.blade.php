@extends('layouts.app')
@section('title', 'Master Perangkat Aplikasi')
@section('content')
@php
    $tabs = [
        'jenis_perangkat' => ['title' => 'Jenis Perangkat', 'icon' => '🖥️'],
        'brand' => ['title' => 'Brand', 'icon' => '🏷️'],
        'lokasi' => ['title' => 'Lokasi', 'icon' => '📍'],
        'bidang' => ['title' => 'Bidang', 'icon' => '🏢'],
        'msb' => ['title' => 'MSB/Sub Bidang', 'icon' => '📋'],
    ];
@endphp

<div class="space-y-6">
    @foreach($tabs as $key => $tab)
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-base font-bold">{{ $tab['icon'] }} {{ $tab['title'] }}</h2>
            <span class="badge badge-neutral">{{ ${$key}->total() }} data</span>
        </div>
        <div class="card-body border-b border-slate-100">
            <form method="POST" action="{{ route('admin.master-perangkat-aplikasi.store') }}" class="flex gap-2 items-end">
                @csrf
                <input type="hidden" name="type" value="{{ $key }}">
                <div class="flex-1"><input type="text" name="name" class="form-input" placeholder="Tambah {{ strtolower($tab['title']) }} baru..." required></div>
                <button type="submit" class="btn btn-primary btn-sm">+ Tambah</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>No</th><th>Nama</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach(${$key} as $i => $item)
                    <tr>
                        <td class="text-center text-xs">{{ ${$key}->firstItem() + $i }}</td>
                        <td class="text-sm font-medium">{{ $item->name }}</td>
                        <td><form method="POST" action="{{ route('admin.master-perangkat-aplikasi.toggle', [$key, $item->id]) }}" class="inline">@csrf<button class="badge {{ $item->is_active ? 'badge-success' : 'badge-danger' }} cursor-pointer hover:opacity-80 text-[10px]">{{ $item->is_active ? '✅' : '❌' }}</button></form></td>
                        <td><form method="POST" action="{{ route('admin.master-perangkat-aplikasi.destroy', $item->id) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<input type="hidden" name="type" value="{{ $key }}"><button class="btn btn-danger btn-sm !px-2">🗑️</button></form></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(${$key}->hasPages())<div class="p-3 border-t border-slate-100">{{ ${$key}->links() }}</div>@endif
    </div>
    @endforeach
</div>
@endsection
