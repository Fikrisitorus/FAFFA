# Bab 4: Implementasi Sistem - Antarmuka Pengguna

## 4.2 Antarmuka Pengguna (User Interface)

Bagian ini menjelaskan antarmuka pengguna untuk setiap halaman dalam sistem manajemen sampah, termasuk fitur-fitur, tombol, dan fungsinya.

---

## 4.2.1 Halaman Autentikasi

### A. Halaman Login

**Screenshot:**
![Halaman Login](path/to/screenshot/login.png)

**Deskripsi:**
Halaman login merupakan gerbang masuk pengguna ke sistem. Halaman ini menerapkan autentikasi dengan validasi kredensial.

**Fitur dan Elemen:**

1. **Form Login**
   - **Field Email/Username:** Input untuk memasukkan email atau username pengguna
   - **Field Password:** Input terenkripsi untuk memasukkan password
   - **Checkbox "Remember Me":** Opsi untuk menyimpan session login
   
2. **Tombol "Login"**
   - **Fungsi:** Mengirim kredensial ke server untuk validasi
   - **Aksi:** Jika valid, redirect ke dashboard sesuai role; jika invalid, tampilkan pesan error

3. **Link "Lupa Password"**
   - **Fungsi:** Mengarahkan ke halaman reset password
   - **Aksi:** Menampilkan form untuk request reset password via email

4. **Link "Daftar Akun Baru"**
   - **Fungsi:** Mengarahkan ke halaman registrasi untuk user baru
   - **Aksi:** Redirect ke form pendaftaran

**Validasi:**
- Email harus dalam format yang valid
- Password minimal 8 karakter
- Menampilkan pesan error jika kredensial salah

---

### B. Halaman Register

**Screenshot:**
![Halaman Register](path/to/screenshot/register.png)

**Deskripsi:**
Halaman registrasi untuk pendaftaran akun baru dengan role User/Nasabah.

**Fitur dan Elemen:**

1. **Form Registrasi**
   - **Field Nama Lengkap:** Input nama lengkap pengguna
   - **Field Email:** Input email yang akan digunakan untuk login
   - **Field Password:** Input password dengan validasi strength
   - **Field Konfirmasi Password:** Konfirmasi password harus sama
   - **Field Nomor Telepon:** Input nomor telepon untuk komunikasi
   - **Dropdown Kelompok (opsional):** Pilih kelompok nasabah jika bergabung

2. **Tombol "Daftar"**
   - **Fungsi:** Submit data registrasi ke server
   - **Aksi:** Create user baru dengan role "nasabah", kirim email verifikasi

3. **Link "Sudah Punya Akun? Login"**
   - **Fungsi:** Kembali ke halaman login
   - **Aksi:** Redirect ke halaman login

**Validasi:**
- Semua field required kecuali kelompok
- Email harus unique dalam sistem
- Password minimal 8 karakter dengan kombinasi huruf dan angka
- Konfirmasi password harus sama

---

## 4.2.2 Dashboard Admin

### A. Dashboard Utama Admin

**Screenshot:**
![Dashboard Admin](path/to/screenshot/admin-dashboard.png)

**Deskripsi:**
Dashboard admin menampilkan ringkasan statistik sistem secara real-time dengan berbagai widget dan grafik.

**Fitur dan Elemen:**

1. **Widget Statistik**
   - **Total Pengguna:** Menampilkan jumlah total pengguna aktif
   - **Total Penjemputan Bulan Ini:** Jumlah penjemputan dalam periode bulan berjalan
   - **Total Transaksi Bulan Ini:** Jumlah transaksi keuangan
   - **Total Donasi Sistem:** Akumulasi donasi yang masuk ke sistem

2. **Grafik Penjemputan per Bulan**
   - **Fungsi:** Visualisasi trend penjemputan dalam 12 bulan terakhir
   - **Interaksi:** Hover untuk melihat detail per bulan

3. **Grafik Transaksi**
   - **Fungsi:** Visualisasi total nilai transaksi per periode
   - **Filter:** Dapat difilter berdasarkan rentang tanggal

4. **Tabel Aktivitas Terbaru**
   - **Fungsi:** Menampilkan 10 aktivitas terbaru di sistem
   - **Kolom:** Waktu, User, Aktivitas, Status
   - **Aksi:** Click untuk melihat detail

