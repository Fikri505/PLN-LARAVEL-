    @extends('layouts.app')
@section('title', 'Edit Perangkat Aplikasi')
@section('content')
<div class="w-full">
    <div class="card animate-fade-in-up">
        <div class="card-header flex items-center justify-between">
            <h2 class="text-lg font-bold">✏️ Edit — {{ $item->jenis_perangkat }}</h2>
            <a href="{{ route('perangkat-aplikasi.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('perangkat-aplikasi.update', $item->id) }}" class="space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="form-label">Jenis Perangkat *</label>
                        <select name="jenis_perangkat" class="form-select" required><option value="">-- Pilih --</option>
                            @foreach($jenisOptions as $o)<option value="{{ $o }}" {{ old('jenis_perangkat', $item->jenis_perangkat) === $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach
                        </select></div>
                    <div><label class="form-label">Brand</label>
                        <select name="brand" class="form-select"><option value="">-- Pilih --</option>
                            @foreach($brandOptions as $o)<option value="{{ $o }}" {{ old('brand', $item->brand) === $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach
                        </select></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">URL</label><input type="text" name="url" value="{{ old('url', $item->url) }}" class="form-input"></div>
                    <div><label class="form-label">IP</label><input type="text" name="ip" value="{{ old('ip', $item->ip) }}" class="form-input"></div>
                    <div><label class="form-label">Type</label><input type="text" name="type" value="{{ old('type', $item->type) }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">Server</label><input type="text" name="server" value="{{ old('server', $item->server) }}" class="form-input"></div>
                    <div><label class="form-label">OS</label><input type="text" name="os" value="{{ old('os', $item->os) }}" class="form-input"></div>
                    <div><label class="form-label">Pemilik Aset</label><input type="text" name="pemilik_aset" value="{{ old('pemilik_aset', $item->pemilik_aset) }}" class="form-input"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><label class="form-label">Lokasi</label>
                        <select name="lokasi" class="form-select"><option value="">--</option>@foreach($lokasiOptions as $o)<option value="{{ $o }}" {{ old('lokasi', $item->lokasi) === $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach</select></div>
                    <div><label class="form-label">Bidang</label>
                        <select name="bidang" class="form-select"><option value="">--</option>@foreach($bidangOptions as $o)<option value="{{ $o }}" {{ old('bidang', $item->bidang) === $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach</select></div>
                    <div><label class="form-label">MSB</label>
                        <select name="msb_sub_bidang" class="form-select"><option value="">--</option>@foreach($msbOptions as $o)<option value="{{ $o }}" {{ old('msb_sub_bidang', $item->msb_sub_bidang) === $o ? 'selected' : '' }}>{{ $o }}</option>@endforeach</select></div>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                    <p class="text-sm font-bold text-slate-700 mb-3">🔒 Patch Status</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['firmware_patch'=>'Firmware','database_patch'=>'Database','network_device_patch'=>'Network','application_patch'=>'Application','os_patch'=>'OS','library_dependency_patch'=>'Library'] as $field => $label)
                        <div><label class="form-label text-xs">{{ $label }}</label>
                            <select name="{{ $field }}" class="form-select text-xs">
                                @foreach(['⌛'=>'⌛ Pending','✅'=>'✅ Done','❌'=>'❌ N/A'] as $v => $l)
                                    <option value="{{ $v }}" {{ old($field, $item->$field) === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select></div>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-3"><button type="submit" class="btn btn-primary">💾 Update</button><a href="{{ route('perangkat-aplikasi.index') }}" class="btn btn-secondary">Batal</a></div>
            </form>
        </div>
    </div>
</div>
@endsection
