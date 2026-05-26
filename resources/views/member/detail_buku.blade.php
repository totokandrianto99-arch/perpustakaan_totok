@extends('layouts.member')
@section('title',$buku->judul)
@section('page-title','Detail Buku')
@section('breadcrumb','Member / Katalog / Detail')

@section('content')
<div style="display:grid;grid-template-columns:220px 1fr;gap:20px;align-items:start;">
    {{-- Cover --}}
    <div class="card animate-fade" style="overflow:hidden;">
        @if($buku->cover)
            <img src="{{ Storage::url($buku->cover) }}" style="width:100%;aspect-ratio:2/3;object-fit:cover;">
        @else
            <div style="width:100%;aspect-ratio:2/3;background:linear-gradient(135deg,var(--p1),var(--p2));display:flex;align-items:center;justify-content:center;font-size:56px;color:#fff;"><i class="fas fa-book"></i></div>
        @endif
        <div class="card-body" style="padding:16px;">
            <span class="badge badge-info" style="margin-bottom:8px;display:inline-block;">{{ $buku->kategori }}</span>
            <div style="display:flex;flex-direction:column;gap:6px;font-size:13px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text2)">Stok</span>
                    <strong style="color:{{ $buku->stok > 0 ? 'var(--s1)' : 'var(--red)' }}">{{ $buku->stok }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text2)">Dipinjam</span>
                    <strong>{{ $buku->total_dipinjam }}x</strong>
                </div>
                @if($buku->lokasi_rak)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text2)">Rak</span>
                    <strong>{{ $buku->lokasi_rak }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card animate-fade">
            <div class="card-body">
                <h1 style="font-size:22px;font-weight:700;margin-bottom:6px;color:var(--text)">{{ $buku->judul }}</h1>
                <p style="font-size:15px;color:var(--text2);margin-bottom:4px">{{ $buku->pengarang }}</p>
                @if($buku->penerbit)<p style="font-size:13px;color:var(--text3)">{{ $buku->penerbit }} · {{ $buku->tahun_terbit }}</p>@endif

                @if($buku->sinopsis)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                    <h4 style="font-size:13px;font-weight:600;margin-bottom:8px;color:var(--text2)">SINOPSIS</h4>
                    <p style="font-size:14px;line-height:1.8;color:var(--text2)">{{ $buku->sinopsis }}</p>
                </div>
                @endif

                <div style="margin-top:20px;display:flex;gap:10px;">
                    @if($buku->stok > 0 && !Auth::user()->isSuspended())
                        @if(Auth::user()->canBorrowNow())
                        <a href="{{ route('member.pinjam',$buku->id_buku) }}" class="btn btn-primary">
                            <i class="fas fa-hand-holding-heart"></i> Pinjam Buku Ini
                        </a>
                        @else
                        <button class="btn btn-secondary" disabled title="Anda tidak dapat meminjam saat ini">
                            <i class="fas fa-ban"></i> Tidak Dapat Meminjam
                        </button>
                        @endif
                    @elseif($buku->stok === 0)
                    <button class="btn btn-secondary" disabled><i class="fas fa-times"></i> Stok Habis</button>
                    @endif
                    <a href="{{ route('member.katalog') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
