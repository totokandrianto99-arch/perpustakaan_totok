# Laporan Pengerjaan — Sistem Peminjaman Buku Perpustakaan

## Identitas Proyek
- **Nama Aplikasi**: Sistem Peminjaman Buku Perpustakaan
- **Framework**: Laravel 11
- **Database**: MySQL (db_perpustakaan)
- **Server**: Laragon (Apache + MySQL)

---

## Spesifikasi Teknis

### Stack Teknologi
| Komponen | Teknologi |
|----------|-----------|
| Backend  | PHP 8.x / Laravel 11 |
| Frontend | Blade Template + CSS Custom |
| Database | MySQL / MariaDB |
| Server   | Laragon (Apache) |
| Font     | Inter (Google Fonts) |
| Icons    | Font Awesome 6 |

---

## Struktur Database

### Tabel `user`
| Field      | Tipe    | Keterangan        |
|------------|---------|-------------------|
| id_user    | BIGINT  | Primary Key (AI)  |
| username   | VARCHAR | Unique            |
| password   | VARCHAR | Bcrypt hashed     |
| level      | ENUM    | admin / petugas   |

### Tabel `buku`
| Field        | Tipe    | Keterangan       |
|--------------|---------|------------------|
| id_buku      | BIGINT  | Primary Key (AI) |
| judul        | VARCHAR | Judul buku       |
| pengarang    | VARCHAR | Nama pengarang   |
| tahun_terbit | YEAR    | Tahun terbit     |
| stok         | INT     | Jumlah stok      |

### Tabel `peminjaman`
| Field          | Tipe    | Keterangan              |
|----------------|---------|-------------------------|
| id_pinjam      | BIGINT  | Primary Key (AI)        |
| nama_peminjam  | VARCHAR | Nama peminjam           |
| tanggal_pinjam | DATE    | Tanggal pinjam          |
| id_buku        | BIGINT  | FK → buku.id_buku       |
| jumlah_pinjam  | INT     | Jumlah buku dipinjam    |
| status         | ENUM    | dipinjam / dikembalikan |

---

## Fitur Aplikasi

### ✅ Login Admin
- Autentikasi menggunakan username & password
- Session-based authentication (Laravel Auth)
- Redirect otomatis ke dashboard setelah login

### ✅ Dashboard
- Statistik: Total buku, total transaksi, sedang dipinjam, dikembalikan
- Tabel buku terbaru (5 data)
- Tabel peminjaman terbaru (5 data)

### ✅ Manajemen Buku (CRUD)
- Tambah buku baru
- Edit data buku
- Hapus buku
- Tampilkan daftar buku dengan pagination

### ✅ Transaksi Peminjaman
- Input peminjaman baru (otomatis kurangi stok)
- Konfirmasi pengembalian (otomatis tambah stok)
- Hapus data transaksi
- Status: dipinjam / dikembalikan

---

## Konfigurasi Jaringan Lokal (LAN)

### Topologi: Star
```
[CLIENT 1] ──┐
              ├── [SWITCH] ── [SERVER]
[CLIENT 2] ──┘
```

### Pengaturan IP Address (Manual)
| Perangkat | IP Address          | Subnet Mask   |
|-----------|---------------------|---------------|
| Server    | 192.168.no_absen.10 | 255.255.255.0 |
| Client 1  | 192.168.no_absen.11 | 255.255.255.0 |
| Client 2  | 192.168.no_absen.12 | 255.255.255.0 |

### Langkah Konfigurasi Server
1. Buka **Control Panel → Network → Change adapter settings**
2. Klik kanan adapter → Properties → IPv4
3. Set IP: `192.168.X.10`, Subnet: `255.255.255.0`
4. Buka Laragon → Start Apache & MySQL
5. Buka Windows Firewall → Allow Apache (port 80)

### Akses dari Client
```
http://192.168.X.10/perpustakaan_totok/public
```

### Pengujian Koneksi
```bash
# Dari client, ping ke server
ping 192.168.X.10

# Jika reply → koneksi berhasil
```

---

## Akun Default
| Username | Password | Level |
|----------|----------|-------|
| admin    | admin123 | admin |

---

## Cara Menjalankan

```bash
# 1. Clone / copy ke folder Laragon www
# 2. Buat database db_perpustakaan di phpMyAdmin
# 3. Konfigurasi .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
# 4. Jalankan migrasi
php artisan migrate --seed

# 5. Akses aplikasi
http://localhost/perpustakaan_totok/public
```
