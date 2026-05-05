@extends('layouts.app')
@section('title', 'Edit ' . $tabLabels[$section])

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.master-perangkat-aplikasi.index', ['section' => $section]) }}"
           class="hover:text-primary transition-colors">
            🗂️ Master Perangkat Aplikasi
        </a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Edit {{ $tabLabels[$section] }}</span>
    </div>

    {{-- Form Card --}}
    <div class="card animate-fade-in-up">
        <div class="card-header">
            <h2 class="text-base font-bold">✏️ Edit Data {{ $tabLabels[$section] }}</h2>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.master-perangkat-aplikasi.update', $item->id) }}"
                  class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $section }}">

                {{-- Nama --}}
                <div>
                    <label for="name" class="form-label">
                        Nama {{ $tabLabels[$section] }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama {{ strtolower($tabLabels[$section]) }}..."
                        value="{{ old('name', $item->name) }}"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Section --}}
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm text-slate-600">
                    <span class="font-medium">Section:</span>
                    @php
                        $icons = [
                            'jenis_perangkat' => '🖥️',
                            'brand'           => '🏷️',
                            'lokasi'          => '📍',
                            'bidang'          => '🏢',
                            'msb'             => '📋',
                        ];
                    @endphp
                    {{ $icons[$section] ?? '📄' }} {{ $tabLabels[$section] }}
                </div>

                {{-- Info ID --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-600">
                    <span class="font-medium">ID Data:</span> #{{ $item->id }}
                    &nbsp;|&nbsp;
                    <span class="font-medium">Nama saat ini:</span> {{ $item->name }}
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">
                        💾 Update Data
                    </button>
                    <a href="{{ route('admin.master-perangkat-aplikasi.index', ['section' => $section]) }}"
                       class="btn btn-secondary">
                        ✖ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection