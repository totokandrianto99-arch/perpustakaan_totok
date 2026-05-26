@extends('layouts.member')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb','Member / Dashboard')

@section('content')
{{-- Warning jika ada overdue --}}
@if($stats['overdue'] > 0)
<div class="alert alert-danger" style="margin-bottom:16px;">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>Anda memiliki {{ $stats['overdue'] }} peminjaman terlambat!</strong>
        Segera kembalikan buku untuk menghindari sanksi lebih lanjut.
    </div>
</div>
@endif

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card animate-fade">
        <div class="stat-icon si-purple"><i class="fas fa-book-open"></i></div>
        <div><div class="stat-value">{{ $stats['aktif'] }}</div><div class="stat-label">Sedang Dipinjam</div></div>
    </div>
    <div class="stat-card animate-fade" style="animation-delay:.05s">
        <div class="stat-icon si-orange"><i class="fas fa-clock"></i></div>
        <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Menunggu Approval</div></div>
    </div>
    <div class="stat-card animate-fade" style="animation-delay:.1s">
        <div class="stat-icon si-red"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="stat-value">{{ $stats['overdue'] }}</div><div class="stat-label">Terlambat</div></div>
    </div>
    <div class="stat-card animate-fade" style="animation-delay:.15s">
        <div class="stat-icon si-green"><i class="fas fa-history"></i></div>
        <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Pinjam</div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:16px;align-items:start;position:relative;z-index:0;">
    {{-- Peminjaman Aktif --}}
    <div class="card animate-fade">
        <div class="card-header">
            <h3><i class="fas fa-book-open" style="color:var(--p1);margin-right:6px"></i>Peminjaman Aktif</h3>
            <a href="{{ route('member.katalog') }}" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari Buku</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($peminjamanAktif as $p)
            <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);">
                @if($p->buku?->cover)
                    <img src="{{ Storage::url($p->buku->cover) }}" style="width:40px;height:54px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                @else
                    <div style="width:40px;height:54px;background:linear-gradient(135deg,var(--p1),var(--p2));border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;flex-shrink:0;"><i class="fas fa-book"></i></div>
                @endif
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->buku?->judul }}</div>
                    <div style="font-size:12px;color:var(--text2);margin-top:2px">{{ $p->buku?->pengarang }}</div>
                    @if($p->due_date)
                    <div style="font-size:12px;margin-top:4px;color:{{ $p->isOverdue() ? 'var(--red)' : 'var(--text2)' }}">
                        <i class="fas fa-calendar-alt"></i>
                        Jatuh tempo: {{ $p->due_date->format('d M Y') }}
                        @if($p->late_days > 0)<strong style="color:var(--red)"> (Terlambat {{ $p->late_days }} hari)</strong>@endif
                    </div>
                    @endif
                </div>
                <div style="flex-shrink:0;text-align:right;">
                    <span class="badge badge-{{ $p->statusColor() }}" style="display:block;margin-bottom:6px;">{{ $p->statusLabel() }}</span>
                    @if(in_array($p->status,['borrowed','overdue']))
                    <form method="POST" action="{{ route('member.kembalikan',$p->id_pinjam) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Kembalikan buku ini?')">
                            <i class="fas fa-undo"></i> Kembalikan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p>Tidak ada peminjaman aktif. <a href="{{ route('member.katalog') }}" style="color:var(--p1)">Pinjam buku sekarang</a></p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Notifikasi --}}
    <div class="card animate-fade" style="animation-delay:.1s;position:sticky;top:80px;">
        <div class="card-header" style="position:relative;z-index:2;">
            <h3><i class="fas fa-bell" style="color:var(--acc);margin-right:6px"></i>Notifikasi</h3>
            @if($notifikasi->where('dibaca',false)->count() > 0)
            <button onclick="markAllRead()" class="btn btn-sm btn-secondary" style="font-size:11px;">Tandai Dibaca</button>
            @endif
        </div>
        <div style="max-height:380px;overflow-y:auto;">
            @forelse($notifikasi as $n)
            <div style="padding:12px 16px;border-bottom:1px solid var(--border);{{ !$n->dibaca ? 'background:rgba(99,102,241,.05)' : '' }}">
                <div style="display:flex;gap:10px;align-items:flex-start;">
                    <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;
                        background:{{ $n->tipe === 'danger' ? 'rgba(239,68,68,.15)' : ($n->tipe === 'warning' ? 'rgba(245,158,11,.15)' : ($n->tipe === 'success' ? 'rgba(16,185,129,.15)' : 'rgba(59,130,246,.15)')) }};
                        color:{{ $n->tipe === 'danger' ? 'var(--red)' : ($n->tipe === 'warning' ? 'var(--acc)' : ($n->tipe === 'success' ? 'var(--s1)' : 'var(--p2)')) }}">
                        <i class="fas fa-{{ $n->tipe === 'danger' ? 'exclamation-circle' : ($n->tipe === 'warning' ? 'exclamation-triangle' : ($n->tipe === 'success' ? 'check-circle' : 'info-circle')) }}"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:{{ !$n->dibaca ? '600' : '400' }}">{{ $n->judul }}</div>
                        <div style="font-size:12px;color:var(--text2);margin-top:2px;line-height:1.5">{{ $n->pesan }}</div>
                        <div style="font-size:11px;color:var(--text3);margin-top:4px">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:var(--text2);font-size:13px;">
                <i class="fas fa-bell-slash" style="font-size:24px;opacity:.3;display:block;margin-bottom:8px;"></i>
                Tidak ada notifikasi
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markAllRead() {
    fetch('{{ route("member.notif.read") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(() => location.reload());
}
</script>
@endpush
