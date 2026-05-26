@extends('layouts.app')
@section('title','Input Peminjaman')
@section('page-title','Input Peminjaman')
@section('breadcrumb','Beranda / Peminjaman / Input')

@section('content')
<div class="card anim" style="max-width:640px;">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle" style="color:var(--p2);margin-right:8px"></i>Form Peminjaman Buku</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('peminjaman.store') }}">
            @csrf

            {{-- Peminjam --}}
            <div class="form-group">
                <label class="form-label">Peminjam <span style="color:var(--red)">*</span></label>
                <select name="id_user" id="userSelect" class="form-control {{ $errors->has('id_user') ? 'is-invalid' : '' }}" onchange="checkUserLimit(this)">
                    <option value="">-- Pilih Member --</option>
                    @foreach($userList as $u)
                    <option value="{{ $u->id_user }}"
                        data-aktif="{{ $pinjamAktifPerUser[$u->id_user] ?? 0 }}"
                        data-max="{{ $maxPinjamAktif }}"
                        {{ old('id_user') == $u->id_user ? 'selected' : '' }}>
                        {{ $u->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
                @error('id_user')<div class="invalid-feedback">{{ $message }}</div>@enderror

                {{-- Warning batas pinjam --}}
                <div id="user-limit-warning" style="display:none;margin-top:8px;padding:10px 14px;border-radius:10px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);font-size:13px;color:#ef4444;">
                    <i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i>
                    <span id="user-limit-text"></span>
                </div>
                <div id="user-ok-info" style="display:none;margin-top:8px;padding:10px 14px;border-radius:10px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);font-size:13px;color:#10b981;">
                    <i class="fas fa-check-circle" style="margin-right:6px;"></i>
                    <span id="user-ok-text"></span>
                </div>
            </div>

            {{-- Buku --}}
            <div class="form-group">
                <label class="form-label">Buku <span style="color:var(--red)">*</span></label>
                <select name="id_buku" id="bukuSelect" class="form-control {{ $errors->has('id_buku') ? 'is-invalid' : '' }}" onchange="updateStok()">
                    <option value="">-- Pilih Buku --</option>
                    @foreach($bukuList as $b)
                    <option value="{{ $b->id_buku }}" data-stok="{{ $b->stok }}" {{ old('id_buku') == $b->id_buku ? 'selected' : '' }}>
                        {{ $b->judul }}
                    </option>
                    @endforeach
                </select>
                @error('id_buku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="stok-info" style="font-size:12px;color:var(--text2);margin-top:4px;"></div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Jumlah Pinjam <span style="color:var(--red)">*</span></label>
                    <input type="number" name="jumlah_pinjam" id="jumlahInput" class="form-control {{ $errors->has('jumlah_pinjam') ? 'is-invalid' : '' }}"
                           placeholder="1" min="1" value="{{ old('jumlah_pinjam',1) }}">
                    @error('jumlah_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Durasi Pinjam (hari) <span style="color:var(--red)">*</span></label>
                    <input type="number" name="durasi_pinjam" class="form-control {{ $errors->has('durasi_pinjam') ? 'is-invalid' : '' }}"
                           placeholder="{{ $defaultDurasi }}" min="1" max="{{ $maxDurasi }}" value="{{ old('durasi_pinjam',$defaultDurasi) }}">
                    @error('durasi_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div style="font-size:11px;color:var(--text2);margin-top:3px">Maks {{ $maxDurasi }} hari</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan <span style="font-size:11px;color:var(--text2)">(opsional)</span></label>
                <textarea name="catatan_user" class="form-control" rows="2" placeholder="Catatan tambahan...">{{ old('catatan_user') }}</textarea>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const maxPinjam = {{ $maxPinjamAktif }};

function checkUserLimit(sel) {
    const opt     = sel.options[sel.selectedIndex];
    const aktif   = parseInt(opt.dataset.aktif ?? 0);
    const warning = document.getElementById('user-limit-warning');
    const okInfo  = document.getElementById('user-ok-info');
    const submitBtn = document.getElementById('submitBtn');

    warning.style.display = 'none';
    okInfo.style.display  = 'none';

    if (!opt.value) return;

    if (aktif >= maxPinjam) {
        document.getElementById('user-limit-text').textContent =
            `Member ini sudah memiliki ${aktif} peminjaman aktif (maks ${maxPinjam}). Harus mengembalikan buku terlebih dahulu.`;
        warning.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '.5';
    } else {
        const sisa = maxPinjam - aktif;
        document.getElementById('user-ok-text').textContent =
            aktif > 0
                ? `${aktif} peminjaman aktif — masih bisa meminjam ${sisa} buku lagi.`
                : `Belum ada peminjaman aktif — bisa meminjam hingga ${maxPinjam} buku.`;
        okInfo.style.display = 'block';
        submitBtn.disabled = false;
        submitBtn.style.opacity = '';
    }
}

function updateStok() {
    const sel  = document.getElementById('bukuSelect');
    const opt  = sel.options[sel.selectedIndex];
    const stok = opt.dataset.stok;
    const info = document.getElementById('stok-info');
    const jml  = document.getElementById('jumlahInput');
    if (stok !== undefined && stok !== '') {
        const color = parseInt(stok) > 0 ? 'var(--s1,#10b981)' : '#ef4444';
        info.innerHTML = `<i class="fas fa-info-circle"></i> Stok tersedia: <strong style="color:${color}">${stok}</strong>`;
        jml.max = stok;
    } else { info.innerHTML = ''; jml.removeAttribute('max'); }
}

// Trigger on page load jika ada old value
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('userSelect');
    if (sel.value) checkUserLimit(sel);
    updateStok();
});
</script>
@endpush
