@extends('layouts.app')
@section('title','Data Buku')
@section('page-title','Data Buku')
@section('breadcrumb','Beranda / Data Buku')

@section('content')
<div class="card anim">
    <div class="card-header">
        <h3><i class="fas fa-book" style="color:var(--p2);margin-right:6px"></i>Daftar Buku</h3>
        <a href="{{ route('buku.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Buku
        </a>
    </div>
    <div class="card-body" style="padding:16px 22px;">
        {{-- FILTER BAR --}}
        <form method="GET" id="filter-form-buku" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px;">
            <div style="position:relative;">
                <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:12px;"></i>
                <input type="text" name="search" id="search-buku" class="form-control" style="padding:8px 12px 8px 32px;max-width:220px;"
                       placeholder="Cari judul / pengarang..." value="{{ request('search') }}">
            </div>
            <x-custom-select name="kategori" placeholder="Semua Kategori" icon="tag" width="160px"
                :selected="request('kategori','')" :searchable="true"
                :options="array_merge(['' => 'Semua Kategori'], array_combine(\App\Models\Buku::KATEGORI, \App\Models\Buku::KATEGORI))"/>
            <x-custom-select name="sort" placeholder="Urutkan" icon="sort" width="160px"
                :selected="request('sort','latest')"
                :options="['latest'=>'Terbaru','az'=>'A–Z','za'=>'Z–A','populer'=>'Paling Dipinjam','stok'=>'Stok Terbanyak']"/>
            <x-custom-select name="tersedia" placeholder="Semua Status" icon="circle" width="140px"
                :selected="request('tersedia','')"
                :options="[''=>'Semua Status','1'=>'Tersedia']"/>
            @if(request()->hasAny(['search','kategori','sort','tersedia']))
            <a href="{{ route('buku.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cover</th>
                        <th>Judul & Pengarang</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Dipinjam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($bukuList as $i => $buku)
                <tr>
                    <td style="color:var(--text2)">{{ $bukuList->firstItem() + $i }}</td>
                    <td>
                        @if($buku->cover)
                            <img src="{{ Storage::url($buku->cover) }}" style="width:36px;height:48px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:36px;height:48px;background:linear-gradient(135deg,var(--p1),var(--p2));border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;"><i class="fas fa-book"></i></div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $buku->judul }}</div>
                        <div style="font-size:11px;color:var(--text2)">{{ $buku->pengarang }} @if($buku->penerbit)· {{ $buku->penerbit }}@endif</div>
                    </td>
                    <td><span class="badge badge-info">{{ $buku->kategori }}</span></td>
                    <td style="color:var(--text2)">{{ $buku->tahun_terbit }}</td>
                    <td><strong>{{ $buku->stok }}</strong></td>
                    <td style="color:var(--text2)">{{ $buku->total_dipinjam }}</td>
                    <td>
                        <span class="badge {{ $buku->stok > 0 ? 'badge-success' : 'badge-danger' }}">
                            {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('buku.show',$buku->id_buku) }}" class="btn btn-sm btn-secondary btn-icon" title="Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('buku.edit',$buku->id_buku) }}" class="btn btn-sm btn-warning btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <button onclick="openModal('del-{{ $buku->id_buku }}')" class="btn btn-sm btn-danger btn-icon" title="Hapus"><i class="fas fa-trash"></i></button>
                        </div>
                        {{-- Modal Hapus --}}
                        <div class="modal-overlay" id="del-{{ $buku->id_buku }}">
                            <div class="modal" style="max-width:380px;">
                                <div class="modal-header">
                                    <h3>Hapus Buku</h3>
                                    <button class="modal-close" onclick="closeModal('del-{{ $buku->id_buku }}')"><i class="fas fa-times"></i></button>
                                </div>
                                <p style="color:var(--text2);font-size:14px">Hapus buku <strong>{{ $buku->judul }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                <div class="modal-footer">
                                    <button onclick="closeModal('del-{{ $buku->id_buku }}')" class="btn btn-secondary btn-sm">Batal</button>
                                    <form method="POST" action="{{ route('buku.destroy',$buku->id_buku) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9">
                    <div class="empty-state"><i class="fas fa-book-open"></i><p>Belum ada buku. <a href="{{ route('buku.create') }}" style="color:var(--p2)">Tambah sekarang</a></p></div>
                </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $bukuList->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
@push('scripts')
<script>
let searchTimer;
document.getElementById('search-buku').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => document.getElementById('filter-form-buku').submit(), 500);
});
</script>
@endpush
