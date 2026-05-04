@extends('layouts.app')
@section('title', 'Booking Zoom Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">📅 Booking Jadwal Zoom Baru</h2>
                <p class="text-xs text-text-muted mt-1">Form untuk memesan jadwal penggunaan Zoom Meeting</p>
            </div>
            <a href="{{ route('booking-zoom.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('booking-zoom.store') }}" id="bookingForm" class="space-y-5">
                @csrf

                {{-- Date Range Picker --}}
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                    <p class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                        🕐 Waktu Booking
                        <span class="text-slate-400 font-normal text-xs">— Pilih tanggal & jam mulai hingga selesai</span>
                        <span class="text-red-500">*</span>
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-4 items-center">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1.5">🟢 Mulai</label>
                            <input type="datetime-local" name="start_datetime" id="start_datetime"
                                   value="{{ old('start_datetime', request('start_datetime')) }}"
                                   class="form-input" required>
                        </div>
                        <div class="hidden md:flex items-center justify-center text-slate-300 text-xl pt-5">→</div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1.5">🔴 Selesai</label>
                            <input type="datetime-local" name="end_datetime" id="end_datetime"
                                   value="{{ old('end_datetime', request('end_datetime')) }}"
                                   class="form-input" required>
                        </div>
                    </div>
                    <div id="daterangeSummary" class="hidden mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700 items-center gap-2 flex-wrap">
                        <span>📌</span>
                        <span id="summaryText"></span>
                        <span id="durationBadge" class="ml-auto bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-bold"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" class="form-select" required>
                            <option value="">-- Pilih Unit --</option>
                            @foreach($unitOptions as $u)
                                <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ strtoupper($u) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Link Zoom <span class="text-red-500">*</span></label>
                        <select name="zoom_link" class="form-select" required>
                            <option value="">-- Pilih Link Zoom --</option>
                            @foreach($zoomOptions as $z)
                                <option value="{{ $z }}" {{ old('zoom_link', request('zoom_link')) === $z ? 'selected' : '' }}>{{ $z }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-input" placeholder="Tambahkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Simpan Booking</button>
                    <a href="{{ route('booking-zoom.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="text-sm font-bold">ℹ️ Informasi</h3></div>
        <div class="card-body text-sm text-slate-600 leading-relaxed">
            <ul class="list-disc list-inside space-y-1.5">
                <li>Pastikan waktu booking tidak bentrok dengan booking lain</li>
                <li>Status akan otomatis diset ke <strong>DIPAKAI</strong> setelah booking</li>
                <li>Status akan otomatis berubah ke <strong>KOSONG</strong> setelah waktu selesai</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
const startInput = document.getElementById('start_datetime');
const endInput = document.getElementById('end_datetime');
const summary = document.getElementById('daterangeSummary');
const summaryTxt = document.getElementById('summaryText');
const durationBg = document.getElementById('durationBadge');

function formatDT(val) {
    if (!val) return '';
    const d = new Date(val);
    const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear()
        + ' ' + String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
}
function formatDuration(sv, ev) {
    const diffMs = new Date(ev) - new Date(sv);
    if (diffMs <= 0) return null;
    const totalMins = Math.floor(diffMs / 60000);
    const hours = Math.floor(totalMins / 60);
    const mins = totalMins % 60;
    if (hours === 0) return mins + ' menit';
    if (mins === 0) return hours + ' jam';
    return hours + ' jam ' + mins + ' menit';
}
function updateSummary() {
    const sv = startInput.value, ev = endInput.value;
    if (sv && ev) {
        const dur = formatDuration(sv, ev);
        if (dur) {
            summaryTxt.textContent = formatDT(sv) + '  →  ' + formatDT(ev);
            durationBg.textContent = '⏱ ' + dur;
            summary.classList.remove('hidden');
            summary.classList.add('flex');
        } else {
            summary.classList.add('hidden');
            summary.classList.remove('flex');
        }
    } else {
        summary.classList.add('hidden');
        summary.classList.remove('flex');
    }
}
startInput.addEventListener('change', function() { if (this.value) endInput.min = this.value; updateSummary(); });
endInput.addEventListener('change', updateSummary);
document.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endpush
@endsection
