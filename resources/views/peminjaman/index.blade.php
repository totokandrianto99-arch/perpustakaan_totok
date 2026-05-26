@extends('layouts.app')
@section('title','Peminjaman')
@section('page-title','Manajemen Peminjaman')
@section('breadcrumb','Beranda / Peminjaman')

@section('content')
{{-- STATS --}}
<div class="stats-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:20px;">
    <div class="stat-card anim d1">
        <div class="stat-icon si-orange"><i class="fas fa-clock"></i></div>
        <div><div class="stat-val">{{ $stats['pending'] }}</div><div class="stat-lbl">Menunggu</div></div>
    </div>
    <div class="stat-card anim d2">
        <div class="stat-icon si-blue"><i class="fas fa-book-open"></i></div>
        <div><div class="stat-val">{{ $stats['borrowed'] }}</div><div class="stat-lbl">Dipinjam</div></div>
    </div>
    <div class="stat-card anim d3">
        <div class="stat-icon si-red"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="stat-val">{{ $stats['overdue'] }}</div><div class="stat-lbl">Terlambat</div></div>
    </div>
    <div class="stat-card anim d4">
        <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-val">{{ $stats['returned'] }}</div><div class="stat-lbl">Dikembalikan</div></div>
    </div>
    <div class="stat-card anim d4">
        <div class="stat-icon si-purple"><i class="fas fa-calendar-plus"></i></div>
        <div><div class="stat-val">{{ $stats['perpanjang_pending'] }}</div><div class="stat-lbl">Req. Perpanjang</div></div>
    </div>
</div>

