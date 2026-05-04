@extends('layouts.app')
@section('title', 'Master User')
@section('content')
<div class="space-y-6">
    {{-- Add User Form --}}
    <div class="card animate-fade-in-up">
        <div class="card-header"><h2 class="text-lg font-bold">👥 Tambah User Baru</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                @csrf
                <div><label class="form-label text-xs">Username *</label><input type="text" name="username" class="form-input" required></div>
                <div><label class="form-label text-xs">Password *</label><input type="password" name="password" class="form-input" required></div>
                <div><label class="form-label text-xs">Role *</label>
                    <select name="role" class="form-select"><option value="user">User</option><option value="admin">Admin</option></select></div>
                <div><label class="form-label text-xs">Bagian</label><input type="text" name="bagian" class="form-input"></div>
                <div><button type="submit" class="btn btn-primary w-full">+ Tambah</button></div>
            </form>
        </div>
    </div>

    {{-- User List --}}
    <div class="card animate-fade-in-up">
        <div class="card-header"><h2 class="text-lg font-bold">📋 Daftar User</h2></div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>No</th><th>Username</th><th>Role</th><th>Bagian</th><th>Status</th><th>Permission</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($users as $i => $u)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $i }}</td>
                        <td class="font-semibold">{{ $u->username }}</td>
                        <td><span class="badge {{ $u->role === 'admin' ? 'badge-warning' : 'badge-info' }}">{{ strtoupper($u->role) }}</span></td>
                        <td class="text-xs">{{ $u->bagian ?: '-' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle', $u->id) }}" class="inline">@csrf
                                <button type="submit" class="badge {{ $u->is_active ? 'badge-success' : 'badge-danger' }} cursor-pointer hover:opacity-80">
                                    {{ $u->is_active ? '✅ Aktif' : '❌ Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-xs">
                            @if($u->role === 'admin') <span class="text-amber-600 font-semibold">ALL ACCESS</span>
                            @elseif(count($u->perm_slugs) > 0)
                                @foreach($u->perm_slugs as $slug)
                                    <span class="inline-block bg-sky-50 text-sky-700 rounded px-1.5 py-0.5 text-[10px] font-medium mb-0.5">{{ $slug }}</span>
                                @endforeach
                            @else <span class="text-slate-400">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-1">
                                @if($u->role !== 'admin' || $u->id !== auth()->id())
                                <button type="button" onclick="openPermModal({{ $u->id }}, '{{ $u->username }}', {{ json_encode($u->perm_slugs) }})" class="btn btn-outline btn-sm !px-2" title="Atur permission">🔑</button>
                                @endif
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" onsubmit="return confirm('Yakin hapus user {{ $u->username }}?')">@csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm !px-2">🗑️</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())<div class="card-body border-t border-slate-100">{{ $users->links() }}</div>@endif
    </div>
</div>

{{-- Permission Modal --}}
<div id="permModal" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <h3 class="text-lg font-bold mb-4">🔑 Permission: <span id="permUsername"></span></h3>
        <form method="POST" id="permForm">
            @csrf
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @foreach($availablePages as $slug => $page)
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                    <input type="checkbox" name="permissions[]" value="{{ $slug }}" class="perm-check rounded text-pln-blue">
                    <span class="text-lg">{{ $page['icon'] }}</span>
                    <span class="text-sm font-medium">{{ $page['name'] }}</span>
                </label>
                @endforeach
            </div>
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn btn-primary flex-1">💾 Simpan</button>
                <button type="button" onclick="closePermModal()" class="btn btn-secondary flex-1">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openPermModal(userId, username, perms) {
    document.getElementById('permUsername').textContent = username;
    document.getElementById('permForm').action = '/dashboard-pln-laravel/public/admin/users/' + userId + '/permissions';
    document.querySelectorAll('.perm-check').forEach(cb => { cb.checked = perms.includes(cb.value); });
    const m = document.getElementById('permModal'); m.classList.remove('hidden'); m.classList.add('flex');
}
function closePermModal() { const m = document.getElementById('permModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
document.getElementById('permModal').addEventListener('click', function(e) { if (e.target === this) closePermModal(); });
</script>
@endpush
@endsection
