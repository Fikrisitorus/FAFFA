# BAB IV  
# IMPLEMENTASI SISTEM

## 4.1 Implementasi Antarmuka Pengguna

Implementasi antarmuka pengguna pada sistem Bank Sampah dirancang dengan mempertimbangkan aspek usability dan user experience untuk memastikan sistem dapat digunakan dengan mudah oleh berbagai macam pengguna dengan latar belakang teknologi yang beragam. Antarmuka pengguna diimplementasikan menggunakan framework Laravel Blade untuk server-side rendering dan TailwindCSS untuk styling yang responsif dan modern.

Berikut adalah detail implementasi dari setiap halaman antarmuka yang terdapat dalam sistem:

---

### 4.1.1 Halaman Landing Page (Guest)

**Gambar 4.1: Tampilan Landing Page**

![Halaman Landing Page](screenshots/01-landing-page.png)

*Keterangan: Landing page menampilkan informasi umum sistem dan call-to-action untuk registrasi*

Halaman landing page merupakan halaman pertama yang diakses oleh pengunjung yang belum melakukan autentikasi. Halaman ini berfungsi sebagai portal informasi dan entry point bagi calon pengguna untuk mengenal sistem serta melakukan pendaftaran akun.

Pada bagian header terdapat navigation bar dengan desain sticky yang tetap berada di bagian atas saat pengguna melakukan scrolling. Navigation bar menampilkan logo "Bank Sampah" di sebelah kiri yang berfungsi sebagai identitas visual sistem. Bagian tengah navbar berisi menu navigasi horizontal dengan link-link yang mengarah ke berbagai section dalam halaman yang sama, yaitu "Home", "About", "Articles", "Regulation", "Location", "Transparansi", dan "Help Desk". Implementasi navigasi menggunakan smooth scrolling behavior untuk memberikan pengalaman yang lebih baik saat berpindah antar section. Di pojok kanan terdapat tombol "Login" dengan warna hijau yang mengarahkan pengguna ke halaman autentikasi. Untuk tampilan mobile, navbar dilengkapi dengan hamburger menu yang dapat di-expand untuk menampilkan menu navigasi secara vertical dengan overlay full-screen.

Section hero menampilkan headline utama "Manage Waste, Save the Environment" yang disertai dengan deskripsi singkat mengenai pentingnya pengelolaan sampah dan kontribusi terhadap lingkungan yang lebih sehat. Terdapat image ilustrasi di sebelah kiri yang memberikan visualisasi tentang proses pengelolaan sampah. Pada bagian bawah hero section terdapat dua call-to-action button utama. Tombol pertama adalah "Daftar Kelompok Nasabah" dengan background warna hijau yang mengarahkan ke route `/register/kelompok-nasabah`. Tombol kedua adalah "Daftar Pengepul" dengan background warna biru yang mengarahkan ke route `/register/pengepul`. Kedua tombol ini memberikan jalur registrasi yang berbeda sesuai dengan role yang ingin dipilih oleh calon pengguna.

Section "About Us" menjelaskan tentang EcoBank sebagai platform yang dedicated untuk mempromosikan praktik pengelolaan sampah berkelanjutan. Paragraf penjelasan ini ditulis dalam bahasa Inggris dan mendeskripsikan bahwa aplikasi ini menyediakan platform bagi individu dan komunitas untuk berpartisipasi aktif dalam daur ulang dan pengurangan sampah, serta menciptakan peluang ekonomi melalui inisiatif recycling.

Section "Educational Articles" menampilkan grid artikel dalam bentuk card layout yang responsive. Data artikel di-fetch dari database melalui variabel `$artikelTerbaru` yang dikirim dari controller. Setiap card artikel menampilkan featured image yang diambil dari storage dengan fallback ke placeholder jika gambar tidak tersedia, judul artikel yang di-truncate maksimal 50 karakter, dan excerpt yang di-limit 80 karakter. Implementasi hover effect membuat gambar akan scale up dan judul berubah warna menjadi hijau ketika cursor berada di atas card, memberikan visual feedback yang interaktif. Jika belum ada artikel yang dipublikasikan, sistem akan menampilkan pesan "Belum ada artikel tersedia" di tengah section. Di bagian bawah terdapat tombol "See All Articles" yang mengarahkan ke halaman arsip artikel lengkap dengan route `artikel.index`.