5. **Menu Navigasi Sidebar**
   - **Dashboard:** Kembali ke halaman dashboard
   - **Pengguna & Role:** Manajemen user dan permission
   - **Master Data:** Kelola data master sistem
   - **Transaksi:** Lihat dan verifikasi transaksi
   - **Laporan:** Generate berbagai laporan
   - **Setting:** Konfigurasi sistem

---

### B. Halaman Manajemen Pengguna

**Screenshot:**
![Manajemen Pengguna](path/to/screenshot/admin-users.png)

**Deskripsi:**
Halaman untuk mengelola pengguna sistem dengan CRUD operations dan role assignment.

**Fitur dan Elemen:**

1. **Tombol "Tambah Pengguna Baru"** (di kanan atas)
   - **Fungsi:** Membuka form untuk menambah user baru
   - **Aksi:** Tampilkan modal form dengan field nama, email, password, role

2. **Field Search/Pencarian**
   - **Fungsi:** Mencari user berdasarkan nama atau email
   - **Aksi:** Real-time filter tabel saat mengetik

3. **Filter Role**
   - **Fungsi:** Filter pengguna berdasarkan role (Admin, Pengepul, Nasabah, Kelompok)
   - **Aksi:** Update tabel sesuai role yang dipilih

4. **Tabel Daftar Pengguna**
   - **Kolom:**
     - Nama: Nama lengkap pengguna
     - Email: Email login
     - Role: Badge menunjukkan role pengguna
     - Status: Active/Inactive dengan toggle
     - Aksi: Tombol Edit dan Hapus
   
5. **Tombol "Edit"** (setiap row)
   - **Fungsi:** Edit data pengguna
   - **Aksi:** Buka modal edit dengan data pengguna ter-populate

6. **Tombol "Hapus"** (setiap row)
   - **Fungsi:** Hapus/nonaktifkan pengguna
   - **Aksi:** Konfirmasi dialog, soft delete user

7. **Pagination**
   - **Fungsi:** Navigasi halaman tabel
   - **Elemen:** Previous, angka halaman, Next, jumlah per halaman

---

### C. Halaman Verifikasi Transaksi Sistem

**Screenshot:**
![Verifikasi Transaksi Sistem](path/to/screenshot/admin-verif-sistem.png)

**Deskripsi:**
Halaman untuk admin memverifikasi transaksi yang menggunakan dana sistem (donasi).

**Fitur dan Elemen:**

1. **Filter Status**
   - **Opsi:** Semua, Pending, Verified, Rejected
   - **Fungsi:** Filter transaksi berdasarkan status verifikasi

2. **Filter Tanggal**
   - **Field:** Dari Tanggal, Sampai Tanggal
   - **Fungsi:** Filter transaksi berdasarkan periode
   - **Tombol "Apply":** Terapkan filter

3. **Tabel Transaksi**
   - **Kolom:**
     - No. Transaksi: Kode unik transaksi
     - Tanggal: Waktu transaksi dibuat
     - Nasabah: Nama penerima pembayaran
     - Pengepul: Nama yang memproses
     - Jenis Sampah: Kategori sampah
     - Berat (kg): Berat sampah terverifikasi
     - Total: Nominal pembayaran
     - Status: Badge status verifikasi
     - Aksi: Tombol detail/verifikasi

4. **Tombol "Lihat Detail"** (setiap row)
   - **Fungsi:** Melihat detail lengkap transaksi
   - **Aksi:** Buka modal dengan info lengkap + bukti pembayaran

5. **Tombol "Verifikasi"** (dalam modal detail)
   - **Fungsi:** Approve transaksi sebagai valid
   - **Aksi:** Update status menjadi verified, set verified_by_admin

6. **Tombol "Tolak"** (dalam modal detail)
   - **Fungsi:** Reject transaksi
   - **Aksi:** Tampilkan form alasan penolakan, update status rejected

7. **Tombol "Export Excel"** (di header)
   - **Fungsi:** Download data transaksi dalam format Excel
   - **Aksi:** Generate file .xlsx dengan filter yang aktif

---

### D. Halaman Laporan Keuangan

**Screenshot:**
![Laporan Keuangan](path/to/screenshot/admin-laporan.png)

**Deskripsi:**
Halaman untuk melihat dan generate laporan keuangan dengan berbagai filter.

