@extends('layouts.app')
@section('title', $buku ? 'Edit Buku' : 'Tambah Buku')
@section('page-title', $buku ? 'Edit Buku' : 'Tambah Buku')
@section('breadcrumb', 'Beranda / Data Buku / ' . ($buku ? 'Edit' : 'Tambah'))

@section('content')
<div class="card anim" style="max-width:700px;">
    <div class="card-header">
        <h3><i class="fas fa-{{ $buku ? 'pen' : 'plus-circle' }}" style="color:var(--p2);margin-right:8px"></i>
            {{ $buku ? 'Edit Buku' : 'Tambah Buku Baru' }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $buku ? route('buku.update',$buku->id_buku) : route('buku.store') }}" enctype="multipart/form-data">
            @csrf
            @if($buku) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Judul Buku <span style="color:var(--red)">*</span></label>
                <input type="text" name="judul" class="form-control {{ $errors->has('judul') ? 'is-invalid' : '' }}"
                       placeholder="Masukkan judul buku" value="{{ old('judul',$buku?->judul) }}" autofocus>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Pengarang <span style="color:var(--red)">*</span></label>
                    <input type="text" name="pengarang" class="form-control {{ $errors->has('pengarang') ? 'is-invalid' : '' }}"
                           placeholder="Nama pengarang" value="{{ old('pengarang',$buku?->pengarang) }}">
                    @error('pengarang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Penerbit</label>
                    <input type="text" name="penerbit" class="form-control"
                           placeholder="Nama penerbit" value="{{ old('penerbit',$buku?->penerbit) }}">
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Tahun Terbit <span style="color:var(--red)">*</span></label>
                    <input type="number" name="tahun_terbit" class="form-control {{ $errors->has('tahun_terbit') ? 'is-invalid' : '' }}"
                           placeholder="2020" min="1900" max="{{ date('Y') }}" value="{{ old('tahun_terbit',$buku?->tahun_terbit) }}">
                    @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:var(--red)">*</span></label>
                    <select name="kategori" class="form-control {{ $errors->has('kategori') ? 'is-invalid' : '' }}">
                        @foreach(\App\Models\Buku::KATEGORI as $k)
                        <option value="{{ $k }}" {{ old('kategori',$buku?->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Stok <span style="color:var(--red)">*</span></label>
                    <input type="number" name="stok" class="form-control {{ $errors->has('stok') ? 'is-invalid' : '' }}"
                           placeholder="0" min="0" value="{{ old('stok',$buku?->stok) }}">
                    @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi Rak <span style="font-size:11px;color:var(--text2)">(opsional)</span></label>
                <input type="text" name="lokasi_rak" class="form-control"
                       placeholder="Contoh: Rak A-3" value="{{ old('lokasi_rak',$buku?->lokasi_rak) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Sinopsis <span style="font-size:11px;color:var(--text2)">(opsional)</span></label>
                <textarea name="sinopsis" class="form-control" rows="4"
                          placeholder="Deskripsi singkat buku...">{{ old('sinopsis',$buku?->sinopsis) }}</textarea>
            </div>

            {{-- COVER --}}
            <div class="form-group">
                <label class="form-label">Cover Buku <span style="font-size:11px;color:var(--text2)">(JPG/PNG/WEBP, maks 2MB)</span></label>
                <div style="display:flex;gap:16px;align-items:flex-start;">
                    <div id="cover-preview-wrap" style="flex-shrink:0;width:80px;height:106px;border-radius:10px;overflow:hidden;border:1.5px solid var(--border);background:var(--input-bg);display:flex;align-items:center;justify-content:center;">
                        @if($buku?->cover)
                            <img id="cover-preview" src="{{ Storage::url($buku->cover) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <img id="cover-preview" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
                            <i id="cover-ph" class="fas fa-image" style="font-size:24px;color:var(--text3)"></i>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <label id="drop-zone" for="cover-input" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:18px;border:1.5px dashed var(--border);border-radius:10px;cursor:pointer;transition:all .2s;background:var(--input-bg);">
                            <i class="fas fa-cloud-upload-alt" style="font-size:20px;color:var(--p2)"></i>
                            <span style="font-size:13px;color:var(--text2)">Klik atau seret gambar</span>
                        </label>
                        <input type="file" id="cover-input" name="cover" accept="image/*" style="display:none" onchange="previewCover(this)">
                        @error('cover')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                        @if($buku?->cover)
                        <label style="display:flex;align-items:center;gap:6px;margin-top:8px;font-size:12px;color:var(--red);cursor:pointer;">
                            <input type="checkbox" name="hapus_cover" value="1"> Hapus cover saat ini
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ $buku ? 'Simpan Perubahan' : 'Tambah Buku' }}
                </button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewCover(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('cover-preview');
        const ph  = document.getElementById('cover-ph');
        img.src = e.target.result;
        img.style.display = 'block';
        if (ph) ph.style.display = 'none';
    };
    reader.readAsDataURL(file);
}
const dz = document.getElementById('drop-zone');
const inp = document.getElementById('cover-input');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor = 'var(--p2)'; });
dz.addEventListener('dragleave', () => { dz.style.borderColor = ''; });
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.style.borderColor = '';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file); inp.files = dt.files; previewCover(inp);
    }
});
</script>
@endpush
