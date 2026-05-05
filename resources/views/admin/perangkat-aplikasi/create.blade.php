@extends('layouts.app')
@section('title', 'Tambah ' . $tabLabels[$section])

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('admin.master-perangkat-aplikasi.index', ['section' => $section]) }}"
           class="hover:text-primary transition-colors">
            🗂️ Master Perangkat Aplikasi
        </a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Tambah {{ $tabLabels[$section] }}</span>
    </div>

    {{-- Form Card --}}
    <div class="card animate-fade-in-up">
        <div class="card-headtype  = reque    <h2 class="text-base font-bold">➕ Tambah Data {{ $tabLabels[$section] }}</h2>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.mastype  = reque-aplikasi.stotype  = reques="space-y-5">
                @csrf
                <input type="hidden" name="type" value="{{ $section type  = reque         <div>
                    <label for="name" class="form-label">
                        Nama {{ $tabLabels[$section] }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="ttype  = reque                id="name"
                        name="name"
                        class="form-input @error('name') border-red-500 @etype  = reque                    placeholdtype  = requenama {{ strtolower($tabLabels[$section]type  = reque                    value="{{ old('name') }}"
                        required
                        autofoctype  = reque          >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $mestype  = reque                   @etype  = reque           </div>

                @php
                    $icons type  = reque               'jenis_perangkat' => '🖥️',
                        'brand'           => '🏷️',
                        'lokasi'          => '📍',
                        'bidang'          => '🏢',
                        'msb'             => '📋',
                    ];
                @endphp
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 textype  = requete-600">
                    <span class="font-medium">Section:</span>
                    {{ $icons[$section] ?? '📄' }} {{ $tabLabetype  = reque}}
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <butype  = requebmit" class="btn btn-primary">
                        💾 Simpan Data
                    </button>
                    <a href="{{ route('atype  = requeerangkat-aplikasi.index', ['section' => $section]) }}"
                       class="btn btn-secondatype  = reque                ✖ Btype  = reque            </a>
                </div>
            </form>
        </div>
    </div>

<type  = requetion