**Fitur dan Elemen:**

1. **Panel Filter**
   - **Periode:** Dropdown (Hari Ini, Minggu Ini, Bulan Ini, Custom)
   - **Jenis Laporan:** Dropdown (Pemasukan, Pengeluaran, Kas, Donasi)
   - **Kelompok:** Dropdown untuk filter berdasarkan kelompok nasabah
   - **Tombol "Generate":** Proses dan tampilkan laporan

2. **Widget Ringkasan**
   - **Total Pemasukan:** Jumlah total kas masuk
   - **Total Pengeluaran:** Jumlah total kas keluar
   - **Saldo Akhir:** Kas masuk - kas keluar
   - **Total Donasi:** Akumulasi donasi sistem

3. **Grafik Tren Keuangan**
   - **Fungsi:** Visualisasi pemasukan vs pengeluaran per periode
   - **Tipe:** Line chart atau bar chart
   - **Interaksi:** Hover untuk detail per periode

4. **Tabel Detail Laporan**
   - **Kolom sesuai jenis laporan:**
     - Tanggal
     - Deskripsi/Keterangan
     - Kategori
     - Debit/Kredit
     - Saldo
   - **Total Footer:** Summary total di bagian bawah

5. **Tombol "Export PDF"**
   - **Fungsi:** Download laporan dalam format PDF
   - **Aksi:** Generate PDF dengan header, filter info, dan tabel

6. **Tombol "Export Excel"**
   - **Fungsi:** Download laporan dalam format Excel
   - **Aksi:** Generate .xlsx untuk analisis lebih lanjut

7. **Tombol "Print"**
   - **Fungsi:** Print laporan langsung
   - **Aksi:** Buka print preview browser

---

## 4.2.3 Dashboard Pengepul

### A. Dashboard Pengepul

**Screenshot:**
![Dashboard Pengepul](path/to/screenshot/pengepul-dashboard.png)

**Deskripsi:**
Dashboard untuk pengepul menampilkan order penjemputan dan statistik performa.

**Fitur dan Elemen:**

1. **Widget Statistik**
   - **Penjemputan Hari Ini:** Jumlah order yang dikerjakan hari ini
   - **Penjemputan Selesai:** Total penjemputan yang telah complete
   - **Total Berat (kg):** Akumulasi berat sampah yang dijemput
   - **Pendapatan Bulan Ini:** Estimasi earning dari komisi

2. **Notifikasi Order Baru**
   - **Badge:** Menampilkan jumlah order pending
   - **Alert:** Notifikasi real-time jika ada order baru
   - **Tombol "Lihat Order":** Redirect ke halaman penjemputan

3. **Tabel Order Aktif**
   - **Kolom:**
     - No. Order
     - Nasabah
     - Alamat
     - Estimasi Berat
     - Status
     - Aksi
   - **Status Badge:** PENDING, ON_PROGRESS, WEIGHT_VERIFIED

4. **Kalender Jadwal**
   - **Fungsi:** Menampilkan jadwal penjemputan rutin
   - **Interaksi:** Click tanggal untuk lihat detail jadwal

---

### B. Halaman Penjemputan (FCFS)

**Screenshot:**
![Halaman Penjemputan](path/to/screenshot/pengepul-penjemputan.png)

**Deskripsi:**
Halaman untuk pengepul melihat dan mengambil order penjemputan dengan sistem FCFS.

**Fitur dan Elemen:**

1. **Tab Filter Status**
   - **PENDING:** Order yang belum diambil (available)
   - **MY ORDERS:** Order yang diambil oleh pengepul ini
   - **COMPLETED:** Order yang sudah selesai

2. **Card Order (Status PENDING)**
   - **Info Order:**
     - Kode Order: ID unik
     - Nasabah: Nama dan kontak
     - Alamat Lengkap: Lokasi penjemputan
     - Estimasi Berat: Total estimasi kg
     - Jenis Sampah: List jenis yang diminta
     - Waktu Request: Kapan order dibuat
   - **Tombol "Ambil Order":**
     - **Fungsi:** Claim order ini (FCFS mechanism)
     - **Aksi:** Database lock, assign pengepul_id, status → ASSIGNED
     - **Warning:** "Order sudah diambil orang lain" jika terlambat

