@extends('layouts.app')
@section('title','Pengaturan')
@section('page-title','Pengaturan Sistem')
@section('breadcrumb','Beranda / Pengaturan')

@section('content')
<div class="card anim" style="max-width:600px;">
    <div class="card-header">
        <h3><i class="fas fa-cog" style="color:var(--p2);margin-right:6px"></i>Pengaturan Sistem</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @foreach($settings as $key => $s)
            <div class="form-group">
                <label class="form-label">{{ $s['label'] }}</label>
                @if($key === 'denda_aktif')
                <select name="{{ $key }}" class="form-control">
                    <option value="0" {{ $s['value'] == '0' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="1" {{ $s['value'] == '1' ? 'selected' : '' }}>Aktif</option>
                </select>
                @else
                <input type="{{ in_array($key,['durasi_pinjam_default','durasi_pinjam_max','denda_per_hari','max_pinjam_aktif']) ? 'number' : 'text' }}"
                       name="{{ $key }}" class="form-control" value="{{ $s['value'] }}"
                       min="{{ in_array($key,['durasi_pinjam_default','durasi_pinjam_max']) ? 1 : 0 }}">
                @endif
            </div>
            @endforeach

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection
