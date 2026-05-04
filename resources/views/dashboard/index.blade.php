@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Overview statistik dan aktivitas terkini')

@section('content')
<div class="space-y-8">

    {{-- Rekapan Pemesanan Ruangan --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <span class="text-xl">📋</span>
            <div>
                <h3 class="text-base font-bold text-text-primary">Rekapan Pemesanan Ruangan</h3>
                <p class="text-xs text-text-muted">Data jadwal pemesanan ruangan secara keseluruhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="stat-gradient-1 rounded-2xl p-5 text-white shadow-lg shadow-pln-blue/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-1">
                <p class="text-sm font-semibold opacity-90 mb-2">📊 Total Jadwal</p>
                <p class="text-4xl font-black">{{ $rows->count() }}</p>
            </div>
            <div class="stat-gradient-2 rounded-2xl p-5 text-white shadow-lg shadow-pink-400/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-2">
                <p class="text-sm font-semibold opacity-90 mb-2">📅 Bulan Ini</p>
                <p class="text-4xl font-black">{{ $monthCount }}</p>
            </div>
            <div class="stat-gradient-3 rounded-2xl p-5 text-white shadow-lg shadow-sky-400/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-3">
                <p class="text-sm font-semibold opacity-90 mb-2">✅ Solved</p>
                <p class="text-4xl font-black">{{ $solvedCount }}</p>
            </div>
        </div>
    </div>

    {{-- Zoom Stats --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <span class="text-xl">🎥</span>
            <div>
                <h3 class="text-base font-bold text-text-primary">Rekapan Booking Zoom</h3>
                <p class="text-xs text-text-muted">Data booking zoom secara keseluruhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="stat-gradient-4 rounded-2xl p-5 text-white shadow-lg shadow-sky-500/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-4">
                <p class="text-sm font-semibold opacity-90 mb-2">🎥 Total Booking Zoom</p>
                <p class="text-4xl font-black">{{ $zoomTotal }}</p>
            </div>
            <div class="stat-gradient-5 rounded-2xl p-5 text-white shadow-lg shadow-violet-500/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-5">
                <p class="text-sm font-semibold opacity-90 mb-2">📅 Booking Bulan Ini</p>
                <p class="text-4xl font-black">{{ $zoomMonth }}</p>
            </div>
            <div class="stat-gradient-6 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up stagger-6">
                <p class="text-sm font-semibold opacity-90 mb-2">🟢 Zoom Tersedia Hari Ini</p>
                <p class="text-4xl font-black">{{ $zoomAvailableToday }} <span class="text-base opacity-70 font-medium">/ {{ $zoomActiveTotal }}</span></p>
            </div>
        </div>
    </div>

</div>
@endsection
