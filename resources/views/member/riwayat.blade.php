@extends('layouts.member')
@section('title','Riwayat Pinjam')
@section('page-title','Riwayat Peminjaman')
@section('breadcrumb','Member / Riwayat')

@section('content')
<div class="card animate-fade">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color:var(--p1);margin-right:6px"></i>Riwayat Peminjaman</h3>
        <a href="{{ route('member.katalog') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Pinjam Buku</a>
    </div>
    <div class="card-body" style="padding:0;">
        @forelse($peminjaman as $p)
        <div style="display:flex;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);align-items:flex-start;">

            {{-- Cover --}}
            @if($p->buku?->cover)
                <img src="{{ Storage::url($p->buku->cover) }}" style="width:44px;height:58px;object-fit:cover;border-radius:6px;flex-shrink:0;">
            @else
                <div style="width:44px;height:58px;background:linear-gradient(135deg,var(--p1),var(--p2));border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;flex-shrink:0;"><i class="fas fa-book"></i></div>
            @endif

            {{-- Info --}}
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->buku?->judul ?? 'Buku dihapus' }}</div>
                <div style="font-size:12px;color:var(--text2);margin-top:2px">{{ $p->buku?->pengarang }}</div>
                <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:8px;font-size:12px;color:var(--text2);">
                    <span><i class="fas fa-calendar"></i> Pinjam: {{ $p->tanggal_pinjam?->format('d M Y') }}</span>
                    @if($p->due_date)
                        <span><i class="fas fa-calendar-times"></i> Jatuh tempo: {{ $p->due_date->format('d M Y') }}</span>
                    @endif
                    @if($p->return_date)
                        <span><i class="fas fa-calendar-check" style="color:var(--s1)"></i> Kembali: {{ $p->return_date->format('d M Y') }}</span>
                    @endif
                    <span><i class="fas fa-clock"></i> {{ $p->durasi_pinjam }} hari</span>
                </div>
                @if($p->late_days > 0)
                <div style="margin-top:6px;font-size:12px;color:var(--red);">
                    <i class="fas fa-exclamation-triangle"></i> Terlambat {{ $p->late_days }} hari
                    @if($p->penalty > 0) &middot; Denda: Rp{{ number_format($p->penalty,0,',','.') }}@endif
                </div>
                @endif
                @if($p->catatan_admin)
                <div style="margin-top:6px;font-size:12px;color:var(--text2);background:var(--hover-bg);padding:6px 10px;border-radius:6px;">
                    <i class="fas fa-comment"></i> <em>{{ $p->catatan_admin }}</em>
                </div>
                @endif
                @if($p->status === 'perpanjang_pending')
                <div style="margin-top:6px;font-size:12px;color:var(--acc);background:rgba(245,158,11,.08);padding:6px 10px;border-radius:6px;border:1px solid rgba(245,158,11,.2);">
                    <i class="fas fa-clock"></i> Menunggu konfirmasi perpanjangan +{{ $p->perpanjang_durasi }} hari
                </div>
                @endif
            </div>

            {{-- Status & Aksi --}}
            <div style="flex-shrink:0;text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                <span class="badge badge-{{ $p->statusColor() }}">{{ $p->statusLabel() }}</span>

                @if(in_array($p->status, ['borrowed','overdue']))
                <form method="POST" action="{{ route('member.kembalikan',$p->id_pinjam) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Kembalikan buku ini?')">
                        <i class="fas fa-undo"></i> Kembalikan
                    </button>
                </form>
                <button onclick="bukaPerpanjang({{ $p->id_pinjam }}, '{{ $p->buku?->judul }}', '{{ $p->due_date?->format('d M Y') }}')"
                        class="btn btn-sm btn-secondary">
                    <i class="fas fa-calendar-plus"></i> Perpanjang
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <p>Belum ada riwayat peminjaman. <a href="{{ route('member.katalog') }}" style="color:var(--p1)">Pinjam buku sekarang</a></p>
        </div>
        @endforelse
    </div>
</div>
{{ $peminjaman->links('vendor.pagination.custom') }}

{{-- MODAL PERPANJANG — satu modal, diisi via JS --}}
<div class="modal-overlay" id="modal-perpanjang">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-calendar-plus" style="color:var(--p1);margin-right:6px"></i>Request Perpanjangan</h3>
            <button class="modal-close" onclick="closeModal('modal-perpanjang')"><i class="fas fa-times"></i></button>
        </div>

        <div style="margin-bottom:14px;padding:12px;background:var(--hover-bg);border-radius:10px;font-size:13px;">
            <div style="font-weight:600;" id="perp-judul">-</div>
            <div style="color:var(--text2);margin-top:4px;">
                Jatuh tempo saat ini: <strong id="perp-due">-</strong>
            </div>
        </div>

        <form method="POST" id="form-perpanjang" action="">
            @csrf
            <div class="form-group">
                <label class="form-label">Tambah Durasi <span style="color:var(--red)">*</span></label>
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                    <button type="button" class="btn btn-sm btn-primary durasi-btn" data-val="3" onclick="pilihDurasi(3)">3 hari</button>
                    <button type="button" class="btn btn-sm btn-secondary durasi-btn" data-val="7" onclick="pilihDurasi(7)">7 hari</button>
                    <button type="button" class="btn btn-sm btn-secondary durasi-btn" data-val="14" onclick="pilihDurasi(14)">14 hari</button>
                </div>
                <input type="hidden" name="durasi_tambah" id="input-durasi" value="3">
                <div style="font-size:12px;color:var(--text2);">
                    Jatuh tempo baru: <strong id="perp-new-due">-</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Alasan <span style="font-size:11px;color:var(--text2)">(opsional)</span></label>
                <textarea name="catatan_user" class="form-control" rows="2" placeholder="Alasan perpanjangan..."></textarea>
            </div>
            <div style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.15);border-radius:8px;padding:10px;font-size:12px;color:var(--text2);margin-bottom:14px;">
                <i class="fas fa-info-circle" style="color:var(--p1)"></i>
                Request akan diproses admin. Jatuh tempo tidak berubah sampai disetujui.
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-perpanjang')" class="btn btn-secondary btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Kirim Request</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let perpDueDate = null;

function bukaPerpanjang(id, judul, due) {
    document.getElementById('perp-judul').textContent = judul;
    document.getElementById('perp-due').textContent   = due;
    document.getElementById('form-perpanjang').action = '/member/perpanjang/' + id;

    // Parse due date
    const parts = due.split(' ');
    const months = {Jan:0,Feb:1,Mar:2,Apr:3,Mei:4,Jun:5,Jul:6,Agu:7,Sep:8,Okt:9,Nov:10,Des:11};
    perpDueDate = new Date(parts[2], months[parts[1]] ?? 0, parseInt(parts[0]));

    // Reset ke 3 hari
    pilihDurasi(3);
    openModal('modal-perpanjang');
}

function pilihDurasi(val) {
    document.getElementById('input-durasi').value = val;

    document.querySelectorAll('.durasi-btn').forEach(b => {
        b.classList.toggle('btn-primary',  parseInt(b.dataset.val) === val);
        b.classList.toggle('btn-secondary', parseInt(b.dataset.val) !== val);
    });

    if (perpDueDate) {
        const d = new Date(perpDueDate);
        d.setDate(d.getDate() + val);
        document.getElementById('perp-new-due').textContent =
            d.toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
    }
}
</script>
@endpush