Section "Regulations and Publications" menyediakan dua item clickable yang diimplementasikan sebagai list dengan hover effect. Item pertama adalah link untuk download dokumen "Waste Management Regulations (PDF)" yang dilengkapi dengan icon download. Item kedua adalah link menuju "Sustainable Living Guide" yang dilengkapi dengan icon arrow right, mengindikasikan bahwa aksi akan membuka halaman baru.

Section "Location" menampilkan gambar maps sebagai background image dengan aspect ratio video yang responsive. Di bawah gambar ditampilkan alamat lengkap "Jl. Kebayoran Lama No. 12, Jakarta, Indonesia" dalam format text. Terdapat tombol "See Full Location" yang ketika diklik akan membuka Google Maps dalam tab baru dengan query parameter alamat tersebut menggunakan Google Maps Search API.

Section "Transparansi Donasi" merupakan salah satu fitur utama untuk memberikan transparansi pengelolaan dana donasi kepada publik. Section ini diimplementasikan dengan layout card yang menampilkan dashboard keuangan. Terdapat tiga card metric yang ditampilkan dalam grid responsive. Card pertama menampilkan "Total Donasi Masuk" yang merupakan akumulasi dana donasi yang telah masuk ke sistem, dengan data yang diambil dari variabel `$totalDonasiMasuk` yang dikirim dari controller dan ditampilkan dengan format rupiah menggunakan function `number_format()`. Card kedua menampilkan "Total Pengeluaran" yang merupakan total dana donasi yang sudah digunakan untuk berbagai program, dengan data dari variabel `$totalPengeluaran`. Card ketiga menampilkan "Saldo Donasi Saat Ini" yang merupakan selisih antara donasi masuk dan pengeluaran, dengan data dari variabel `$saldoDonasi`. Semua angka ditampilkan dengan pemisah ribuan untuk memudahkan pembacaan. Di bawah card metric terdapat info box dengan background hijau muda yang menjelaskan komitmen sistem terhadap transparansi penuh dalam pengelolaan donasi dan akuntabilitas. Section ini juga menampilkan daftar "Riwayat Pengeluaran Terbaru" yang di-loop dari variabel `$riwayatPengeluaran`. Setiap item pengeluaran ditampilkan dalam card yang berisi kategori pengeluaran dengan badge pill, tanggal dalam format "d M Y", nama pengeluaran, deskripsi yang di-truncate maksimal 100 karakter, nama penerima pengeluaran, jumlah dalam format rupiah, dan nama admin yang menyetujui pengeluaran. Jika belum ada riwayat pengeluaran, akan ditampilkan empty state dengan icon dan pesan "Belum ada riwayat pengeluaran". Di bagian bawah section terdapat tombol "Lihat Semua Transparansi Donasi" yang mengarahkan ke halaman `transparansi.index` untuk melihat data transparansi lengkap.

Section "Help Desk" menyediakan berbagai channel komunikasi untuk bantuan pengguna. Terdapat link WhatsApp dengan nomor +62 812 3456 7890 yang diimplementasikan dengan protocol `https://wa.me/` sehingga ketika diklik akan membuka aplikasi WhatsApp dengan nomor tersebut dalam tab baru. Terdapat juga link Email ke support@ecobank.com yang menggunakan protocol `mailto:` untuk membuka email client default pengguna. Di bawahnya terdapat grid icon link ke social media platforms yaitu Twitter, Facebook, dan Instagram yang ketika diklik akan membuka profil media social dalam tab baru.

Footer halaman diimplementasikan dengan layout centered yang menampilkan dua link navigasi yaitu "Privacy Policy" dan "Terms of Service" yang mengarahkan ke halaman masing-masing menggunakan route helper. Terdapat juga copyright text yang menampilkan "© 2024 EcoBank. All rights reserved." dengan tahun yang dynamic menggunakan PHP function `date('Y')` sehingga akan selalu update otomatis setiap tahunnya.

