@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb','Beranda / Dashboard')

@section('content')
{{-- KPI CARDS --}}
<div class="stats-grid">
    <div class="stat-card anim d1">
        <div class="stat-icon si-blue"><i class="fas fa-book"></i></div>
        <div><div class="stat-val">{{ $stats['total_buku'] }}</div><div class="stat-lbl">Total Buku</div></div>
    </div>
    <div class="stat-card anim d2">
        <div class="stat-icon si-purple"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $stats['total_user'] }}</div><div class="stat-lbl">Total Member</div></div>
    </div>
    <div class="stat-card anim d2">
        <div class="stat-icon si-orange"><i class="fas fa-clock"></i></div>
        <div><div class="stat-val">{{ $stats['pending'] }}</div><div class="stat-lbl">Menunggu Approval</div></div>
    </div>
    <div class="stat-card anim d3">
        <div class="stat-icon si-green"><i class="fas fa-exchange-alt"></i></div>
        <div><div class="stat-val">{{ $stats['dipinjam'] }}</div><div class="stat-lbl">Sedang Dipinjam</div></div>
    </div>
    <div class="stat-card anim d3">
        <div class="stat-icon si-red"><i class="fas fa-exclamation-triangle"></i></div>
        <div><div class="stat-val">{{ $stats['overdue'] }}</div><div class="stat-lbl">Terlambat</div></div>
    </div>
    <div class="stat-card anim d4">
        <div class="stat-icon si-teal"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-val">{{ $stats['dikembalikan'] }}</div><div class="stat-lbl">Dikembalikan</div></div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="grid-2 mb-4" style="margin-bottom:20px;">
    <div class="card anim">
        <div class="card-header">
            <h3><i class="fas fa-chart-bar" style="color:var(--p2);margin-right:6px"></i>Peminjaman 7 Hari Terakhir</h3>
        </div>
        <div class="card-body" style="padding:16px;">
            <div id="chart-bulan" style="min-height:220px;"></div>
        </div>
    </div>
    <div class="card anim d1">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie" style="color:var(--acc1);margin-right:6px"></i>Kategori Populer</h3>
        </div>
        <div class="card-body" style="padding:16px;">
            <div id="chart-kategori" style="min-height:220px;"></div>
        </div>
    </div>
</div>