3. **Card Order (Status MY ORDERS = ASSIGNED/ON_PROGRESS)**
   - **Info sama seperti PENDING**
   - **Tombol "Mulai Penjemputan":**
     - **Fungsi:** Start proses penjemputan
     - **Aksi:** Update status → ON_PROGRESS, record waktu mulai
   - **Tombol "Batalkan":**
     - **Fungsi:** Release order kembali ke PENDING
     - **Aksi:** Konfirmasi, set pengepul_id = null

4. **Card Order (Status ON_PROGRESS)**
   - **Tombol "Verifikasi Berat":**
     - **Fungsi:** Input berat aktual setelah penjemputan
     - **Aksi:** Buka modal form verifikasi berat

---

### C. Halaman Verifikasi Berat

**Screenshot:**
![Verifikasi Berat](path/to/screenshot/pengepul-verifikasi.png)

**Deskripsi:**
Modal untuk pengepul menginput berat aktual sampah yang dijemput.

**Fitur dan Elemen:**

1. **Tabel Detail Sampah**
   - **Kolom:**
     - Jenis Sampah: Nama kategori
     - Estimasi (kg): Berat yang diminta nasabah
     - Berat Aktual (kg): Input field untuk berat verifikasi
     - Harga/kg: Harga saat ini
     - Total: Auto-calculate (berat × harga)

2. **Input "Berat Aktual"** (setiap row)
   - **Fungsi:** Input berat hasil timbangan
   - **Validasi:** 
     - Tidak boleh kosong
     - Harus angka positif
     - Warning jika selisih > 20% dari estimasi

3. **Auto-Calculate Total**
   - **Fungsi:** Hitung total pembayaran secara otomatis
   - **Formula:** Σ(berat × harga) untuk semua jenis sampah

4. **Dropdown "Opsi Pembayaran"**
   - **take_all:** 100% ke nasabah
   - **donate_all:** 100% ke sistem (donasi)
   - **donate_partial:** 50% nasabah, 50% sistem

5. **Display "Total Pembayaran"**
   - **Fungsi:** Menampilkan total yang harus dibayar
   - **Split View:** Jika partial, tampilkan breakdown

6. **Tombol "Simpan & Lanjut ke Pembayaran"**
   - **Fungsi:** Simpan berat verifikasi dan proceed ke payment
   - **Aksi:** Update penjemputan_sampah_details, status → WEIGHT_VERIFIED

7. **Tombol "Batal"**
   - **Fungsi:** Cancel verifikasi, kembali tanpa save
   - **Aksi:** Close modal tanpa perubahan

---

### D. Halaman Pembayaran

**Screenshot:**
![Halaman Pembayaran](path/to/screenshot/pengepul-pembayaran.png)

**Deskripsi:**
Halaman untuk memproses pembayaran melalui Midtrans payment gateway.

**Fitur dan Elemen:**

1. **Ringkasan Order**
   - **No. Penjemputan:** Kode unik
   - **Nasabah:** Nama penerima
   - **Total Berat:** Akumulasi kg
   - **Detail Pembayaran:** Breakdown per jenis sampah

2. **Summary Pembayaran**
   - **Subtotal:** Total sebelum opsi
   - **Opsi Pembayaran:** Label (Semua/Donasi/Sebagian)
   - **Pembayaran ke Nasabah:** Nominal
   - **Donasi ke Sistem:** Nominal
   - **Grand Total:** Total yang dibayar

3. **Tombol "Proses Pembayaran"**
   - **Fungsi:** Initiate Midtrans payment
   - **Aksi:** 
     - Call MidtransService → createSnapToken()
     - Generate payment URL
     - Status → PAYMENT_PENDING
     - Redirect ke Midtrans or show Snap popup

4. **Snap Payment Popup (Midtrans)**
   - **Metode Pembayaran:**
     - QRIS: Scan & pay
     - Virtual Account: BCA, BNI, Mandiri, dll
     - E-wallet: GoPay, OVO, DANA, dll
   - **Tombol "Bayar":** Submit payment ke Midtrans

5. **Callback Handling**
   - **Success:** Midtrans webhook → status PAYMENT_PAID
   - **Pending:** Menunggu konfirmasi bank
   - **Failed:** Tampilkan error, allow retry

---

## 4.2.4 Dashboard User/Nasabah

### A. Dashboard User

**Screenshot:**
![Dashboard User](path/to/screenshot/user-dashboard.png)

