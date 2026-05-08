# 📔 Jurnal Proyek BismillahV2

## 📋 Deskripsi Proyek

**BismillahV2** adalah aplikasi manajemen sampah berbasis web yang dibangun menggunakan Laravel 11 dan Filament 3.3. Aplikasi ini dirancang untuk mengelola penjemputan sampah, transaksi nasabah, pengelolaan kelompok, dan pelaporan keuangan.

### Teknologi yang Digunakan
- **Framework**: Laravel 11
- **Admin Panel**: Filament 3.3
- **Database**: MySQL/SQLite
- **Payment Gateway**: Midtrans
- **Authentication**: Laravel Breeze + Spatie Permission
- **Frontend**: Blade Templates + Livewire

---

## 📅 Timeline Perkembangan

### 2025

#### Januari 2025
- ✅ Setup project Laravel 11 dengan Filament 3.3
- ✅ Konfigurasi database dan migration dasar
- ✅ Implementasi sistem autentikasi dengan role-based access control
- ✅ Integrasi Midtrans untuk payment gateway
- ✅ Pembuatan model dan migration untuk entitas utama:
  - User, Nasabah, Kelompok
  - Penjemputan, Transaksi
  - JenisSampah, HargaSampah
  - Kas, Pengeluaran
  - Artikel, Notification, LogAktivitas

#### Juli 2025
- ✅ Pembuatan migration untuk tabel-tabel utama
- ✅ Implementasi model relationships
- ✅ Pembuatan Filament Resources untuk CRUD:
  - NasabahResource
  - KelompokResource
  - PenjemputanResource
  - TransaksiResource
  - JenisSampahResource
  - KasResource
  - PengeluaranResource
  - ArtikelResource
- ✅ Implementasi Observer pattern untuk:
  - TransaksiObserver
  - PenjemputanObserver
  - SedekahSampahObserver
  - PengeluaranObserver
  - ArtikelObserver

#### Agustus 2025
- ✅ Implementasi sistem jadwal pengepul
- ✅ Penambahan fitur verifikasi berat sampah
- ✅ Implementasi payment options (take_all, donate_all, donate_partial)
- ✅ Penambahan field payment ke Penjemputan model
- ✅ Update sistem penjemputan dengan status tracking

#### Oktober 2025
- ✅ Implementasi sistem pengeluaran dengan approval workflow
- ✅ Pembuatan model PenggunaanDana
- ✅ Update transaksi dengan dual verification (nasabah & admin)
- ✅ Implementasi sistem sedekah sampah

#### November 2025
- ✅ Penambahan status dan alasan penolakan pada transaksi
- ✅ Implementasi verifikasi transaksi oleh nasabah
- ✅ Update sistem transaksi dengan status tracking

---

## 🎯 Fitur Utama

### 1. Manajemen Pengguna
- **Roles**: Admin, Pengepul, Kelompok Nasabah, Nasabah
- **User Management**: CRUD user dengan role assignment
- **Nasabah Management**: Pengelolaan data nasabah dengan saldo
- **Kelompok Management**: Pengelolaan kelompok nasabah

### 2. Manajemen Penjemputan
- **Jadwal Penjemputan**: Sistem jadwal rutin untuk pengepul
- **Request Penjemputan**: Nasabah dapat request penjemputan
- **Status Tracking**: 
  - Pending, Scheduled, In Progress, Completed, Cancelled
  - Weight Verified, Weight Disputed
- **Payment Options**:
  - Take All (100% ke nasabah)
  - Donate All (100% ke sistem)
  - Donate Partial (split antara nasabah dan sistem)
- **Weight Verification**: Sistem verifikasi berat dengan toleransi

### 3. Sistem Transaksi
- **Dual Verification**: Verifikasi oleh nasabah dan admin
- **Bukti Pembayaran**: Upload dan verifikasi bukti pembayaran
- **Status Tracking**: Pending, Verified, Rejected
- **Alasan Penolakan**: Tracking alasan jika transaksi ditolak
- **Automatic Journaling**: Otomatis membuat entry kas saat transaksi

### 4. Manajemen Keuangan
- **Kas Management**: Tracking kas masuk dan keluar
- **Pengeluaran**: Sistem pengeluaran dengan approval workflow
- **Laporan Keuangan**: 
  - Laporan Admin (pemasukan & pengeluaran)
  - Laporan Kelompok
- **Saldo Nasabah**: Tracking saldo per nasabah

### 5. Dashboard & Laporan
- **Dashboard Admin**: Overview statistik dan monitoring
- **Dashboard Pengepul**: Dashboard khusus untuk pengepul
- **Dashboard Kelompok**: Dashboard untuk koordinator kelompok
- **Laporan Admin**: Laporan keuangan dengan filter periode
- **Laporan Kelompok**: Laporan untuk kelompok nasabah