---

### 4.1.2 Halaman Account Login

**Gambar 4.2: Tampilan Halaman Login**

![Halaman Login](screenshots/02-login-page.png)

*Keterangan: Form login dengan validasi keamanan dan session management*

Halaman login merupakan halaman autentikasi yang mengimplementasikan Laravel Authentication system untuk memverifikasi identitas pengguna sebelum mengakses sistem. Halaman ini menggunakan layout guest yang telah didefinisikan dalam `layouts.guest` dan memiliki desain yang clean dengan fokus pada form autentikasi.

Form login diimplementasikan dengan HTTP method POST yang mengarah ke route `login` yang telah didefinisikan dalam routing Laravel. Form dilengkapi dengan CSRF token protection menggunakan directive `@csrf` untuk mencegah serangan Cross-Site Request Forgery. Jika terdapat session status dari server (misalnya setelah password reset), akan ditampilkan alert box dengan background hijau yang berisi pesan status tersebut.

Field pertama adalah input email dengan label "Email" yang diimplementasikan menggunakan input type email dengan HTML5 validation. Field ini memiliki attribut `required` dan `autofocus` sehingga cursor akan langsung berada di field ini saat halaman dimuat. Attribut `autocomplete="username"` digunakan untuk kompatibilitas dengan password manager browser. Field ini juga akan menampilkan old input value menggunakan helper `old('email')` jika terdapat validation error sehingga pengguna tidak perlu menginput ulang. Placeholder text "nama@email.com" memberikan contoh format email yang valid. Jika terdapat validation error untuk field email, akan ditampilkan pesan error dengan warna merah di bawah field menggunakan Blade directive `@error`.

Field kedua adalah input password dengan label "Password" yang menggunakan input type password untuk menampilkan karakter dalam bentuk masked (tersembunyi) dengan simbol bullet. Field ini juga memiliki attribut `required` dan `autocomplete="current-password"` untuk integrasi dengan password manager. Placeholder text "••••••••" memberikan visual feedback bahwa karakter akan tersembunyi. Validation error untuk field password juga akan ditampilkan jika ada.

Di bawah field password terdapat layout horizontal flex yang membagi ruang untuk dua elemen. Di sebelah kiri terdapat checkbox "Ingat saya" dengan label dalam bahasa Indonesia yang diimplementasikan dengan name `remember`. Checkbox ini menggunakan custom styling TailwindCSS dengan warna hijau untuk memberikan konsistensi visual. Jika checkbox dicentang, Laravel akan menyimpan remember token dalam cookies browser dengan durasi 5 tahun (default Laravel) sehingga pengguna tidak perlu login berulang kali. Di sebelah kanan terdapat link "Lupa password?" dengan warna hijau yang mengarahkan ke route `password.request`. Link ini hanya ditampilkan jika route tersebut terdaftar dalam sistem menggunakan conditional `Route::has('password.request')`.

