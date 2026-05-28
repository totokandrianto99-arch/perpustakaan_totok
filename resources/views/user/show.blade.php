@extends('layouts.app')
@section('title','Detail User')
@section('page-title','Detail User')
@section('breadcrumb','Beranda / Manajemen User / Detail')

@section('content')
<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start;">
    {{-- Profile Card --}}
    <div class="card anim">
        <div class="card-body" style="text-align:center;padding:28px 22px;">
            <div style="width:72px;height:72px;background:linear-gradient(135deg,var(--p1),var(--p2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;margin:0 auto 14px;">
                {{ strtoupper(substr($user->nama_lengkap,0,1)) }}
            </div>
            <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">{{ $user->nama_lengkap }}</h3>
            <p style="font-size:13px;color:var(--text2)">@{{ $user->username }}</p>
            <p style="font-size:12px;color:var(--text3);margin-top:2px">{{ $user->email ?? 'Tidak ada email' }}</p>

            <div style="margin:16px 0;display:flex;flex-direction:column;gap:8px;text-align:left;">
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:var(--text2)">Status</span>
                    <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($user->status) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:var(--text2)">Poin Pelanggaran</span>
                    <span class="badge {{ $user->violation_points >= 5 ? 'badge-danger' : ($user->violation_points >= 3 ? 'badge-warning' : 'badge-secondary') }}">{{ $user->violation_points }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:var(--text2)">Hak Pinjam</span>
                    <span class="badge {{ $user->can_borrow ? 'badge-success' : 'badge-danger' }}">{{ $user->can_borrow ? 'Ya' : 'Tidak' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;">
                    <span style="color:var(--text2)">Perlu Approval</span>
                    <span class="badge {{ $user->need_admin_approval ? 'badge-warning' : 'badge-secondary' }}">{{ $user->need_admin_approval ? 'Ya' : 'Tidak' }}</span>
                </div>
            </div>

            @if($user->isSuspended() && $user->suspend_reason)
            <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:10px;font-size:12px;color:var(--red);text-align:left;margin-bottom:14px;">
                <strong>Alasan Suspend:</strong><br>{{ $user->suspend_reason }}
            </div>
            @endif

            {{-- Aksi --}}
            <div style="display:flex;flex-direction:column;gap:8px;">
                <form method="POST" action="{{ route('users.toggleBorrow',$user->id_user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm {{ $user->can_borrow ? 'btn-warning' : 'btn-success' }} w-full" style="justify-content:center;">
                        <i class="fas fa-{{ $user->can_borrow ? 'ban' : 'check' }}"></i>
                        {{ $user->can_borrow ? 'Larang Pinjam' : 'Izinkan Pinjam' }}
                    </button>
                </form>

                @if($user->status === 'active')
                <button onclick="openModal('suspend-user')" class="btn btn-sm btn-danger w-full" style="justify-content:center;">
                    <i class="fas fa-user-slash"></i> Suspend Akun
                </button>
                @else
                <form method="POST" action="{{ route('users.activate',$user->id_user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success w-full" style="justify-content:center;">
                        <i class="fas fa-user-check"></i> Aktifkan Akun
                    </button>
                </form>
                @endif

                @if($user->violation_points > 0)
                <form method="POST" action="{{ route('users.resetViolation',$user->id_user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-secondary w-full" style="justify-content:center;">
                        <i class="fas fa-redo"></i> Reset Poin
                    </button>
                </form>
                @endif

                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary w-full" style="justify-content:center;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Stats & Riwayat --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
            <div class="stat-card anim"><div class="stat-icon si-blue"><i class="fas fa-book"></i></div><div><div class="stat-val">{{ $stats['total'] }}</div><div class="stat-lbl">Total Pinjam</div></div></div>
            <div class="stat-card anim d1"><div class="stat-icon si-orange"><i class="fas fa-book-open"></i></div><div><div class="stat-val">{{ $stats['aktif'] }}</div><div class="stat-lbl">Aktif</div></div></div>
            <div class="stat-card anim d2"><div class="stat-icon si-red"><i class="fas fa-exclamation"></i></div><div><div class="stat-val">{{ $stats['overdue'] }}</div><div class="stat-lbl">Terlambat</div></div></div>
            <div class="stat-card anim d3"><div class="stat-icon si-green"><i class="fas fa-check"></i></div><div><div class="stat-val">{{ $stats['returned'] }}</div><div class="stat-lbl">Dikembalikan</div></div></div>
        </div>

        <div class="card anim d2">
            <div class="card-header"><h3><i class="fas fa-history" style="color:var(--acc1);margin-right:6px"></i>Riwayat Peminjaman</h3></div>
            <div class="card-body" style="padding:0;">
                <table>
                    <thead><tr><th>Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Terlambat</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($user->peminjaman->sortByDesc('created_at') as $p)
                    <tr>
                        <td style="font-size:13px;font-weight:500">{{ Str::limit($p->buku?->judul,30) }}</td>
                        <td style="font-size:12px;color:var(--text2)">{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                        <td style="font-size:12px;color:var(--text2)">{{ $p->due_date?->format('d M Y') ?? '-' }}</td>
                        <td>@if($p->late_days > 0)<span class="badge badge-danger">{{ $p->late_days }} hari</span>@else<span style="color:var(--text3);font-size:12px">-</span>@endif</td>
                        <td><span class="badge badge-{{ $p->statusColor() }}">{{ $p->statusLabel() }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:20px;text-align:center;color:var(--text2)">Belum ada riwayat</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Suspend --}}
<div class="modal-overlay" id="suspend-user">
    <div class="modal">
        <div class="modal-header"><h3>Suspend Akun</h3><button class="modal-close" onclick="closeModal('suspend-user')"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="{{ route('users.suspend',$user->id_user) }}">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Alasan Suspend <span style="color:var(--red)">*</span></label>
                <textarea name="suspend_reason" class="form-control" rows="3" required placeholder="Tuliskan alasan..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('suspend-user')" class="btn btn-secondary btn-sm">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-slash"></i> Suspend</button>
            </div>
        </form>
    </div>
</div>
@endsection
