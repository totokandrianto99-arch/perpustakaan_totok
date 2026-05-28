@extends('layouts.app')
@section('title','Manajemen User')
@section('page-title','Manajemen User')
@section('breadcrumb','Beranda / Manajemen User')

@push('styles')
<style>
/* ── Responsive user table ── */
.user-table-wrap { overflow-x: auto; }

/* Card view untuk mobile */
.user-cards { display: none; flex-direction: column; gap: 12px; }

.user-card-item {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.user-card-top { display: flex; align-items: center; gap: 12px; }
.user-card-avatar {
    width: 42px; height: 42px;
    background: linear-gradient(135deg, var(--p1), var(--p2));
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-card-info { flex: 1; min-width: 0; }
.user-card-name { font-weight: 600; font-size: 14px; color: var(--text); }
.user-card-sub  { font-size: 12px; color: var(--text2); margin-top: 2px; }
.user-card-badges { display: flex; flex-wrap: wrap; gap: 6px; }
.user-card-pw {
    display: flex; align-items: center; gap: 6px;
    background: var(--hover-bg); border-radius: 8px;
    padding: 7px 10px; font-size: 12px;
}
.user-card-pw span { font-family: monospace; letter-spacing: 1px; color: var(--text2); }
.user-card-actions { display: flex; gap: 6px; flex-wrap: wrap; }

@media (max-width: 900px) {
    .user-table-wrap table { font-size: 12px; }
    thead th, tbody td { padding: 9px 10px; }
}

@media (max-width: 640px) {
    .user-table-wrap { display: none; }
    .user-cards { display: flex; }
}
</style>
@endpush

@section('content')
<div class="card anim">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:var(--p2);margin-right:6px"></i>Daftar Member</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah User</a>
    </div>
    <div class="card-body" style="padding:16px 22px;">

        {{-- Filter --}}
        <form method="GET" id="filter-form-user" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px;">
            <div style="position:relative;flex:1;min-width:180px;">
                <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:12px;"></i>
                <input type="text" name="search" id="search-user" class="form-control" style="padding:8px 12px 8px 32px;"
                       placeholder="Cari nama / username..." value="{{ request('search') }}">
            </div>
            <x-custom-select name="status" placeholder="Semua Status" icon="circle" width="150px"
                :selected="request('status','')"
                :options="[
                    ['value'=>'','label'=>'Semua Status'],
                    ['value'=>'active','label'=>'Aktif','dot'=>'#10B981'],
                    ['value'=>'suspended','label'=>'Suspended','dot'=>'#EF4444'],
                ]"/>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>

        {{-- DESKTOP TABLE --}}
        <div class="user-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Status</th>
                        <th>Poin</th>
                        <th>Hak Pinjam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $i => $u)
                <tr>
                    <td style="color:var(--text2);font-size:12px">{{ $users->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--p1),var(--p2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($u->nama_lengkap,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:13px">{{ $u->nama_lengkap }}</div>
                                <div style="font-size:11px;color:var(--text2)">{{ $u->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text2);font-size:13px">{{ $u->username }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:5px;">
                            <span id="pw-{{ $u->id_user }}" style="font-size:12px;font-family:monospace;letter-spacing:1px;">••••••••</span>
                            <button type="button" onclick="togglePw('{{ $u->id_user }}','{{ addslashes($u->plain_password ?? '(tidak tersimpan)') }}')"
                                    style="background:none;border:none;cursor:pointer;color:var(--text3);font-size:11px;padding:2px 4px;" title="Lihat password">
                                <i class="fas fa-eye" id="pw-eye-{{ $u->id_user }}"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $u->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                            {{ $u->status === 'active' ? 'Aktif' : 'Suspended' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $u->violation_points >= 5 ? 'badge-danger' : ($u->violation_points >= 3 ? 'badge-warning' : 'badge-secondary') }}">
                            {{ $u->violation_points }} poin
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $u->can_borrow ? 'badge-success' : 'badge-danger' }}">
                            {{ $u->can_borrow ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:wrap;">
                            <a href="{{ route('users.show',$u->id_user) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('users.edit',$u->id_user) }}" class="btn btn-sm btn-warning btn-icon" title="Edit"><i class="fas fa-edit"></i></a>

                            <form method="POST" action="{{ route('users.toggleBorrow',$u->id_user) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $u->can_borrow ? 'btn-secondary' : 'btn-success' }} btn-icon"
                                        title="{{ $u->can_borrow ? 'Larang Pinjam' : 'Izinkan Pinjam' }}">
                                    <i class="fas fa-{{ $u->can_borrow ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>

                            @if($u->status === 'active')
                            <button onclick="openModal('suspend-{{ $u->id_user }}')" class="btn btn-sm btn-danger btn-icon" title="Suspend"><i class="fas fa-user-slash"></i></button>
                            @else
                            <form method="POST" action="{{ route('users.activate',$u->id_user) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success btn-icon" title="Aktifkan"><i class="fas fa-user-check"></i></button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('users.destroy',$u->id_user) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus user {{ addslashes($u->nama_lengkap) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>

                        {{-- Modal Suspend --}}
                        <div class="modal-overlay" id="suspend-{{ $u->id_user }}">
                            <div class="modal">
                                <div class="modal-header">
                                    <h3>Suspend Akun</h3>
                                    <button class="modal-close" onclick="closeModal('suspend-{{ $u->id_user }}')"><i class="fas fa-times"></i></button>
                                </div>
                                <p style="font-size:13px;color:var(--text2);margin-bottom:14px">Suspend akun <strong>{{ $u->nama_lengkap }}</strong>?</p>
                                <form method="POST" action="{{ route('users.suspend',$u->id_user) }}">
                                    @csrf @method('PATCH')
                                    <div class="form-group">
                                        <label class="form-label">Alasan <span style="color:var(--red)">*</span></label>
                                        <textarea name="suspend_reason" class="form-control" rows="3" required placeholder="Tuliskan alasan suspend..."></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="closeModal('suspend-{{ $u->id_user }}')" class="btn btn-secondary btn-sm">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-slash"></i> Suspend</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-users"></i><p>Belum ada member</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS --}}
        <div class="user-cards">
            @forelse($users as $u)
            <div class="user-card-item">
                <div class="user-card-top">
                    <div class="user-card-avatar">{{ strtoupper(substr($u->nama_lengkap,0,1)) }}</div>
                    <div class="user-card-info">
                        <div class="user-card-name">{{ $u->nama_lengkap }}</div>
                        <div class="user-card-sub">@{{ $u->username }} · {{ $u->email ?? 'Tidak ada email' }}</div>
                    </div>
                </div>
                <div class="user-card-badges">
                    <span class="badge {{ $u->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ $u->status === 'active' ? 'Aktif' : 'Suspended' }}</span>
                    <span class="badge {{ $u->violation_points >= 3 ? 'badge-warning' : 'badge-secondary' }}">{{ $u->violation_points }} poin</span>
                    <span class="badge {{ $u->can_borrow ? 'badge-success' : 'badge-danger' }}">{{ $u->can_borrow ? 'Boleh Pinjam' : 'Dilarang' }}</span>
                </div>
                <div class="user-card-pw">
                    <i class="fas fa-key" style="color:var(--text3);font-size:11px;"></i>
                    <span id="mpw-{{ $u->id_user }}">••••••••</span>
                    <button type="button" onclick="togglePw('mpw-{{ $u->id_user }}','{{ addslashes($u->plain_password ?? '(tidak tersimpan)') }}',true)"
                            style="background:none;border:none;cursor:pointer;color:var(--text3);font-size:11px;margin-left:auto;" title="Lihat password">
                        <i class="fas fa-eye" id="mpw-eye-{{ $u->id_user }}"></i>
                    </button>
                </div>
                <div class="user-card-actions">
                    <a href="{{ route('users.show',$u->id_user) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                    <a href="{{ route('users.edit',$u->id_user) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                    @if($u->status === 'active')
                    <button onclick="openModal('msuspend-{{ $u->id_user }}')" class="btn btn-sm btn-danger"><i class="fas fa-user-slash"></i></button>
                    @else
                    <form method="POST" action="{{ route('users.activate',$u->id_user) }}" style="display:inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-user-check"></i></button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('users.destroy',$u->id_user) }}" style="display:inline"
                          onsubmit="return confirm('Hapus user {{ addslashes($u->nama_lengkap) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </div>

                {{-- Modal Suspend Mobile --}}
                <div class="modal-overlay" id="msuspend-{{ $u->id_user }}">
                    <div class="modal">
                        <div class="modal-header">
                            <h3>Suspend Akun</h3>
                            <button class="modal-close" onclick="closeModal('msuspend-{{ $u->id_user }}')"><i class="fas fa-times"></i></button>
                        </div>
                        <p style="font-size:13px;color:var(--text2);margin-bottom:14px">Suspend akun <strong>{{ $u->nama_lengkap }}</strong>?</p>
                        <form method="POST" action="{{ route('users.suspend',$u->id_user) }}">
                            @csrf @method('PATCH')
                            <div class="form-group">
                                <label class="form-label">Alasan <span style="color:var(--red)">*</span></label>
                                <textarea name="suspend_reason" class="form-control" rows="3" required placeholder="Tuliskan alasan..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="closeModal('msuspend-{{ $u->id_user }}')" class="btn btn-secondary btn-sm">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-slash"></i> Suspend</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state"><i class="fas fa-users"></i><p>Belum ada member</p></div>
            @endforelse
        </div>

        {{ $users->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
let searchTimerU;
document.getElementById('search-user').addEventListener('input', function() {
    clearTimeout(searchTimerU);
    searchTimerU = setTimeout(() => document.getElementById('filter-form-user').submit(), 500);
});

const pwVisible = {};
function togglePw(id, plain, direct = false) {
    const elId = direct ? id : 'pw-' + id;
    const eyeId = direct ? id.replace('mpw-','mpw-eye-') : 'pw-eye-' + id;
    const el  = document.getElementById(elId);
    const eye = document.getElementById(eyeId);
    const key = elId;
    pwVisible[key] = !pwVisible[key];
    el.textContent = pwVisible[key] ? plain : '••••••••';
    eye.className  = pwVisible[key] ? 'fas fa-eye-slash' : 'fas fa-eye';
}
</script>
@endpush
