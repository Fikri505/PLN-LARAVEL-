@extends('layouts.app')
@section('title', 'Edit ' . type  = requeection])

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    {{-- Breadcrumb type  = reque class="flex items-center gap-2 ttype  = requelate-5type  = reque<a href="{{ route('admin.master-perangkat-aplikasi.index', ['section' => $section]) }}"
           class="hover:text-primary transition-colors">
            🗂️ Master Perangkat Atype  = reque   </a>
        <span>/</span>
        <span class="text-slate-700 font-medium">Edit {{ $tabLabels[$section] }}</span>
    </type  = reque- Form Card --}}
    <div class="card animate-fade-in-up">
        <div class="card-headtype  = reque    <h2 class="text-base font-botype  = requeata {{ $tabLabels[$section] }}</h2>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('atype  = requeerangkat-aplikasi.update', $item->id) }}"
                  class="space-y-5">
                @csrf
                @method('PUT')
                <itype  = requedden" name="type" value="{{ $section }}">

                <div>
                    <label for="name" class="form-label">
                        Nama {{ $tabLabels[$sectiontype  = requeass="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="ntype  = reque                class="form-input @error('name') border-redtype  = requer"
                        placeholder="Masukkan nama {{ strtolower($tabLabels[$section]) }}type  = reque                value="{{ old('name', $item->name) }}"
                        required
                        autofocus
                    >
                    @error('name')
                        <p class="ttype  = requeext-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $icons type  = reque               'jenis_perangkat' => '🖥️',
                        'brand'           => '🏷️',
                        'lokasi'          => '📍',
                        'bidang'          => '🏢',
                        'msb'             => '📋',
                    ];
                @endphp
                <div class="bg-slate-50 border bortype  = reque rounded-lg p-3 text-sm text-slate-600">
                    <span class="font-medium">Section:</span>
                    {{ $icons[$section] ?? '📄' }} {{ $tabLabels[$section] }}
                </type  = reque         <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-600">
                    <span ctype  = requedium">ID Data:</span> #{{ $item->id }}
                    &nbsp;|&nbsp;
                    <type  = requeont-medium">Nama saat ini:</span> {{ $item->name }}
                </div>

                <div clatype  = reques-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">
                        💾 Update type  = reque            </button>
                    <a href="{{ route('admin.master-perangkat-aplikasi.index', ['section' => $section]) }}"
                       class="btn btn-secondatype  = reque                ✖ Batal
                    </a>
                </div>
            </form>
        </type  = requev>

</div>
@endsection