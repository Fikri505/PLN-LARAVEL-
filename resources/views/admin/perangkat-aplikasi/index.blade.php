@extends('layouts.app')
@section('title', 'Master Perangkat Aplikasi')

@section('content')

@php
$icons = [
    'jenis_perangkat' => '🖥️',
    'brand'           => '🏷️',
    'lokasi'          => '📍',
    'bidang'          => '🏢',
    'msb'             => '📋',
];
$createUrl = route('admin.master-perangkat-aplikasi.create', ['section' => $section]);
@endphp

<div class="space-y-4">
    <div class="card animate-fade-in-up">

        {{-- Card Header --}}
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold">🗂️ Master Perangkat Aplikasi</h2>
                <span class="badge badge-neutral">Total: {{ $items->total() }} data</span>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="card-body border-b border-slate-100">
            <div class="flex items-center justify-end gap-3">

                {{-- Dropdown Pilih Section --}}
                <div class="relative">
                    <button
                        id="sectionDropdownBtn"
                        type="button"
                        class="btn btn-secondary btn-sm flex items-center gap-2"
                        onclick="toggleSectionDropdown()"
                    >
                        📂 Pilih Master:
                        <span class="font-semibold text-primary">{{ $tabLabels[$section] }}</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div
                        id="sectionDropdown"
                        class="hidden absolute z-50 top-full right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg min-w-[210px]"
                    >
                        @foreach($tabLabels as $tabKey => $tabLabel)
                        @php $tabUrl = route('admin.master-perangkat-aplikasi.index', ['section' => $tabKey]); @endphp
                        <a
                            href="{{ $tabUrl }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-slate-50 transition-colors
                                {{ $section === $tabKey ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-700' }}"
                        >
                            {{ $icons[$tabKey] ?? '📄' }} {{ $tabLabel }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Button Tambah --}}
                <a href="{{ $createUrl }}" class="btn btn-primary btn-sm flex items-center gap-1">
                    ➕ Input {{ $tabLabels[$section] }}
                </a>

            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center w-12">No</th>
                        <th>Nama {{ $tabLabels[$section] }}</th>
                        <th class="text-center w-24">Status</th>
                        <th class="text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $rowIndex => $item)
                    @php
                        $toggleUrl  = route('admin.master-perangkat-aplikasi.toggle', [$section, $item->id]);
                        $editUrl    = route('admin.master-perangkat-aplikasi.edit', $item->id) . '?type=' . $section;
                        $destroyUrl = route('admin.master-perangkat-aplikasi.destroy', $item->id);
                        $rowNumber  = $items->firstItem() + $rowIndex;
                    @endphp
                    <tr>
                        <td class="text-center text-xs text-slate-500">
                            {{ $rowNumber }}
                        </td>
                        <td class="text-sm font-medium">
                            {{ $item->name }}
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ $toggleUrl }}" class="inline">
                                @csrf
                                <button
                                    type="submit"
                                    class="badge cursor-pointer hover:opacity-80 text-[10px]
                                        {{ $item->is_active ? 'badge-success' : 'badge-danger' }}"
                                >
                                    {{ $item->is_active ? '✅ Aktif' : '❌ Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ $editUrl }}" class="btn btn-warning btn-sm !px-2 !py-1 text-xs">
                                    ✏️ Edit
                                </a>
                                <form
                                    method="POST"
                                    action="{{ $destroyUrl }}"
                                    class="inline delete-form"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="{{ $section }}">
                                    <button type="submit" class="btn btn-danger btn-sm !px-2 !py-1 text-xs" data-tippy-content="Hapus {{ $tabLabels[$section] }}">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12">
                            @php $emptyCreateUrl = route('admin.master-perangkat-aplikasi.create', ['section' => $section]); @endphp
                            <div class="flex flex-col items-center justify-center opacity-70">
                                <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-slate-500 font-medium text-lg">Belum Ada Data {{ $tabLabels[$section] }}</p>
                                <a href="{{ $emptyCreateUrl }}" class="btn btn-primary btn-sm mt-3">
                                    ➕ Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($items->hasPages())
        <div class="border-t border-slate-100 px-4">
            {{ $items->links() }}
        </div>
        @endif

    </div>
</div>

<script>
function toggleSectionDropdown() {
    document.getElementById('sectionDropdown').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    var btn      = document.getElementById('sectionDropdownBtn');
    var dropdown = document.getElementById('sectionDropdown');
    if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

@endsection