**Deskripsi:**
Dashboard untuk nasabah menampilkan saldo, riwayat, dan quick action.

**Fitur dan Elemen:**

1. **Card Saldo**
   - **Total Saldo:** Jumlah saldo terkini (Rp)
   - **Badge:** Status saldo (positive/negative)
   - **Tombol "Tarik Saldo":** Request withdrawal (jika tersedia)

2. **Card Statistik Bulan Ini**
   - **Total Penjemputan:** Jumlah request bulan ini
   - **Total Berat:** Akumulasi kg sampah
   - **Total Pendapatan:** Earning dari penjualan sampah
   - **Total Donasi:** Kontribusi ke sistem

3. **Quick Actions**
   - **Tombol "Request Penjemputan Baru":**
     - **Fungsi:** Buat order penjemputan
     - **Aksi:** Redirect ke form request
   - **Tombol "Lihat Riwayat":**
     - **Fungsi:** Lihat semua transaksi
     - **Aksi:** Redirect ke halaman riwayat

4. **Tabel Penjemputan Terbaru**
   - **Kolom:**
     - Tanggal
     - Status
     - Estimasi Berat
     - Pengepul
     - Aksi
   - **Limit:** 5 data terbaru

---

### B. Halaman Request Penjemputan

**Screenshot:**
![Request Penjemputan](path/to/screenshot/user-request.png)

**Deskripsi:**
Form untuk nasabah membuat request penjemputan sampah.

**Fitur dan Elemen:**

1. **Form Request Penjemputan**
   
   **Section 1: Informasi Penjemputan**
   - **Field "Tanggal Penjemputan":**
     - **Input:** Date picker
     - **Validasi:** Min today, max 7 hari ke depan
   - **Field "Waktu Penjemputan":**
     - **Input:** Time picker
     - **Opsi:** 08:00 - 16:00
   - **Field "Alamat Lengkap":**
     - **Input:** Textarea
     - **Default:** Alamat dari profil user
     - **Fungsi:** Editable untuk alamat berbeda

   **Section 2: Detail Sampah**
   - **Tombol "+ Tambah Jenis Sampah":**
     - **Fungsi:** Add row baru untuk jenis sampah
     - **Aksi:** Insert form row baru
   
   - **Repeating Form Row:**
     - **Dropdown "Jenis Sampah":**
       - **Opsi:** Plastik, Kertas, Logam, Kaca, dll
       - **Source:** Master data JENIS_SAMPAH
     - **Field "Estimasi Berat (kg)":**
       - **Input:** Number
       - **Validasi:** Min 0.1, max 1000
     - **Display "Harga/kg":**
       - **Fungsi:** Auto-populate dari master
       - **Format:** Rp X.XXX
     - **Display "Estimasi Total":**
       - **Fungsi:** Auto-calculate (berat × harga)
       - **Format:** Rp X.XXX
     - **Tombol "Hapus" (icon trash):**
       - **Fungsi:** Remove row ini
       - **Aksi:** Delete form row

   **Section 3: Opsi Pembayaran**
   - **Radio Button Group:**
     - **Option 1:** "Terima Semua Pembayaran (100%)"
     - **Option 2:** "Donasikan Semua ke Sistem (100%)"
     - **Option 3:** "Donasikan Sebagian (50% saya, 50% sistem)"
   - **Info Box:**
     - **Fungsi:** Jelaskan opsi yang dipilih
     - **Contoh:** "Anda akan menerima Rp X.XXX"

2. **Summary Box (Sticky di kanan)**
   - **Total Estimasi Berat:** Σ semua berat
   - **Total Estimasi Nilai:** Σ semua (berat × harga)
   - **Pembayaran ke Anda:** Sesuai opsi
   - **Donasi ke Sistem:** Sesuai opsi

3. **Tombol "Batalkan"**
   - **Fungsi:** Cancel request, kembali ke dashboard
   - **Aksi:** Konfirmasi, redirect tanpa save

4. **Tombol "Submit Request"**
   - **Fungsi:** Kirim request penjemputan
   - **Aksi:** 
     - Validate form
     - Insert PENJEMPUTAN
     - Insert PENJEMPUTAN_SAMPAH_DETAILS (multiple)
     - Status → PENDING
     - Notifikasi success
     - Redirect ke dashboard

