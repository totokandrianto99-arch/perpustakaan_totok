@extends('layouts.member')
@section('title','Pinjam Buku')
@section('page-title','Pinjam Buku')
@section('breadcrumb','Member / Katalog / Pinjam')

@section('content')
<div style="display:grid;grid-template-columns:200px 1fr;gap:20px;align-items:start;max-width:700px;">
    {{-- Cover --}}
    <div class="card animate-fade" style="overflow:hidden;">
        @if($buku->cover)
            <img src="{{ Storage::url($buku->cover) }}" style="width:100%;aspect-ratio:2/3;object-fit:cover;">
        @else
            <div style="width:100%;aspect-ratio:2/3;background:linear-gradient(135deg,var(--p1),var(--p2));display:flex;align-items:center;justify-content:center;font-size:48px;color:#fff;"><i class="fas fa-book"></i></div>
        @endif
    </div>

    {{-- Form --}}
    <div class="card animate-fade">
        <div class="card-header">
            <h3><i class="fas fa-hand-holding-heart" style="color:var(--p1);margin-right:6px"></i>Form Peminjaman</h3>
        </div>
        <div class="card-body">
            <div style="margin-bottom:16px;padding:12px;background:var(--hover-bg);border-radius:10px;border:1px solid var(--border);">
                <div style="font-weight:600;font-size:14px;color:var(--text)">{{ $buku->judul }}</div>
                <div style="font-size:12px;color:var(--text2);margin-top:2px">{{ $buku->pengarang }}</div>
                <div style="font-size:12px;color:var(--text2);margin-top:4px">
                    <span class="badge badge-success">Stok: {{ $buku->stok }}</span>
                    <span class="badge badge-info" style="margin-left:4px">{{ $buku->kategori }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('member.pinjam.store',$buku->id_buku) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Jumlah Pinjam <span style="color:var(--red)">*</span></label>
                    <input type="number" name="jumlah_pinjam" class="form-control {{ $errors->has('jumlah_pinjam') ? 'is-invalid' : '' }}"
                           min="1" max="{{ $buku->stok }}" value="{{ old('jumlah_pinjam',1) }}">
                    @error('jumlah_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi Pinjam <span style="color:var(--red)">*</span></label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                        @foreach([3,7,14,30] as $d)
                        @if($d <= $maxDurasi)
                        <button type="button" class="btn btn-sm durasi-btn {{ old('durasi_pinjam',$defaultDurasi) == $d ? 'btn-primary' : 'btn-secondary' }}" data-val="{{ $d }}">{{ $d }} hari</button>
                        @endif
                        @endforeach
                    </div>
                    <input type="hidden" name="durasi_pinjam" id="durasi-hidden" value="{{ old('durasi_pinjam',$defaultDurasi) }}">
                    @error('durasi_pinjam')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                    <div style="font-size:11px;color:var(--text2);margin-top:4px">Jatuh tempo: <strong id="due-preview">-</strong></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan <span style="font-size:11px;color:var(--text2)">(opsional)</span></label>
                    <textarea name="catatan_user" class="form-control" rows="2" placeholder="Catatan untuk petugas...">{{ old('catatan_user') }}</textarea>
                </div>

                <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:12px;margin-bottom:16px;font-size:13px;color:var(--text2);">
                    <i class="fas fa-info-circle" style="color:var(--acc)"></i>
                    Request peminjaman akan diproses oleh petugas. Anda akan mendapat notifikasi setelah disetujui.
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Request</button>
                    <a href="{{ route('member.detail_buku',$buku->id_buku) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const maxDurasi = {{ $maxDurasi }};
const durasiBtns = document.querySelectorAll('.durasi-btn');
const durasiHidden = document.getElementById('durasi-hidden');
const duePreview = document.getElementById('due-preview');

function updateDue(days) {
    const d = new Date();
    d.setDate(d.getDate() + parseInt(days));
    duePreview.textContent = d.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
}

durasiBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        durasiBtns.forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-secondary'); });
        this.classList.remove('btn-secondary');
        this.classList.add('btn-primary');
        durasiHidden.value = this.dataset.val;
        updateDue(this.dataset.val);
    });
});

// Init
updateDue(durasiHidden.value || {{ $defaultDurasi }});
</script>
@endpush
