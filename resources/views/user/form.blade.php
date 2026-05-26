@extends('layouts.app')
@section('title', $user ? 'Edit User' : 'Tambah User')
@section('page-title', $user ? 'Edit User' : 'Tambah User')
@section('breadcrumb', 'Beranda / Manajemen User / ' . ($user ? 'Edit' : 'Tambah'))

@section('content')
<div class="card anim" style="max-width:560px;">
    <div class="card-header">
        <h3><i class="fas fa-user{{ $user ? '-edit' : '-plus' }}" style="color:var(--p2);margin-right:6px"></i>
            {{ $user ? 'Edit User: '.$user->nama_lengkap : 'Tambah User Baru' }}
        </h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $user ? route('users.update',$user->id_user) : route('users.store') }}">
            @csrf
            @if($user) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}"
                       value="{{ old('nama_lengkap', $user?->nama_lengkap) }}" required>
                @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Username <span style="color:var(--red)">*</span></label>
                    <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                           value="{{ old('username', $user?->username) }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email', $user?->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Password
                    @if($user)
                        <span style="color:var(--text3);font-weight:400;font-size:12px">(kosongkan jika tidak diubah)</span>
                    @else
                        <span style="color:var(--red)">*</span>
                    @endif
                </label>
                <div style="position:relative;">
                    <input type="password" name="password" id="pw-input" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Min. 6 karakter' }}"
                           {{ $user ? '' : 'required' }}>
                    <button type="button" onclick="togglePwInput()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text3);">
                        <i class="fas fa-eye" id="pw-eye"></i>
                    </button>
                </div>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Batal</a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> {{ $user ? 'Simpan Perubahan' : 'Tambah User' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwInput() {
    const inp = document.getElementById('pw-input');
    const eye = document.getElementById('pw-eye');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
@endpush