**Validasi:**
- Min 1 jenis sampah harus dipilih
- Semua jenis sampah harus unique (tidak boleh duplikat)
- Semua berat harus diisi dan > 0
- Tanggal dan waktu tidak boleh kosong

---

### C. Halaman Riwayat Transaksi

**Screenshot:**
![Riwayat Transaksi](path/to/screenshot/user-riwayat.png)

**Deskripsi:**
Halaman untuk melihat semua riwayat transaksi nasabah.

**Fitur dan Elemen:**

1. **Filter Panel**
   - **Filter Status:**
     - **Opsi:** Semua, Menunggu Verifikasi, Verified, Ditolak
     - **Fungsi:** Filter berdasarkan status verifikasi
   - **Filter Tanggal:**
     - **From - To date picker**
     - **Tombol "Apply":** Terapkan filter

2. **Tabel Riwayat Transaksi**
   - **Kolom:**
     - **Tanggal:** Waktu transaksi dibuat
     - **No. Transaksi:** Kode unik
     - **Jenis Sampah:** Kategori
     - **Berat (kg):** Berat terverifikasi
     - **Total Pembayaran:** Nominal (Rp)
     - **Status:** Badge (Pending/Verified/Rejected)
     - **Aksi:** Tombol Detail, Verifikasi (jika pending)

3. **Tombol "Lihat Detail"** (setiap row)
   - **Fungsi:** Lihat detail lengkap transaksi
   - **Aksi:** Buka modal detail transaksi

4. **Modal Detail Transaksi**
   - **Informasi Lengkap:**
     - No. Transaksi
     - No. Penjemputan (link)
     - Tanggal transaksi
     - Pengepul yang memproses
     - Jenis sampah
     - Berat verifikasi
     - Harga per kg
     - Total pembayaran
     - Opsi pembayaran yang dipilih
     - Status verifikasi
   - **Bukti Pembayaran:**
     - **Image viewer:** Tampilkan bukti transfer
     - **Tombol "Download":** Download bukti
   - **Riwayat Verifikasi:**
     - Verified by nasabah: tanggal, status
     - Verified by admin: tanggal, status

5. **Tombol "Verifikasi Transaksi"** (dalam modal, jika pending)
   - **Fungsi:** Approve atau reject transaksi
   - **Aksi:** Tampilkan confirmation dialog
   
6. **Dialog Verifikasi**
   - **Tombol "Setuju - Terima Pembayaran":**
     - **Fungsi:** Approve transaksi
     - **Aksi:** Set verified_by_nasabah = user_id, status verified
   - **Tombol "Tolak - Ajukan Koreksi":**
     - **Fungsi:** Reject dengan alasan
     - **Aksi:** Tampilkan textarea alasan → submit rejection

7. **Export Options**
   - **Tombol "Export PDF":** Download riwayat dalam PDF
   - **Tombol "Export Excel":** Download data dalam Excel

---

### D. Halaman Profil User

**Screenshot:**
![Profil User](path/to/screenshot/user-profil.png)

**Deskripsi:**
Halaman untuk mengelola data profil dan akun pengguna.

**Fitur dan Elemen:**

1. **Tab Navigation**
   - **Tab "Data Profil":** Info personal user
   - **Tab "Keamanan":** Change password
   - **Tab "Notifikasi":** Preferensi notifikasi

2. **Tab Data Profil - Form**
   - **Field "Nama Lengkap":** Input text
   - **Field "Email":** Input email (readonly/disabled)
   - **Field "Nomor Telepon":** Input phone
   - **Field "Alamat":** Textarea
   - **Dropdown "Kelompok":** Select kelompok (jika bergabung)
   - **Display "Kode Nasabah":** Auto-generated, readonly
   
   **Tombol "Update Profil":**
   - **Fungsi:** Save perubahan data
   - **Aksi:** Validate → Update NASABAH table → Success notification

3. **Tab Keamanan - Form**
   - **Field "Password Lama":** Input password
   - **Field "Password Baru":** Input password (strength indicator)
   - **Field "Konfirmasi Password Baru":** Input password
   
   **Tombol "Ubah Password":**
   - **Fungsi:** Update password
   - **Aksi:** Validate old password → Update users.password → Logout