<div class="card anim">
    <div class="card-header">
        <h3><i class="fas fa-exchange-alt" style="color:var(--p2);margin-right:6px"></i>Daftar Peminjaman</h3>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Input Manual</a>
    </div>
    <div class="card-body" style="padding:16px 22px;">
        {{-- FILTER --}}
        <form method="GET" id="filter-form-pinjam" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px;">
            <div style="position:relative;">
                <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text3);font-size:12px;"></i>
                <input type="text" name="search" id="search-pinjam" class="form-control" style="padding:8px 12px 8px 32px;max-width:220px;"
                       placeholder="Cari nama / judul..." value="{{ request('search') }}">
            </div>
            @php
            $statusOpts = ['' => 'Semua Status'];
            foreach(\App\Models\Peminjaman::STATUS_LABELS as $val => $info) {
                $statusOpts[$val] = $info['label'];
            }
            $statusDots = [
                'pending'  => '#F59E0B', 'approved' => '#3B82F6',
                'rejected' => '#EF4444', 'borrowed' => '#2543EB',
                'returned' => '#10B981', 'overdue'  => '#EF4444', 'suspended' => '#64748B',
            ];
            $statusOptsNorm = [['value'=>'','label'=>'Semua Status']];
            foreach(\App\Models\Peminjaman::STATUS_LABELS as $val => $info) {
                $statusOptsNorm[] = ['value'=>$val,'label'=>$info['label'],'dot'=>$statusDots[$val]??'#94A3B8'];
            }
            @endphp
            <x-custom-select name="status" placeholder="Semua Status" icon="filter" width="160px"
                :selected="request('status','')" :options="$statusOptsNorm"/>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> Reset</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th><th>Durasi</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($peminjaman as $i => $p)
                <tr>
                    <td style="color:var(--text2)">{{ $peminjaman->firstItem() + $i }}</td>
                    <td>
                        <div style="font-weight:500;font-size:13px">{{ $p->nama_peminjam }}</div>
                        @if($p->user)<div style="font-size:11px;color:var(--text2)">{{ '@'.$p->user->username }}</div>@endif
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:500">{{ Str::limit($p->buku?->judul,28) }}</div>
                        <div style="font-size:11px;color:var(--text2)">Jml: {{ $p->jumlah_pinjam }}</div>
                    </td>
                    <td style="font-size:12px;color:var(--text2)">{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                    <td style="font-size:12px;">
                        @if($p->due_date)
                            <span style="color:{{ $p->isOverdue() ? 'var(--red)' : 'var(--text2)' }}">
                                {{ $p->due_date->format('d M Y') }}
                                @if($p->late_days > 0)<br><span style="color:var(--red);font-size:11px">+{{ $p->late_days }} hari</span>@endif
                            </span>
                            @if($p->status === 'perpanjang_pending')
                            <br><span style="font-size:11px;color:var(--acc);"><i class="fas fa-calendar-plus"></i> +{{ $p->perpanjang_durasi }} hari pending</span>
                            @endif
                        @else -
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text2)">{{ $p->durasi_pinjam }} hari</td>
                    <td><span class="badge badge-{{ $p->statusColor() }}">{{ $p->statusLabel() }}</span></td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            {{-- APPROVE PEMINJAMAN --}}
                            @if($p->status === 'pending')
                            <button onclick="openModal('approve-{{ $p->id_pinjam }}')" class="btn btn-sm btn-success btn-icon" title="Setujui"><i class="fas fa-check"></i></button>
                            <button onclick="openModal('reject-{{ $p->id_pinjam }}')" class="btn btn-sm btn-danger btn-icon" title="Tolak"><i class="fas fa-times"></i></button>
                            @endif

                            {{-- APPROVE PERPANJANGAN --}}
                            @if($p->status === 'perpanjang_pending')
                            <button onclick="openModal('app-perp-{{ $p->id_pinjam }}')" class="btn btn-sm btn-success btn-icon" title="Setujui Perpanjangan"><i class="fas fa-calendar-check"></i></button>
                            <button onclick="openModal('rej-perp-{{ $p->id_pinjam }}')" class="btn btn-sm btn-danger btn-icon" title="Tolak Perpanjangan"><i class="fas fa-calendar-times"></i></button>
                            @endif

                            {{-- KEMBALIKAN --}}
                            @if(in_array($p->status,['borrowed','overdue']))
                            <button onclick="openModal('kembali-{{ $p->id_pinjam }}')" class="btn btn-sm btn-warning btn-icon" title="Kembalikan"><i class="fas fa-undo"></i></button>
                            <button onclick="openModal('perpanjang-{{ $p->id_pinjam }}')" class="btn btn-sm btn-secondary btn-icon" title="Perpanjang Langsung"><i class="fas fa-calendar-plus"></i></button>
                            @endif

                            {{-- DELETE --}}
                            <button onclick="openModal('del-pinjam-{{ $p->id_pinjam }}')" class="btn btn-sm btn-danger btn-icon" title="Hapus"><i class="fas fa-trash"></i></button>
                        </div>

                        {{-- Modal Approve --}}
                        <div class="modal-overlay" id="approve-{{ $p->id_pinjam }}">
                            <div class="modal">
                                <div class="modal-header"><h3>Setujui Peminjaman</h3><button class="modal-close" onclick="closeModal('approve-{{ $p->id_pinjam }}')"><i class="fas fa-times"></i></button></div>
                                <p style="font-size:13px;color:var(--text2);margin-bottom:14px">Setujui peminjaman <strong>{{ $p->buku?->judul }}</strong> oleh <strong>{{ $p->nama_peminjam }}</strong>?</p>
                                <form method="POST" action="{{ route('peminjaman.approve',$p->id_pinjam) }}">
                                    @csrf @method('PATCH')
                                    <div class="form-group">
                                        <label class="form-label">Catatan (opsional)</label>
                                        <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Catatan untuk peminjam..."></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="closeModal('approve-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Setujui</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Reject --}}
                        <div class="modal-overlay" id="reject-{{ $p->id_pinjam }}">
                            <div class="modal">
                                <div class="modal-header"><h3>Tolak Peminjaman</h3><button class="modal-close" onclick="closeModal('reject-{{ $p->id_pinjam }}')"><i class="fas fa-times"></i></button></div>
                                <form method="POST" action="{{ route('peminjaman.reject',$p->id_pinjam) }}">
                                    @csrf @method('PATCH')
                                    <div class="form-group">
                                        <label class="form-label">Alasan Penolakan <span style="color:var(--red)">*</span></label>
                                        <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="closeModal('reject-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Kembalikan --}}
                        <div class="modal-overlay" id="kembali-{{ $p->id_pinjam }}">
                            <div class="modal" style="max-width:380px;">
                                <div class="modal-header"><h3>Konfirmasi Pengembalian</h3><button class="modal-close" onclick="closeModal('kembali-{{ $p->id_pinjam }}')"><i class="fas fa-times"></i></button></div>
                                <p style="font-size:13px;color:var(--text2)">Tandai buku <strong>{{ $p->buku?->judul }}</strong> sudah dikembalikan?
                                @if($p->late_days > 0)<br><span style="color:var(--red)">Terlambat {{ $p->late_days }} hari.</span>@endif</p>
                                <div class="modal-footer">
                                    <button type="button" onclick="closeModal('kembali-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                    <form method="POST" action="{{ route('peminjaman.kembalikan',$p->id_pinjam) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-undo"></i> Kembalikan</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Perpanjang --}}
                        <div class="modal-overlay" id="perpanjang-{{ $p->id_pinjam }}">
                            <div class="modal" style="max-width:380px;">
                                <div class="modal-header"><h3>Perpanjang Peminjaman</h3><button class="modal-close" onclick="closeModal('perpanjang-{{ $p->id_pinjam }}')"><i class="fas fa-times"></i></button></div>
                                <form method="POST" action="{{ route('peminjaman.perpanjang',$p->id_pinjam) }}">
                                    @csrf @method('PATCH')
                                    <div class="form-group">
                                        <label class="form-label">Tambah Durasi (hari, maks 14)</label>
                                        <input type="number" name="durasi_tambah" class="form-control" min="1" max="14" value="7">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="closeModal('perpanjang-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-calendar-plus"></i> Perpanjang</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Approve Perpanjangan --}}
                        @if($p->status === 'perpanjang_pending')
                        <div class="modal-overlay" id="app-perp-{{ $p->id_pinjam }}">
                            <div class="modal">
                                <div class="modal-header">
                                    <h3><i class="fas fa-calendar-check" style="color:var(--s1);margin-right:6px"></i>Setujui Perpanjangan</h3>
                                    <button class="modal-close" onclick="closeModal('app-perp-{{ $p->id_pinjam }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div style="background:var(--hover-bg);border-radius:10px;padding:12px;margin-bottom:14px;font-size:13px;">
                                    <div><strong>{{ $p->buku?->judul }}</strong></div>
                                    <div style="color:var(--text2);margin-top:4px;">Peminjam: {{ $p->nama_peminjam }}</div>
                                    <div style="color:var(--text2);margin-top:2px;">Jatuh tempo: {{ $p->due_date?->format('d M Y') }}</div>
                                    <div style="margin-top:6px;">
                                        <span class="badge badge-warning">Request: +{{ $p->perpanjang_durasi ?? '-' }} hari</span>
                                        @if($p->perpanjang_catatan)
                                        <div style="margin-top:6px;font-style:italic;color:var(--text2);">"{{ $p->perpanjang_catatan }}"</div>
                                        @endif
                                    </div>
                                    @if($p->perpanjang_durasi)
                                    <div style="margin-top:6px;color:var(--s1);font-size:12px;">
                                        <i class="fas fa-arrow-right"></i> Jatuh tempo baru: <strong>{{ $p->due_date?->addDays($p->perpanjang_durasi)->format('d M Y') }}</strong>
                                    </div>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('peminjaman.approvePerpanjang', $p->id_pinjam) }}">
                                    @csrf @method('PATCH')
                                    <div class="form-group">
                                        <label class="form-label">Catatan (opsional)</label>
                                        <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Catatan untuk peminjam..."></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="closeModal('app-perp-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Setujui Perpanjangan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Reject Perpanjangan --}}
                        <div class="modal-overlay" id="rej-perp-{{ $p->id_pinjam }}">
                            <div class="modal">
                                <div class="modal-header">
                                    <h3><i class="fas fa-calendar-times" style="color:var(--red);margin-right:6px"></i>Tolak Perpanjangan</h3>
                                    <button class="modal-close" onclick="closeModal('rej-perp-{{ $p->id_pinjam }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <p style="font-size:13px;color:var(--text2);margin-bottom:14px;">Tolak request perpanjangan <strong>{{ $p->perpanjang_durasi ?? '-' }} hari</strong> dari <strong>{{ $p->nama_peminjam }}</strong>?</p>
                                <form method="POST" action="{{ route('peminjaman.rejectPerpanjang', $p->id_pinjam) }}">
                                    @csrf @method('PATCH')
                                    <div class="form-group">
                                        <label class="form-label">Alasan Penolakan <span style="color:var(--red)">*</span></label>
                                        <textarea name="catatan_admin" class="form-control" rows="3" required placeholder="Tuliskan alasan..."></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="closeModal('rej-perp-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        {{-- Modal Hapus --}}
                        <div class="modal-overlay" id="del-pinjam-{{ $p->id_pinjam }}">
                            <div class="modal" style="max-width:360px;">
                                <div class="modal-header"><h3>Hapus Data</h3><button class="modal-close" onclick="closeModal('del-pinjam-{{ $p->id_pinjam }}')"><i class="fas fa-times"></i></button></div>
                                <p style="font-size:13px;color:var(--text2)">Hapus data peminjaman ini?</p>
                                <div class="modal-footer">
                                    <button type="button" onclick="closeModal('del-pinjam-{{ $p->id_pinjam }}')" class="btn btn-secondary btn-sm">Batal</button>
                                    <form method="POST" action="{{ route('peminjaman.destroy',$p->id_pinjam) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-exchange-alt"></i><p>Belum ada data peminjaman</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $peminjaman->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
let searchTimerP;
document.getElementById('search-pinjam').addEventListener('input', function() {
    clearTimeout(searchTimerP);
    searchTimerP = setTimeout(() => document.getElementById('filter-form-pinjam').submit(), 500);
});
</script>
@endpush
