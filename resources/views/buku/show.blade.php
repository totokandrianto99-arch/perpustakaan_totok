@extends('layouts.app')
@section('title',$buku->judul)
@section('page-title','Detail Buku')
@section('breadcrumb','Beranda / Data Buku / Detail')

@section('content')
<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start;">
    {{-- Cover & Info --}}
    <div>
        <div class="card anim" style="overflow:hidden;">
            @if($buku->cover)
                <img src="{{ Storage::url($buku->cover) }}" style="width:100%;aspect-ratio:2/3;object-fit:cover;">
            @else
                <div style="width:100%;aspect-ratio:2/3;background:linear-gradient(135deg,var(--p1),var(--p2));display:flex;align-items:center;justify-content:center;font-size:60px;color:#fff;"><i class="fas fa-book"></i></div>
            @endif
            <div class="card-body">
                <span class="badge badge-info" style="margin-bottom:8px;">{{ $buku->kategori }}</span>
                <h2 style="font-size:16px;font-weight:700;margin-bottom:4px;color:var(--text)">{{ $buku->judul }}</h2>
                <p style="font-size:13px;color:var(--text2)">{{ $buku->pengarang }}</p>
                @if($buku->penerbit)<p style="font-size:12px;color:var(--text3);margin-top:2px">{{ $buku->penerbit }}</p>@endif
                <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:var(--text2)">Tahun</span><strong>{{ $buku->tahun_terbit }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:var(--text2)">Stok</span>
                        <strong style="color:{{ $buku->stok > 0 ? 'var(--s1)' : 'var(--red)' }}">{{ $buku->stok }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:var(--text2)">Total Dipinjam</span><strong>{{ $buku->total_dipinjam }}</strong>
                    </div>
                    @if($buku->lokasi_rak)
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:var(--text2)">Lokasi Rak</span><strong>{{ $buku->lokasi_rak }}</strong>
                    </div>
                    @endif
                </div>
                <div style="margin-top:16px;display:flex;gap:8px;">
                    <a href="{{ route('buku.edit',$buku->id_buku) }}" class="btn btn-primary btn-sm w-full" style="justify-content:center;"><i class="fas fa-pen"></i> Edit</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail & Riwayat --}}
    <div style="display:flex;flex-direction:column;gap:16px;">
        @if($buku->sinopsis)
        <div class="card anim">
            <div class="card-header"><h3><i class="fas fa-align-left" style="color:var(--p2);margin-right:6px"></i>Sinopsis</h3></div>
            <div class="card-body"><p style="font-size:14px;line-height:1.8;color:var(--text2)">{{ $buku->sinopsis }}</p></div>
        </div>
        @endif

        <div class="card anim d1">
            <div class="card-header">
                <h3><i class="fas fa-history" style="color:var(--acc1);margin-right:6px"></i>Riwayat Peminjaman</h3>
                <span style="font-size:12px;color:var(--text2)">{{ $buku->peminjaman->count() }} transaksi</span>
            </div>
            <div class="card-body" style="padding:0;">
                <table>
                    <thead><tr><th>Peminjam</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($buku->peminjaman->sortByDesc('created_at')->take(15) as $p)
                    <tr>
                        <td style="font-size:13px">{{ $p->nama_peminjam }}</td>
                        <td style="font-size:12px;color:var(--text2)">{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                        <td style="font-size:12px;color:var(--text2)">{{ $p->due_date?->format('d M Y') ?? '-' }}</td>
                        <td><span class="badge badge-{{ $p->statusColor() }}">{{ $p->statusLabel() }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="padding:20px;text-align:center;color:var(--text2)">Belum ada riwayat</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