4. **Tab Notifikasi - Form**
   - **Checkbox "Email Notifikasi Penjemputan":** On/Off
   - **Checkbox "Email Notifikasi Transaksi":** On/Off
   - **Checkbox "Email Notifikasi Promo":** On/Off
   
   **Tombol "Simpan Preferensi":**
   - **Fungsi:** Save notification settings
   - **Aksi:** Update user preferences

---

## 4.2.5 Halaman Public (Guest)

### A. Halaman Beranda (Landing Page)

**Screenshot:**
![Beranda](path/to/screenshot/public-beranda.png)

**Deskripsi:**
Halaman publik yang dapat diakses tanpa login, menampilkan informasi umum sistem.

**Fitur dan Elemen:**

1. **Hero Section**
   - **Heading:** Judul sistem "Bank Sampah Digital"
   - **Subheading:** Tagline singkat
   - **CTA Buttons:**
     - **Tombol "Daftar Sekarang":** Redirect ke register
     - **Tombol "Login":** Redirect ke login

2. **Section Statistik**
   - **Widget "Total Nasabah":** Jumlah user terdaftar
   - **Widget "Sampah Terkumpul":** Total kg sampah
   - **Widget "Donasi Tersalurkan":** Total Rp donasi
   - **Widget "Penjemputan Sukses":** Total order selesai

3. **Section Artikel Terbaru**
   - **Card Artikel (3-6 items):**
     - **Image:** Featured image
     - **Title:** Judul artikel
     - **Excerpt:** Cuplikan isi
     - **Date:** Tanggal publish
     - **Tombol "Baca Selengkapnya":** Link ke detail artikel
   - **Tombol "Lihat Semua Artikel":** Redirect ke halaman artikel

4. **Section Transparansi Donasi**
   - **Tabel/List Penggunaan Dana:**
     - Tanggal
     - Keterangan
     - Jumlah (Rp)
     - Bukti (optional)
   - **Total Saldo Donasi:** Display saldo dana tersisa

5. **Section Cara Kerja**
   - **Timeline/Steps:**
     - Step 1: Daftar akun
     - Step 2: Request penjemputan
     - Step 3: Verifikasi berat
     - Step 4: Terima pembayaran
   - **Ilustrasi:** Icon atau gambar per step

6. **Footer**
   - **Kontak:** Email, Phone, Address
   - **Social Media:** Links ke sosmed
   - **Copyright:** © 2024 Bank Sampah

---

## 4.2.6 Integrasi Payment Gateway (Midtrans)

### A. Popup Midtrans Snap

**Screenshot:**
![Midtrans Snap](path/to/screenshot/midtrans-snap.png)

**Deskripsi:**
Popup untuk memilih metode pembayaran melalui Midtrans gateway.

**Fitur dan Elemen:**

1. **Order Summary**
   - **Order ID:** Nomor transaksi
   - **Total Pembayaran:** Nominal
   - **Detail Item:** Breakdown pembayaran

2. **Payment Methods Tabs**
   - **Tab QRIS:**
     - **QR Code:** Display QR untuk scan
     - **Instructions:** Langkah pembayaran
   - **Tab Virtual Account:**
     - **Bank options:** BCA, BNI, Mandiri, Permata, dll
     - **VA Number:** Auto-generated saat pilih bank
     - **Instructions:** Cara bayar via ATM/mobile banking
   - **Tab E-Wallet:**
     - **Options:** GoPay, OVO, DANA, ShopeePay
     - **Redirect:** Link ke app masing-masing
   - **Tab Credit Card:**
     - **Form:** Card number, expiry, CVV
     - **Save card option:** Checkbox

3. **Payment Status Indicator**
   - **Pending:** Menunggu pembayaran
   - **Processing:** Sedang diproses bank
   - **Success:** Pembayaran berhasil (auto-close popup)
   - **Failed:** Gagal, tampilkan error message

4. **Tombol "Bayar"**
   - **Fungsi:** Submit payment
   - **Aksi:** Process payment → Midtrans → Webhook callback

---

## 4.2.7 Notifikasi dan Feedback

### A. Toast Notifications

**Screenshot:**
![Toast Notification](path/to/screenshot/toast-notification.png)

**Deskripsi:**
Notifikasi temporary yang muncul di pojok layar untuk feedback user action.

**Tipe Notifikasi:**

1. **Success (Hijau):**
   - Icon: ✓ Checkmark
   - Contoh: "Data berhasil disimpan"
   - Duration: 3 detik
   
