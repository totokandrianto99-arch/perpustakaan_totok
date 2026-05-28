@extends('layouts.member')
@section('title','Wishlist Saya')
@section('page-title','Wishlist Saya')
@section('breadcrumb','Member / Wishlist')

@push('styles')
<style>
.wl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
}

.wl-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: transform .22s, box-shadow .22s, border-color .22s;
    position: relative;
}
.wl-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.12);
    border-color: rgba(99,102,241,.3);
}

.wl-cover {
    width: 100%; height: 160px;
    background: linear-gradient(135deg, var(--p1), var(--p2));
    display: flex; align-items: center; justify-content: center;
    font-size: 48px; color: rgba(255,255,255,.85);
    overflow: hidden; position: relative;
}
.wl-cover img { width: 100%; height: 100%; object-fit: cover; }

.wl-remove-btn {
    position: absolute; top: 10px; right: 10px;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(239,68,68,.85); backdrop-filter: blur(6px);
    border: none; cursor: pointer; color: #fff; font-size: 11px;
    display: flex; align-items: center; justify-content: center;
    transition: all .18s; opacity: 0;
}
.wl-card:hover .wl-remove-btn { opacity: 1; }
.wl-remove-btn:hover { background: #dc2626; transform: scale(1.1); }

.wl-body { padding: 14px 16px 16px; }
.wl-kategori { margin-bottom: 6px; }
.wl-title { font-size: 14px; font-weight: 700; color: var(--text); line-height: 1.4; margin-bottom: 3px; }
.wl-author { font-size: 12px; color: var(--text2); margin-bottom: 10px; }
.wl-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }

.wl-added {
    font-size: 11px; color: var(--text3);
    display: flex; align-items: center; gap: 4px;
}

/* Empty state */
.wl-empty {
    text-align: center; padding: 64px 24px;
    display: flex; flex-direction: column; align-items: center; gap: 16px;
}
.wl-empty-icon {
    width: 80px; height: 80px; border-radius: 50%;
    background: rgba(99,102,241,.1);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: var(--p1);
}
.wl-empty h3 { font-size: 18px; font-weight: 700; color: var(--text); }
.wl-empty p  { font-size: 14px; color: var(--text2); max-width: 320px; line-height: 1.6; }
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="font-size:13px;color:var(--text2);margin-top:2px;">
            {{ $wishlists->count() }} buku tersimpan
        </p>
    </div>
    <a href="{{ route('member.katalog') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-book"></i> Jelajahi Katalog
    </a>
</div>

@if($wishlists->count() > 0)
<div class="wl-grid">
    @foreach($wishlists as $wl)
    @php $buku = $wl->buku; @endphp
    @if($buku)
    <div class="wl-card animate-fade">
        {{-- Cover --}}
        <div class="wl-cover">
            @if($buku->cover)
                <img src="{{ Storage::url($buku->cover) }}" alt="{{ $buku->judul }}">
            @else
                <i class="fas fa-book"></i>
            @endif

            {{-- Remove button --}}
            <form method="POST" action="{{ route('member.wishlist.remove', $buku->id_buku) }}" style="position:absolute;top:10px;right:10px;">
                @csrf @method('DELETE')
                <button type="submit" class="wl-remove-btn" title="Hapus dari wishlist">
                    <i class="fas fa-times"></i>
                </button>
            </form>
        </div>

        <div class="wl-body">
            <div class="wl-kategori">
                <span class="badge badge-info" style="font-size:10px;">{{ $buku->kategori }}</span>
            </div>
            <div class="wl-title">{{ Str::limit($buku->judul, 50) }}</div>
            <div class="wl-author">{{ $buku->pengarang }}@if($buku->penerbit) · {{ $buku->penerbit }}@endif</div>

            <div class="wl-footer">
                <span class="badge {{ $buku->stok > 0 ? 'badge-success' : 'badge-danger' }}" style="font-size:11px;">
                    {{ $buku->stok > 0 ? 'Tersedia ('.$buku->stok.')' : 'Stok Habis' }}
                </span>
                <div style="display:flex;gap:6px;">
                    <a href="{{ route('member.detail_buku', $buku->id_buku) }}"
                       class="btn btn-secondary btn-sm" style="padding:5px 10px;font-size:11px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($buku->stok > 0 && Auth::user()->canBorrowNow())
                    <a href="{{ route('member.pinjam', $buku->id_buku) }}"
                       class="btn btn-primary btn-sm" style="padding:5px 10px;font-size:11px;">
                        <i class="fas fa-hand-holding-heart"></i> Pinjam
                    </a>
                    @endif
                </div>
            </div>

            <div class="wl-added" style="margin-top:8px;">
                <i class="fas fa-clock"></i>
                Ditambahkan {{ $wl->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>

@else
<div class="wl-empty">
    <div class="wl-empty-icon"><i class="fas fa-heart"></i></div>
    <h3>Wishlist Kosong</h3>
    <p>Belum ada buku yang kamu simpan. Jelajahi katalog dan klik ikon ❤ untuk menyimpan buku favoritmu.</p>
    <a href="{{ route('member.katalog') }}" class="btn btn-primary">
        <i class="fas fa-book"></i> Jelajahi Katalog
    </a>
</div>
@endif

@endsection