### 6. Verifikasi Transaksi
- **Verifikasi Transaksi Sistem**: Admin verifikasi transaksi sistem
- **Verifikasi Transaksi Nasabah**: Admin verifikasi transaksi nasabah
- **Verifikasi Transaksi Nasabah User**: Nasabah verifikasi transaksi mereka sendiri
- **Upload Bukti Transaksi**: Fitur upload bukti pembayaran

### 7. Konten & Informasi
- **Artikel Management**: CRUD artikel untuk informasi
- **Notification System**: Sistem notifikasi untuk user
- **Log Aktivitas**: Tracking semua aktivitas user

### 8. Pengaturan
- **Settings Management**: Pengaturan aplikasi yang dapat dikonfigurasi
- **Jenis Sampah**: Master data jenis sampah
- **Harga Sampah**: Pengelolaan harga per jenis sampah

---

## 📊 Struktur Database

### Tabel Utama
- `users` - User accounts dengan roles
- `nasabah` - Data nasabah dengan saldo
- `kelompok` - Kelompok nasabah
- `penjemputan` - Data penjemputan sampah
- `penjemputan_sampah_details` - Detail sampah per penjemputan
- `transaksi` - Transaksi pembayaran
- `kas` - Catatan kas masuk/keluar
- `pengeluaran` - Data pengeluaran
- `jenis_sampah` - Master jenis sampah
- `harga_sampah` - Master harga sampah
- `sedekah_sampah` - Data sedekah sampah
- `artikel` - Artikel/informasi
- `notifications` - Notifikasi user
- `log_aktivitas` - Log aktivitas sistem
- `settings` - Pengaturan aplikasi

---

## 🔧 Konfigurasi & Setup

### Environment Variables
```env
APP_NAME=BismillahV2
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bismillahv2
DB_USERNAME=root
DB_PASSWORD=

# Midtrans
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANDBOX=true
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
```

### Installation
1. Clone repository
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` ke `.env`
4. Generate key: `php artisan key:generate`
5. Setup database di `.env`
6. Run migration: `php artisan migrate`
7. Run seeder: `php artisan db:seed`
8. Link storage: `php artisan storage:link`
9. Build assets: `npm run build`

---

## 🚀 Deployment

Lihat file `DEPLOYMENT_GUIDE.md` untuk panduan lengkap deployment.

### Checklist Deployment
- [ ] Export database
- [ ] Update environment variables
- [ ] Upload files ke hosting
- [ ] Import database
- [ ] Run migration
- [ ] Set permissions
- [ ] Configure Midtrans production keys
- [ ] Test semua fitur

---

## 📝 Catatan Perkembangan

### Versi 1.0 (Current)
- ✅ Sistem dasar manajemen sampah
- ✅ Payment gateway integration
- ✅ Multi-role access control
- ✅ Dashboard dan laporan
- ✅ Verifikasi transaksi
- ✅ Sistem pengeluaran

### Planned Features
- [ ] Sistem jurnal akuntansi (double-entry)
- [ ] Export laporan ke Excel/PDF
- [ ] Mobile app integration
- [ ] Real-time notifications
- [ ] Advanced analytics
- [ ] API untuk third-party integration

---

## 🐛 Known Issues

### Minor Issues
- Cache perlu di-clear setelah update settings
- File upload perlu permission yang tepat di production

### Resolved Issues
- ✅ Payment callback handling
- ✅ Weight verification calculation
- ✅ Saldo nasabah update timing

---

## 📚 Dokumentasi Tambahan

- **Deployment Guide**: `DEPLOYMENT_GUIDE.md`
- **API Documentation**: (Coming soon)
- **User Manual**: (Coming soon)

---

## 👥 Tim Development

- **Developer**: [Nama Developer]
- **Project Manager**: [Nama PM]
- **Start Date**: Juli 2025
- **Status**: Active Development

---

## 📞 Support & Contact

Untuk pertanyaan atau issue, silakan hubungi:
- Email: [email]
- Repository: [repository_url]

---

## 📄 License

[License Information]

---

## 🔄 Changelog

### [2025-11-16]
- ✅ Penambahan status dan alasan penolakan pada transaksi
- ✅ Update sistem verifikasi transaksi

### [2025-10-23]
- ✅ Implementasi sistem pengeluaran dengan approval
- ✅ Update model Pengeluaran

### [2025-10-17]
- ✅ Penambahan dual verification pada transaksi
- ✅ Update sistem sedekah sampah

### [2025-10-05]
- ✅ Implementasi payment options
- ✅ Update Penjemputan dengan payment fields

### [2025-09-17]
- ✅ Implementasi weight verification system
- ✅ Update PenjemputanSampahDetail dengan berat verifikasi

### [2025-08-01]
- ✅ Implementasi jadwal pengepul
- ✅ Update sistem penjemputan

### [2025-07-03]
- ✅ Initial setup project
- ✅ Create base models and migrations
- ✅ Setup Filament admin panel

---

**Last Updated**: 2025-11-16
**Version**: 1.0.0

