@extends('layouts.member')
@section('title','Katalog Buku')
@section('page-title','Katalog Buku')
@section('breadcrumb','Member / Katalog')

@push('styles')
<style>
/* ── Filter selects ── */
.filter-select {
    appearance: none; -webkit-appearance: none;
    padding: 9px 36px 9px 12px;
    border: 1.5px solid var(--border); border-radius: 10px;
    background: var(--input-bg); color: var(--text);
    font-size: 13px; font-family: 'Inter', sans-serif;
    cursor: pointer; transition: border-color .2s, box-shadow .2s;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center;
    min-width: 150px; position: relative; z-index: 10;
}
.filter-select:focus { outline: none; border-color: var(--p1); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.filter-select option { background: var(--bg, #fff); color: var(--text, #0f172a); }

/* ── Buku card wrapper (relative for wishlist btn + preview) ── */
.buku-card-wrap { position: relative; }

/* ── Wishlist button ── */
.wl-btn {
    position: absolute; top: 10px; right: 10px; z-index: 20;
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--card-bg); border: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
    color: var(--text3); font-size: 13px;
}
.wl-btn:hover { border-color: #f43f5e; color: #f43f5e; transform: scale(1.15); }
.wl-btn.active { background: #f43f5e; border-color: #f43f5e; color: #fff; }
.wl-btn.active:hover { background: #e11d48; }
.wl-btn.spin i { animation: heartPop .3s cubic-bezier(.34,1.56,.64,1); }
@keyframes heartPop { 0%{transform:scale(1)} 50%{transform:scale(1.5)} 100%{transform:scale(1)} }

/* ── Hover preview card ── */
.book-preview {
    position: fixed; z-index: 9000;
    width: 280px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,.25), 0 4px 16px rgba(0,0,0,.15);
    padding: 0;
    overflow: hidden;
    pointer-events: none;
    opacity: 0;
    transform: scale(.94) translateY(6px);
    transition: opacity .2s ease, transform .2s ease;
    backdrop-filter: blur(12px);
}
.book-preview.visible {
    opacity: 1;
    transform: scale(1) translateY(0);
    pointer-events: auto;
}
.preview-cover {
    width: 100%; height: 140px;
    background: linear-gradient(135deg, var(--p1), var(--p2));
    display: flex; align-items: center; justify-content: center;
    font-size: 48px; color: rgba(255,255,255,.9);
    overflow: hidden; position: relative;
}
.preview-cover img { width: 100%; height: 100%; object-fit: cover; }
.preview-cover-badge {
    position: absolute; top: 10px; left: 10px;
    background: rgba(0,0,0,.45); backdrop-filter: blur(6px);
    color: #fff; font-size: 10px; font-weight: 700;
    padding: 3px 8px; border-radius: 999px;
}
.preview-body { padding: 14px 16px 16px; }
.preview-title { font-size: 14px; font-weight: 700; color: var(--text); line-height: 1.4; margin-bottom: 3px; }
.preview-author { font-size: 12px; color: var(--text2); margin-bottom: 10px; }
.preview-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
.preview-meta span {
    font-size: 11px; padding: 3px 8px; border-radius: 999px;
    background: var(--hover-bg); color: var(--text2);
    border: 1px solid var(--border);
}
.preview-sinopsis {
    font-size: 12px; color: var(--text2); line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
    overflow: hidden; margin-bottom: 12px;
}
.preview-actions { display: flex; gap: 8px; }
.preview-actions a, .preview-actions button {
    flex: 1; padding: 8px 10px; border-radius: 9px;
    font-size: 12px; font-weight: 600; text-align: center;
    text-decoration: none; cursor: pointer; border: none;
    transition: all .18s; display: flex; align-items: center; justify-content: center; gap: 5px;
}
.preview-btn-primary { background: var(--p1); color: #fff; }
.preview-btn-primary:hover { background: #4f46e5; }
.preview-btn-secondary { background: var(--hover-bg); color: var(--text); border: 1px solid var(--border); }
.preview-btn-secondary:hover { background: var(--border); }
.preview-stok-ok  { color: #10B981; font-weight: 700; }
.preview-stok-nil { color: #EF4444; font-weight: 700; }

/* ── Search hint ── */
.search-hint {
    font-size: 11px; color: var(--text3);
    display: flex; align-items: center; gap: 4px;
    margin-top: 4px;
}
.search-hint kbd {
    background: var(--hover-bg); border: 1px solid var(--border);
    border-radius: 4px; padding: 1px 5px; font-size: 10px;
    font-family: inherit; color: var(--text2);
}

@media (max-width: 600px) {
    .book-preview { display: none !important; }
}
</style>
@endpush

@section('content')

{{-- FILTER BAR --}}
<div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius);box-shadow:var(--shadow);margin-bottom:16px;padding:14px 20px;overflow:visible;">
    <form method="GET" id="filter-form">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-start;">

            {{-- Search --}}
            <div style="flex:1;min-width:200px;">
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:13px;pointer-events:none;"></i>
                    <input type="text" name="search" id="search-input" class="form-control"
                           style="padding-left:36px;padding-right:36px;"
                           placeholder="Cari judul, pengarang, penerbit…"
                           value="{{ request('search') }}" autocomplete="off">
                    @if(request('search'))
                    <button type="button" id="clear-search"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text3);font-size:13px;padding:2px;">
                        <i class="fas fa-times-circle"></i>
                    </button>
                    @endif
                </div>
                <div class="search-hint"><kbd>Enter</kbd> untuk mencari</div>
            </div>

            {{-- Kategori --}}
            <select name="kategori" class="filter-select" id="sel-kategori">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $k)
                <option value="{{ $k }}" {{ request('kategori') === $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            {{-- Sort --}}
            <select name="sort" class="filter-select" id="sel-sort">
                <option value="latest"  {{ request('sort','latest') === 'latest'  ? 'selected' : '' }}>Terbaru</option>
                <option value="az"      {{ request('sort') === 'az'      ? 'selected' : '' }}>A – Z</option>
                <option value="za"      {{ request('sort') === 'za'      ? 'selected' : '' }}>Z – A</option>
                <option value="populer" {{ request('sort') === 'populer' ? 'selected' : '' }}>Paling Populer</option>
            </select>

            {{-- Tersedia --}}
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text2);cursor:pointer;white-space:nowrap;padding-top:10px;">
                <input type="checkbox" name="tersedia" value="1" id="cb-tersedia"
                       {{ request('tersedia') ? 'checked' : '' }}
                       style="width:15px;height:15px;accent-color:var(--p1);cursor:pointer;">
                Tersedia saja
            </label>

            {{-- Tombol Cari --}}
            <button type="submit" class="btn btn-primary btn-sm" style="align-self:flex-start;margin-top:1px;">
                <i class="fas fa-search"></i> Cari
            </button>

            @if(request()->hasAny(['search','kategori','sort','tersedia']))
            <a href="{{ route('member.katalog') }}" class="btn btn-secondary btn-sm" style="align-self:flex-start;margin-top:1px;">
                <i class="fas fa-times"></i> Reset
            </a>
            @endif
        </div>
    </form>
</div>

{{-- INFO HASIL --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
    <span style="font-size:13px;color:var(--text2)">
        <strong style="color:var(--text)">{{ $bukuList->total() }}</strong> buku ditemukan
        @if(request('search'))" untuk <em>{{ request('search') }}</em>"@endif
    </span>
    <a href="{{ route('member.wishlist') }}" style="font-size:12px;color:var(--p1);text-decoration:none;display:flex;align-items:center;gap:5px;">
        <i class="fas fa-heart"></i> Wishlist saya
        @if($wishlistIds->count())
        <span style="background:var(--p1);color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:999px;">{{ $wishlistIds->count() }}</span>
        @endif
    </a>
</div>

@if($bukuList->count() > 0)
<div class="buku-grid">
    @foreach($bukuList as $buku)
    @php $inWishlist = $wishlistIds->contains($buku->id_buku); @endphp
    <div class="buku-card-wrap">
        {{-- Wishlist button --}}
        <button class="wl-btn {{ $inWishlist ? 'active' : '' }}"
                data-id="{{ $buku->id_buku }}"
                data-url="{{ route('member.wishlist.toggle', $buku->id_buku) }}"
                title="{{ $inWishlist ? 'Hapus dari wishlist' : 'Tambah ke wishlist' }}"
                onclick="toggleWishlist(this, event)">
            <i class="fas fa-heart"></i>
        </button>

        {{-- Book card --}}
        <a href="{{ route('member.detail_buku',$buku->id_buku) }}"
           class="buku-card animate-fade"
           style="text-decoration:none;display:block;"
           data-preview="{{ $buku->id_buku }}"
           data-judul="{{ addslashes($buku->judul) }}"
           data-pengarang="{{ addslashes($buku->pengarang) }}"
           data-penerbit="{{ addslashes($buku->penerbit ?? '') }}"
           data-tahun="{{ $buku->tahun_terbit ?? '' }}"
           data-kategori="{{ $buku->kategori }}"
           data-stok="{{ $buku->stok }}"
           data-dipinjam="{{ $buku->total_dipinjam }}"
           data-sinopsis="{{ addslashes(Str::limit($buku->sinopsis ?? '', 200)) }}"
           data-cover="{{ $buku->cover ? Storage::url($buku->cover) : '' }}"
           data-url-detail="{{ route('member.detail_buku',$buku->id_buku) }}"
           data-url-pinjam="{{ $buku->stok > 0 ? route('member.pinjam',$buku->id_buku) : '' }}">
            <div class="buku-cover">
                @if($buku->cover)
                    <img src="{{ Storage::url($buku->cover) }}" alt="{{ $buku->judul }}">
                @else
                    <i class="fas fa-book"></i>
                @endif
            </div>
            <span class="badge badge-info" style="font-size:10px;margin-bottom:6px;">{{ $buku->kategori }}</span>
            <h4>{{ Str::limit($buku->judul,40) }}</h4>
            <div class="author">{{ $buku->pengarang }}</div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;">
                <span class="badge {{ $buku->stok > 0 ? 'badge-success' : 'badge-danger' }}" style="font-size:11px;">
                    {{ $buku->stok > 0 ? 'Tersedia ('.$buku->stok.')' : 'Habis' }}
                </span>
                @if($buku->stok > 0)
                <span style="font-size:11px;color:var(--p1);font-weight:600">Pinjam →</span>
                @endif
            </div>
        </a>
    </div>
    @endforeach
</div>
<div style="margin-top:16px;">{{ $bukuList->links('vendor.pagination.custom') }}</div>
@else
<div class="empty-state">
    <i class="fas fa-search"></i>
    <p>Tidak ada buku ditemukan. <a href="{{ route('member.katalog') }}" style="color:var(--p1)">Reset filter</a></p>
</div>
@endif

{{-- Hover Preview Card --}}
<div id="book-preview" class="book-preview" role="tooltip">
    <div class="preview-cover" id="prev-cover">
        <span class="preview-cover-badge" id="prev-kategori"></span>
    </div>
    <div class="preview-body">
        <div class="preview-title" id="prev-title"></div>
        <div class="preview-author" id="prev-author"></div>
        <div class="preview-meta" id="prev-meta"></div>
        <div class="preview-sinopsis" id="prev-sinopsis"></div>
        <div class="preview-actions" id="prev-actions"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

// ── 1. Search: Enter only ──────────────────────────────────────────────
document.getElementById('search-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('filter-form').submit(); }
});
// Prevent form submit on input change (only allow explicit submit)
document.getElementById('search-input').addEventListener('keypress', e => {
    if (e.key === 'Enter') return; // handled above
});

const clearBtn = document.getElementById('clear-search');
if (clearBtn) {
    clearBtn.addEventListener('click', () => {
        document.getElementById('search-input').value = '';
        document.getElementById('filter-form').submit();
    });
}

// Dropdowns & checkbox still auto-submit
document.getElementById('sel-kategori').addEventListener('change', () => document.getElementById('filter-form').submit());
document.getElementById('sel-sort').addEventListener('change',     () => document.getElementById('filter-form').submit());
document.getElementById('cb-tersedia').addEventListener('change',  () => document.getElementById('filter-form').submit());

// ── 2. Wishlist toggle ─────────────────────────────────────────────────
function toggleWishlist(btn, e) {
    e.preventDefault(); e.stopPropagation();
    btn.classList.add('spin');
    setTimeout(() => btn.classList.remove('spin'), 350);

    fetch(btn.dataset.url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.toggle('active', data.added);
        btn.title = data.added ? 'Hapus dari wishlist' : 'Tambah ke wishlist';
        showWishlistToast(data.added, btn.closest('.buku-card-wrap').querySelector('h4')?.textContent);
        // Update badge in header
        document.querySelectorAll('.wl-count-badge').forEach(b => {
            b.textContent = data.count;
            b.style.display = data.count > 0 ? '' : 'none';
        });
    });
}

function showWishlistToast(added, title) {
    const id = 'wl-toast-' + Date.now();
    const el = document.createElement('div');
    el.id = id;
    el.className = 'toast ' + (added ? 'toast-success' : 'toast-warning');
    el.innerHTML = `
        <div class="toast-icon"><i class="fas fa-${added ? 'heart' : 'heart-broken'}"></i></div>
        <div class="toast-body">
            <div class="toast-title">${added ? 'Ditambahkan ke Wishlist' : 'Dihapus dari Wishlist'}</div>
            <div class="toast-msg">${title ?? 'Buku'}</div>
        </div>
        <button class="toast-close" onclick="dismissToast('${id}')"><i class="fas fa-times"></i></button>
        <div class="toast-progress"></div>`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => dismissToast(id), 3500);
}

// ── 3. Hover Preview ───────────────────────────────────────────────────
const preview  = document.getElementById('book-preview');
let   hideTimer = null;
let   activeCard = null;

function showPreview(card) {
    clearTimeout(hideTimer);
    if (activeCard === card && preview.classList.contains('visible')) return;
    activeCard = card;

    const d = card.dataset;

    // Cover
    const cover = document.getElementById('prev-cover');
    const existImg = cover.querySelector('img');
    if (existImg) existImg.remove();
    if (d.cover) {
        const img = document.createElement('img');
        img.src = d.cover;
        cover.prepend(img);
        cover.querySelector('i')?.remove();
    } else {
        if (!cover.querySelector('i')) {
            const ic = document.createElement('i');
            ic.className = 'fas fa-book';
            cover.prepend(ic);
        }
    }
    document.getElementById('prev-kategori').textContent = d.kategori;
    document.getElementById('prev-title').textContent    = d.judul;
    document.getElementById('prev-author').textContent   = d.pengarang + (d.penerbit ? ' · ' + d.penerbit : '');

    // Meta
    const meta = document.getElementById('prev-meta');
    meta.innerHTML = '';
    if (d.tahun) meta.innerHTML += `<span><i class="fas fa-calendar-alt" style="margin-right:3px"></i>${d.tahun}</span>`;
    const stokEl = document.createElement('span');
    stokEl.innerHTML = d.stok > 0
        ? `<span class="preview-stok-ok"><i class="fas fa-check-circle" style="margin-right:3px"></i>Tersedia (${d.stok})</span>`
        : `<span class="preview-stok-nil"><i class="fas fa-times-circle" style="margin-right:3px"></i>Habis</span>`;
    meta.appendChild(stokEl);
    if (d.dipinjam > 0) meta.innerHTML += `<span><i class="fas fa-users" style="margin-right:3px"></i>${d.dipinjam}x dipinjam</span>`;

    // Sinopsis
    const sinEl = document.getElementById('prev-sinopsis');
    sinEl.textContent = d.sinopsis || '';
    sinEl.style.display = d.sinopsis ? '' : 'none';

    // Actions
    const acts = document.getElementById('prev-actions');
    acts.innerHTML = `<a href="${d.urlDetail}" class="preview-btn-secondary"><i class="fas fa-eye"></i> Detail</a>`;
    if (d.urlPinjam) {
        acts.innerHTML += `<a href="${d.urlPinjam}" class="preview-btn-primary"><i class="fas fa-hand-holding-heart"></i> Pinjam</a>`;
    }

    positionPreview(card);
    preview.classList.add('visible');
}

function positionPreview(card) {
    const rect = card.getBoundingClientRect();
    const pw   = 280, ph = 380;
    const vw   = window.innerWidth, vh = window.innerHeight;
    const scrollY = window.scrollY;

    let left = rect.right + 12;
    let top  = rect.top + scrollY;

    // Flip left if not enough space on right
    if (left + pw > vw - 12) left = rect.left - pw - 12;
    // Clamp vertically
    if (top + ph > scrollY + vh - 12) top = scrollY + vh - ph - 12;
    if (top < scrollY + 12) top = scrollY + 12;

    preview.style.left = left + 'px';
    preview.style.top  = top  + 'px';
}

function hidePreview() {
    hideTimer = setTimeout(() => {
        preview.classList.remove('visible');
        activeCard = null;
    }, 120);
}

document.querySelectorAll('[data-preview]').forEach(card => {
    let enterTimer;
    card.addEventListener('mouseenter', () => {
        enterTimer = setTimeout(() => showPreview(card), 300);
    });
    card.addEventListener('mouseleave', () => {
        clearTimeout(enterTimer);
        hidePreview();
    });
});

preview.addEventListener('mouseenter', () => clearTimeout(hideTimer));
preview.addEventListener('mouseleave', hidePreview);
</script>
@endpush