2. **Error (Merah):**
   - Icon: ✗ X mark
   - Contoh: "Gagal menyimpan data"
   - Duration: 5 detik
   
3. **Warning (Kuning):**
   - Icon: ⚠ Triangle
   - Contoh: "Estimasi berat melebihi 20% dari biasanya"
   - Duration: 4 detik
   
4. **Info (Biru):**
   - Icon: ℹ Info
   - Contoh: "Order baru telah tersedia"
   - Duration: 4 detik

---

### B. Confirmation Dialogs

**Screenshot:**
![Confirmation Dialog](path/to/screenshot/confirmation.png)

**Deskripsi:**
Modal konfirmasi untuk action yang critical atau irreversible.

**Elemen:**

1. **Dialog Box**
   - **Icon:** Warning/Question icon
   - **Judul:** Judul action (contoh: "Hapus Pengguna?")
   - **Message:** Penjelasan konsekuensi
   - **Tombol "Ya, Lanjutkan":** Proceed dengan action
   - **Tombol "Batal":** Cancel action, tutup dialog

**Digunakan untuk:**
- Delete data
- Cancel order
- Reject transaksi
- Logout
- Submit form penting

---

## Kesimpulan Antarmuka

Antarmuka sistem manajemen sampah dirancang dengan prinsip:

1. **User-Friendly:** Interface intuitif menggunakan Filament 3.3
2. **Responsive:** Tampilan adaptif di desktop, tablet, dan mobile
3. **Consistent:** Desain dan pattern yang konsisten di semua halaman
4. **Accessible:** Mengikuti accessibility standards (WCAG)
5. **Feedback:** Real-time feedback untuk setiap user action

**Navigasi:**
- Sidebar navigation untuk menu utama
- Breadcrumb untuk tracking posisi
- Back button untuk kembali ke halaman sebelumnya

**Data Management:**
- Table dengan pagination, search, dan filter
- Form validation real-time
- Auto-save draft (untuk form panjang)

**Security:**
- CSRF protection di semua form
- Role-based access control (RBAC)
- Session timeout untuk keamanan

---

## Lampiran: Panduan Screenshot

**Untuk melengkapi dokumentasi ini, siapkan screenshot berikut:**

### Admin (10 screenshot):
1. ✅ Login page
2. ✅ Dashboard admin
3. ✅ Manajemen pengguna
4. ✅ Tambah/edit user (modal)
5. ✅ Verifikasi transaksi sistem
6. ✅ Detail transaksi (modal)
7. ✅ Laporan keuangan
8. ✅ Grafik laporan
9. ✅ Setting sistem
10. ✅ Log aktivitas

### Pengepul (8 screenshot):
1. ✅ Dashboard pengepul
2. ✅ Daftar penjemputan (PENDING)
3. ✅ Detail order & tombol ambil
4. ✅ My orders (ON_PROGRESS)
5. ✅ Form verifikasi berat (modal)
6. ✅ Halaman pembayaran
7. ✅ Midtrans Snap popup
8. ✅ Konfirmasi pembayaran berhasil

### User/Nasabah (6 screenshot):
1. ✅ Dashboard user
2. ✅ Form request penjemputan
3. ✅ Riwayat transaksi (tabel)
4. ✅ Detail transaksi (modal)
5. ✅ Verifikasi transaksi (dialog)
6. ✅ Profil user

### Public (3 screenshot):
1. ✅ Register page
2. ✅ Beranda/landing page
3. ✅ Halaman artikel

### Component (3 screenshot):
1. ✅ Toast notification (success/error/warning/info)
2. ✅ Confirmation dialog
3. ✅ Loading state

**Total: 30 screenshot**

---

**Format Screenshot:**
- Resolusi minimal: 1280x720 (HD)
- Format: PNG atau JPEG
- Highlight important elements (optional: dengan arrow/box merah)
- Include browser chrome jika perlu (untuk context)
- Dark mode atau light mode (konsisten)

**Cara Embed di Dokumentasi:**
```markdown
![Alt text](path/to/screenshot.png)
*Gambar 4.X: Deskripsi singkat halaman*
```

**Penamaan File Screenshot:**
```
01-login.png
02-admin-dashboard.png
03-admin-users.png
04-admin-user-modal.png
... dst
```