Tombol submit "Masuk" diimplementasikan dengan full-width button yang memiliki background hijau (#38e07b) dan text bold berwarna hitam. Button ini dilengkapi dengan hover effect yang mengubah background menjadi lebih gelap (#2ecc71) dan focus ring untuk accessibility. Ketika tombol diklik, form akan di-submit ke backend server.

Proses autentikasi di backend menggunakan Laravel Authentication yang akan memvalidasi kredensial dengan mencocokan email dan password yang telah di-hash menggunakan bcrypt algorithm. Jika kredensial valid, sistem akan membuat session baru untuk pengguna dan menggunakan Spatie Permission package untuk mengecek role pengguna. Berdasarkan role yang dimiliki, sistem akan redirect pengguna ke dashboard yang sesuai: Admin akan diarahkan ke Filament Admin Panel melalui route `filament.admin.pages.dashboard`, Pengepul ke Dashboard Pengepul, dan Nasabah/Kelompok Nasabah ke Dashboard Nasabah. Jika kredensial tidak valid, sistem akan redirect kembali ke halaman login dengan session flash message error yang akan ditampilkan di atas form.

Seluruh proses login dilengkapi dengan security measures termasuk CSRF token validation, password hashing menggunakan bcrypt dengan cost factor 10, dan session management menggunakan secure cookies dengan httpOnly flag untuk mencegah XSS attacks. Session timeout default Laravel adalah 120 menit dan akan otomatis logout jika tidak ada aktivitas.

---

### 4.1.3 Halaman Dashboard Nasabah

**Gambar 4.3: Tampilan Dashboard Nasabah**

![Dashboard Nasabah](screenshots/03-customer-dashboard.png)

*Keterangan: Dashboard menampilkan statistik, chart trend, dan list penjemputan aktif*

[Isi dengan deskripsi detail Dashboard Nasabah sesuai kode actual...]

---

### 4.1.4 Halaman Form Request Penjemputan

**Gambar 4.4: Tampilan Form Request Penjemputan**

![Form Request Penjemputan](screenshots/04-pickup-request-form.png)

*Keterangan: Form untuk membuat permintaan penjemputan sampah dengan multiple jenis sampah*

[Isi dengan deskripsi detail Form Request yang sudah diperbaiki...]

---

### 4.1.5 Halaman Dashboard Pengepul

**Gambar 4.5: Tampilan Dashboard Pengepul**

![Dashboard Pengepul](screenshots/05-collector-dashboard.png)

*Keterangan: Dashboard pengepul dengan sistem FCFS untuk mengambil order*

[Isi dengan deskripsi detail Dashboard Pengepul...]

---

### 4.1.6 Halaman Verifikasi Berat

**Gambar 4.6: Tampilan Halaman Verifikasi Berat**

![Verifikasi Berat](screenshots/06-weight-verification.png)

*Keterangan: Form verifikasi berat sampah dengan repeater multiple jenis*

[Isi dengan deskripsi yang sudah diperbaiki...]

---

### 4.1.7 Halaman Pembayaran

**Gambar 4.7: Tampilan Halaman Pembayaran**

![Halaman Pembayaran](screenshots/07-payment-page.png)

*Keterangan: Integrasi Midtrans untuk proses pembayaran*

[Isi dengan deskripsi Pembayaran Midtrans...]

---

### 4.1.8 Halaman Dashboard Admin

**Gambar 4.8: Tampilan Dashboard Admin**

![Dashboard Admin](screenshots/08-admin-dashboard.png)

*Keterangan: Dashboard admin dengan statistik komprehensif dan charts*

[Isi dengan deskripsi Admin Dashboard...]

---

### 4.1.9 Halaman User Management

**Gambar 4.9: Tampilan Halaman User Management**

![User Management](screenshots/09-user-management.png)

*Keterangan: Manajemen pengguna dengan CRUD operations*

[Isi dengan deskripsi User Management...]

---

### 4.1.10 Halaman Verifikasi Transaksi Nasabah

**Gambar 4.10: Tampilan Verifikasi Transaksi Nasabah**

![Verifikasi Transaksi Nasabah](screenshots/10-customer-transaction-verification.png)

*Keterangan: Halaman verifikasi transaksi untuk pembayaran ke nasabah*

[Isi dengan deskripsi Verifikasi Transaksi Nasabah...]

---

### 4.1.11 Halaman Verifikasi Transaksi Sistem

**Gambar 4.11: Tampilan Verifikasi Transaksi Sistem**

![Verifikasi Transaksi Sistem](screenshots/11-admin-transaction-verification.png)

*Keterangan: Halaman verifikasi transaksi donasi sistem*

[Isi dengan deskripsi Verifikasi Transaksi Sistem...]

---

### 4.1.12 Halaman Laporan Keuangan

**Gambar 4.12: Tampilan Halaman Laporan Keuangan**

![Laporan Keuangan](screenshots/12-financial-report.png)

*Keterangan: Halaman laporan dengan filter, chart, dan export functionality*

[Isi dengan deskripsi Financial Report...]

---

## 4.2 Implementasi Fitur Keamanan

[Tambahkan section tentang keamanan sistem...]

## 4.3 Implementasi Database

[Tambahkan section tentang implementasi database...]