{{-- BOTTOM ROW --}}
<div class="grid-2" style="gap:20px;">
    {{-- Buku Populer --}}
    <div class="card anim d2">
        <div class="card-header">
            <h3><i class="fas fa-fire" style="color:var(--acc2);margin-right:6px"></i>Buku Paling Dipinjam</h3>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($bukuPopuler as $i => $b)
                <tr>
                    <td><span style="font-weight:700;color:var(--p2)">{{ $i+1 }}</span></td>
                    <td>
                        <div style="font-weight:500;font-size:13px">{{ Str::limit($b->judul,30) }}</div>
                        <div style="font-size:11px;color:var(--text2)">{{ $b->pengarang }}</div>
                    </td>
                    <td><span class="badge badge-info">{{ $b->kategori }}</span></td>
                    <td><strong>{{ $b->total_dipinjam }}</strong></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center" style="padding:20px;color:var(--text2)">Belum ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- Overdue List --}}
    <div class="card anim d3">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-circle" style="color:var(--red);margin-right:6px"></i>Peminjaman Terlambat</h3>
            <a href="{{ route('peminjaman.index') }}?status=overdue" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead><tr><th>User</th><th>Buku</th><th>Terlambat</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($overdueList as $p)
                <tr>
                    <td style="font-size:13px">{{ $p->user?->nama_lengkap ?? $p->nama_peminjam }}</td>
                    <td style="font-size:12px;color:var(--text2)">{{ Str::limit($p->buku?->judul,22) }}</td>
                    <td><span class="badge badge-danger">{{ $p->late_days }} hari</span></td>
                    <td>
                        <form method="POST" action="{{ route('peminjaman.kembalikan',$p->id_pinjam) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success" title="Kembalikan"><i class="fas fa-undo"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:20px;text-align:center;color:var(--text2)"><i class="fas fa-check-circle" style="color:var(--s1)"></i> Tidak ada keterlambatan</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Return Pending List --}}
    <div class="card anim d3">
        <div class="card-header">
            <h3><i class="fas fa-truck-loading" style="color:var(--acc1);margin-right:6px"></i>Pengembalian (Menunggu Konfirmasi)</h3>
            <a href="{{ route('peminjaman.index') }}?status=return_pending" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead><tr><th>User</th><th>Buku</th><th>Kembali</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($returnPendingList as $p)
                <tr>
                    <td style="font-size:13px">{{ $p->user?->nama_lengkap ?? $p->nama_peminjam }}</td>
                    <td style="font-size:12px;color:var(--text2)">
                        <div style="display:flex;gap:10px;align-items:flex-start;">
                            <div style="width:32px;height:44px;border-radius:6px;overflow:hidden;background:rgba(255,255,255,.06);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                @if($p->buku?->cover)
                                    <img src="{{ Storage::url($p->buku->cover) }}" style="width:100%;height:100%;object-fit:cover;" />
                                @else
                                    <i class="fas fa-book" style="opacity:.5;"></i>
                                @endif
                            </div>
                            <div style="min-width:0;">
                                <div style="font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px;">{{ Str::limit($p->buku?->judul,28) }}</div>
                                <div style="font-size:11px;color:var(--text2);margin-top:2px;">Jatuh tempo: {{ $p->due_date?->format('d M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($p->return_date)
                            <span class="badge badge-success">{{ $p->return_date->format('d M Y') }}</span>
                        @else
                            <span class="badge badge-warning">{{ $p->tanggal_pinjam?->format('d M Y') }}</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('peminjaman.konfirmasiKembalikan', $p->id_pinjam) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success" title="Konfirmasi"><i class="fas fa-check-double"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:20px;text-align:center;color:var(--text2)"><i class="fas fa-check-circle" style="color:var(--s1)"></i> Tidak ada request pengembalian</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- User Pelanggaran --}}
@if($userPelanggaran->count() > 0)
<div class="card anim" style="margin-top:20px;">
    <div class="card-header">
        <h3><i class="fas fa-user-times" style="color:var(--red);margin-right:6px"></i>User dengan Pelanggaran</h3>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Kelola User</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table>
            <thead><tr><th>Nama</th><th>Username</th><th>Poin</th><th>Level</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($userPelanggaran as $u)
            <tr>
                <td style="font-weight:500">{{ $u->nama_lengkap }}</td>
                <td style="color:var(--text2)">{{ $u->username }}</td>
                <td><span class="badge {{ $u->violation_points >= 5 ? 'badge-danger' : ($u->violation_points >= 3 ? 'badge-warning' : 'badge-secondary') }}">{{ $u->violation_points }} poin</span></td>
                <td><span class="badge badge-info">{{ $u->violationLevel() }}</span></td>
                <td><span class="badge {{ $u->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($u->status) }}</span></td>
                <td><a href="{{ route('users.show',$u->id_user) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const isDark = () => document.documentElement.getAttribute('data-theme') === 'dark';
const textColor = () => isDark() ? '#94A3B8' : '#475569';
const gridColor = () => isDark() ? 'rgba(255,255,255,.06)' : '#F1F5F9';

// Chart Peminjaman Bulanan
const chartBulan = new ApexCharts(document.getElementById('chart-bulan'), {
    series: [{ name: 'Peminjaman', data: {!! json_encode($data) !!} }],
    chart: { type: 'area', height: 220, toolbar: { show: false }, background: 'transparent', animations: { enabled: true, easing: 'easeinout', speed: 600 } },
    colors: ['#3B82F6'],
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: .4, opacityTo: .05 } },
    stroke: { curve: 'smooth', width: 2.5 },
    xaxis: { categories: {!! json_encode($labels) !!}, labels: { style: { colors: textColor(), fontSize: '11px' } } },
    yaxis: { labels: { style: { colors: textColor() } } },
    grid: { borderColor: gridColor(), strokeDashArray: 4 },
    tooltip: { theme: isDark() ? 'dark' : 'light' },
    dataLabels: { enabled: false },
});
chartBulan.render();

// Chart Kategori
const chartKategori = new ApexCharts(document.getElementById('chart-kategori'), {
    series: {!! json_encode($kategoriPopuler->pluck('total')->toArray()) !!},
    labels: {!! json_encode($kategoriPopuler->pluck('kategori')->toArray()) !!},
    chart: { type: 'donut', height: 220, background: 'transparent' },
    colors: ['#3B82F6','#6366F1','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'],
    legend: { position: 'bottom', labels: { colors: textColor() } },
    dataLabels: { enabled: false },
    plotOptions: { pie: { donut: { size: '65%' } } },
    tooltip: { theme: isDark() ? 'dark' : 'light' },
});
chartKategori.render();

// Re-render on theme change
const observer = new MutationObserver(() => {
    chartBulan.updateOptions({ xaxis: { labels: { style: { colors: textColor() } } }, yaxis: { labels: { style: { colors: textColor() } } }, grid: { borderColor: gridColor() }, tooltip: { theme: isDark() ? 'dark' : 'light' } });
    chartKategori.updateOptions({ legend: { labels: { colors: textColor() } }, tooltip: { theme: isDark() ? 'dark' : 'light' } });
});
observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
</script>
@endpush
