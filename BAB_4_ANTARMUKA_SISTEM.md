# BAB 4: IMPLEMENTASI SISTEM
## 4.2 Antarmuka Sistem (User Interface)

Bagian ini menjelaskan implementasi antarmuka pengguna yang telah dirancang untuk sistem manajemen sampah. Setiap halaman dipaparkan secara detail mencakup fungsi dari setiap elemen, tombol, dan alur interaksi pengguna dengan sistem.

---

## a. Landing Page (Guest)

**Gambar 4.1: Landing Page**  
![Landing Page](screenshots/01-landing-page.png)

Landing page merupakan halaman pertama yang dilihat oleh pengunjung yang belum melakukan autentikasi ke dalam sistem. Halaman ini berfungsi sebagai portal informasi umum tentang sistem manajemen sampah dan menjadi pintu masuk bagi pengguna baru untuk mendaftar atau masuk ke dalam sistem.

Pada bagian atas halaman, terdapat navigation bar sticky yang menampilkan logo "Bank Sampah" dengan icon di sebelah kiri. Bagian tengah navbar berisi menu navigasi horizontal dengan link ke berbagai section halaman: **"Home"**, **"About"**, **"Articles"**, **"Regulation"**, **"Location"**, **"Transparansi"**, dan **"Help Desk"**. Ketika link menu diklik, halaman akan smooth scroll ke section yang sesuai. Di pojok kanan navbar terdapat tombol **"Login"** berwarna hijau yang mengarahkan ke halaman autentikasi. Pada tampilan mobile, navbar memiliki hamburger menu yang expand/collapse untuk menampilkan semua link navigasi.

Hero section menampilkan headline utama "Manage Waste, Save the Environment" yang disertai dengan deskripsi singkat tentang pentingnya pengelolaan sampah dan kontribusi terhadap planet yang lebih sehat. Pada bagian ini juga terdapat gambar ilustrasi yang menggambarkan proses pengelolaan sampah. Terdapat dua call-to-action buttons: tombol **"Daftar Kelompok Nasabah"** berwarna hijau yang mengarahkan ke halaman registrasi kelompok nasabah, dan tombol **"Daftar Pengepul"** berwarna biru yang mengarahkan ke halaman registrasi pengepul. Kedua tombol ini memberikan jalur cepat bagi pengguna baru untuk bergabung sesuai dengan role yang diinginkan.

Section **"About Us"** menjelaskan tentang EcoBank sebagai platform yang dedicated untuk mempromosikan praktik pengelolaan sampah berkelanjutan. Paragraf ini menerangkan bahwa aplikasi ini menyediakan platform bagi individu dan komunitas untuk berpartisipasi aktif dalam daur ulang dan pengurangan sampah, serta menciptakan peluang ekonomi melalui inisiatif daur ulang.

Bagian **"Educational Articles"** menampilkan grid card artikel yang dipublikasikan oleh admin sistem. Setiap card menampilkan featured image, judul artikel yang di-truncate maksimal 50 karakter, excerpt yang di-limit 80 karakter, dan link ke detail artikel. Ketika card di-hover, gambar akan scale up dan judul berubah warna menjadi hijau untuk memberikan feedback visual. Jika belum ada artikel, akan muncul text "Belum ada artikel tersedia". Di bagian bawah section ini, terdapat tombol **"See All Articles"** yang mengarahkan ke halaman arsip artikel lengkap.

Section **"Regulations and Publications"** menyediakan dua item yang dapat diklik. Item pertama adalah **"Download Waste Management Regulations (PDF)"** dengan icon download yang memungkinkan pengunjung mengunduh dokumen peraturan pengelolaan sampah. Item kedua adalah **"View Our Latest Publication: Sustainable Living Guide"** dengan icon arrow yang mengarahkan ke halaman publikasi.

Section **"Location"** menampilkan gambar maps dengan alamat "Jl. Kebayoran Lama No. 12, Jakarta, Indonesia". Terdapat tombol **"See Full Location"** yang ketika diklik akan membuka Google Maps dengan query alamat tersebut dalam tab baru.


Section **"Help Desk"** menyediakan kontak untuk bantuan. Terdapat link **WhatsApp** dengan nomor +62 812 3456 7890 yang ketika diklik akan membuka WhatsApp chat. Terdapat juga link **Email** ke support@ecobank.com yang membuka email client. Di bawahnya ada icon link ke social media: Twitter, Facebook, dan Instagram yang membuka profil media social di tab baru.

Footer halaman berisi dua link: **"Privacy Policy"** dan **"Terms of Service"** yang mengarahkan ke halaman masing-masing. Bagian copyright menampilkan "© 2024 EcoBank. All rights reserved." dengan tahun yang dinamis sesuai tahun saat ini.

---

## b. Account Login Page

**Gambar 4.2: Halaman Login**  
![Login Page](screenshots/02-login-page.png)

Halaman login merupakan gerbang autentikasi bagi pengguna yang telah memiliki akun untuk masuk ke dalam sistem. Halaman ini dirancang dengan layout yang clean dan fokus pada form autentikasi di bagian tengah layar dengan background gradient hijau muda.

Form login terdiri dari dua field input utama. Field pertama adalah input **"Email"** dengan label dalam bahasa Indonesia yang menerima masukan berupa alamat email yang terdaftar. Field ini memiliki placeholder "nama@email.com" dan dilengkapi dengan validasi format email HTML5. Jika pengguna memasukkan format yang tidak valid atau email/password salah, akan muncul pesan error berwarna merah di bawah field yang bersangkutan. Field kedua adalah input **"Password"** yang menampilkan karakter dalam bentuk tersembunyi (masked) dengan simbol bullet untuk keamanan. Password field memiliki placeholder "••••••••" dan required validation.

Di bawah field password, terdapat layout horizontal yang membagi dua elemen. Di sebelah kiri terdapat checkbox **"Ingat saya"** yang jika dicentang akan menyimpan session login pengguna untuk durasi yang lebih lama melalui cookie browser, sehingga pengguna tidak perlu login berulang kali. Di sebelah kanan terdapat link text **"Lupa password?"** berwarna hijau yang ketika diklik akan mengarahkan pengguna ke halaman password reset dimana pengguna dapat memasukkan email terdaftar untuk menerima link reset password.

Tombol **"Masuk"** berukuran full-width dengan background hijau (#38e07b) dan text bold berwarna hitam terletak di bagian bawah form. Ketika tombol ini diklik, sistem akan mengirimkan kredensial ke backend server untuk divalidasi menggunakan Laravel Authentication. Jika kredensial valid, sistem akan mengecek role pengguna melalui Spatie Permission dan mengarahkan ke dashboard yang sesuai. Pengguna dengan role Admin akan diarahkan ke Filament Admin Panel, role Pengepul ke Dashboard Pengepul, dan role Nasabah/Kelompok Nasabah ke Dashboard Nasabah. Jika kredensial tidak valid, sistem akan redirect kembali ke halaman login dengan session error message yang ditampilkan di atas form.

Seluruh proses login dilengkapi dengan CSRF token protection untuk mencegah serangan cross-site request forgery, dan password di-hash menggunakan bcrypt algorithm sebelum dibandingkan dengan database untuk menjaga keamanan data pengguna. Session management menggunakan cookie dengan httpOnly flag untuk mencegah XSS attacks.

---

## c. Customer Dashboard Page

**Gambar 4.3: Dashboard Nasabah**  
![Customer Dashboard](screenshots/03-customer-dashboard.png)

Dashboard nasabah merupakan halaman utama yang dilihat pengguna dengan role kelompok nasabah setelah berhasil login. Halaman ini diimplementasikan menggunakan Filament Page dengan custom view `dashboard-kelompok.blade.php` dan dirancang untuk memberikan overview komprehensif tentang status akun, statistik, dan aktivitas penjemputan.

Section widget statistik di bagian atas menampilkan empat card metric dalam grid layout yang responsive (1 kolom untuk mobile, 3 kolom untuk desktop). Card pertama menampilkan **"Total Saldo Kelompok"** dengan icon dollar dalam circle background hijau, yang menghitung jumlah total saldo dari semua nasabah yang tergabung dalam kelompok menggunakan query `$kelompok->nasabah()->sum('saldo')`. Nilai ditampilkan dalam format rupiah dengan pemisah ribuan. Card kedua menampilkan **"Saldo Individual"** dengan icon user dalam circle background kuning, yang menampilkan saldo personal dari nasabah yang sedang login menggunakan `$nasabah->saldo`. Card ketiga menampilkan **"Total Sampah"** dengan icon trash dalam circle background biru, yang menghitung akumulasi berat sampah dari semua transaksi nasabah menggunakan query sum berat dari tabel TRANSAKSI. Nilai ditampilkan dalam format kilogram dengan 1 desimal. Card keempat menampilkan **"Total Order"** dengan icon clipboard dalam circle background ungu, yang menghitung total jumlah penjemputan yang pernah dibuat oleh kelompok menggunakan count query pada tabel PENJEMPUTAN. Semua card memiliki border, shadow, dan spacing yang konsisten dengan design system Filament.

Section chart komprehensif menampilkan tiga card chart dalam grid layout 3 kolom yang menvisualisasikan trend data 6 bulan terakhir menggunakan Chart.js library. Chart pertama adalah **"Total Sampah"** dengan line chart berwarna biru yang menampilkan trend berat sampah per bulan dari data transaksi nasabah. Data di-generate oleh method `getChartSampahBulan()` yang melakukan loop 6 bulan terakhir dan sum berat transaksi per bulan. Chart kedua adalah **"Completed Orders"** dengan line chart berwarna hijau yang menampilkan jumlah penjemputan yang berhasil diselesaikan per bulan, di-generate oleh method `getChartCompletedOrdersBulan()` dengan filter status 'completed'. Chart ketiga adalah **"Pending Orders"** dengan line chart berwarna orange yang menampilkan jumlah penjemputan yang masih pending per bulan, di-generate oleh method `getChartPendingOrdersBulan()` dengan filter status 'pending'. Semua chart menggunakan konfigurasi responsive dengan maintainAspectRatio false, legend disabled, dan grid color dengan opacity rendah untuk visual yang clean. Label bulan ditampilkan dalam format singkat (Jan, Feb, Mar) dan nilai y-axis dimulai dari zero.

Section **"Penjemputan Aktif"** menampilkan list penjemputan yang masih dalam proses dengan status pending, assigned, atau on_progress. Data di-query dari tabel PENJEMPUTAN dengan eager loading relasi pengepul dan sampahDetails menggunakan `whereIn('status', ['pending', 'assigned', 'on_progress'])` dan diurutkan berdasarkan tanggal penjemputan ascending. Setiap penjemputan ditampilkan dalam card dengan border dan background color yang berbeda sesuai status: pending menggunakan yellow-50, assigned menggunakan blue-50, dan on_progress menggunakan green-50. Di bagian header card terdapat badge status dengan warna sesuai (pending=kuning, assigned=biru, on_progress=hijau) dan tanggal-waktu penjemputan dalam format "d M Y - H:i". Konten card menampilkan alamat penjemputan, nama pengepul jika sudah assigned, dan catatan jika ada. Di bawah informasi terdapat section detail sampah yang menampilkan list jenis sampah dengan berat dan estimasi harga per item. Total berat dan estimasi harga ditampilkan dalam summary box berwarna biru. Tombol aksi di bagian kanan card adalah **"Batalkan"** (warna merah) untuk order pending yang membuka modal konfirmasi pembatalan, dan **"Detail"** (warna abu) yang membuka modal detail lengkap penjemputan. Modal detail menampilkan semua informasi penjemputan termasuk status, tanggal, waktu, alamat, pengepul, dan breakdown detail sampah dengan kalkulasi harga. Jika tidak ada penjemputan aktif, akan ditampilkan empty state dengan icon clipboard, heading "Tidak ada penjemputan aktif", deskripsi, dan tombol **"Buat Penjemputan Baru"** yang mengarahkan ke form create penjemputan.

Section **"Riwayat Penjemputan"** menampilkan lima penjemputan terakhir yang sudah selesai atau dibatalkan dengan status completed atau cancelled. Data di-query dengan limit 5 dan order by tanggal descending. Setiap item ditampilkan dalam card dengan border color sesuai status: completed menggunakan green-200 dan cancelled menggunakan red-200. Header card menampilkan badge status dan tanggal-waktu. Konten menampilkan nama pengepul dan total harga jika status completed, yang dihitung dari sum semua transaksi terkait penjemputan tersebut dengan formula `berat * harga_per_kg`. Tombol aksi adalah **"Detail"** yang membuka modal detail riwayat dan **"Ulangi Order"** (warna hijau, hanya untuk completed) yang membuka modal konfirmasi untuk duplicate order dengan detail yang sama. Modal ulangi order menampilkan preview data yang akan di-copy (tanggal, waktu, alamat, catatan) dan tombol **"Buat Order Baru"** yang redirect ke halaman create dengan query parameter `?duplicate={id}` untuk pre-fill form. Jika tidak ada riwayat, ditampilkan empty state dengan icon document, heading "Belum ada riwayat penjemputan", dan deskripsi bahwa riwayat akan muncul setelah penjemputan selesai.

Section **"Aksi Cepat"** di bagian bawah dashboard menyediakan dua tombol shortcut. Tombol **"Buat Penjemputan Baru"** dengan warna primer dan icon plus yang mengarahkan ke route `filament.admin.resources.penjemputans.create` untuk membuat request penjemputan baru. Tombol **"Lihat Semua Penjemputan"** dengan warna abu dan icon clipboard yang mengarahkan ke route `filament.admin.resources.penjemputans.index` untuk melihat list lengkap semua penjemputan dengan pagination dan filter.

Navigasi sidebar menggunakan Filament navigation standard dengan menu Dashboard di posisi pertama (navigationSort = 1) yang mengarahkan ke halaman ini. Navigation icon menggunakan heroicon home dan hanya ditampilkan untuk user dengan role 'kelompok_nasabah' melalui check `shouldRegisterNavigation()`.

Modal konfirmasi pembatalan penjemputan menampilkan detail yang akan dibatalkan dalam box kuning, warning box merah dengan icon alert dan text "⚠️ Tindakan ini tidak dapat dibatalkan!", dan dua tombol yaitu **"Batal"** untuk close modal dan **"Ya, Batalkan Penjemputan"** yang submit form POST ke route `pengepul.batal` dengan CSRF token dan auto-reload setelah 1 detik menggunakan setTimeout javascript.

Seluruh halaman menggunakan Chart.js yang di-load dari CDN untuk rendering chart dengan konfigurasi yang konsisten. Data chart di-pass dari controller ke view menggunakan Blade directive `@json()` dan di-parse di javascript untuk initialization Chart instance. Semua interaksi modal menggunakan Filament Alpine.js component dengan event dispatcher untuk open/close modal menggunakan `$dispatch('open-modal')` dan `$dispatch('close-modal')`.

---

## d. Pickup Request Form Page (Customer)

**Gambar 4.4: Form Request Penjemputan**  
![Pickup Request Form](screenshots/04-pickup-request-form.png)

Halaman form request penjemputan memungkinkan nasabah untuk membuat permintaan penjemputan sampah baru. Form ini dibagi menjadi beberapa section untuk memudahkan pengisian data.

Section informasi penjemputan dimulai dengan field **"Tanggal Penjemputan"** yang menggunakan date picker component. Ketika field ini diklik, akan muncul kalender popup dimana pengguna dapat memilih tanggal. Sistem membatasi pemilihan tanggal minimal hari ini dan maksimal 7 hari ke depan untuk memastikan jadwal penjemputan realistis. Field **"Waktu Penjemputan"** menggunakan time picker dengan slot waktu yang tersedia antara jam 08:00 hingga 16:00 dengan interval 30 menit. Waktu di luar operational hours tidak dapat dipilih dan akan muncul disabled.

Field **"Alamat Lengkap"** berupa textarea yang secara default ter-populate dengan alamat yang tersimpan di profil nasabah. Pengguna dapat mengedit alamat ini jika ingin menggunakan alamat berbeda untuk penjemputan tertentu. Textarea memiliki minimum character limit 20 karakter untuk memastikan alamat yang cukup detail.

Section detail sampah memungkinkan pengguna menambahkan multiple jenis sampah dalam satu request. Terdapat tombol **"+ Tambah Jenis Sampah"** berwarna hijau dengan icon plus yang ketika diklik akan menambahkan row form baru di bawahnya. Setiap row berisi dropdown **"Jenis Sampah"** yang ter-populate dari master data JENIS_SAMPAH di database, field input **"Estimasi Berat (kg)"** yang hanya menerima angka desimal, display readonly **"Harga/kg"** yang otomatis muncul setelah jenis sampah dipilih, dan display **"Estimasi Total"** yang otomatis menghitung perkalian berat dengan harga. Di ujung kanan setiap row terdapat icon trash button berwarna merah untuk menghapus row tersebut jika tidak diperlukan.

Sistem melakukan validasi real-time saat pengguna mengisi berat. Field berat memiliki validasi minimum 0.01 kg untuk memastikan input yang valid. Sistem juga mencegah duplikasi jenis sampah dengan memfilter dropdown jenis sampah yang sudah dipilih di row lain, sehingga setiap jenis sampah hanya bisa ditambahkan satu kali dalam satu request.

Untuk pengguna admin dan pengepul, setiap row detail sampah menampilkan placeholder readonly **"Harga per kg"** dan **"Total Harga"** yang secara otomatis menghitung nilai berdasarkan jenis sampah yang dipilih dan berat yang diinput. Untuk kelompok nasabah, field harga dan total tidak ditampilkan agar fokus pada input berat sampah saja.

Section **"Catatan Tambahan"** terletak di bawah detail sampah dalam bentuk collapsible section yang defaultnya collapsed. Section ini berisi textarea untuk menambahkan catatan khusus untuk pengepul seperti informasi akses lokasi atau instruksi khusus. Field ini bersifat opsional dengan maksimal 500 karakter.

Section **"Foto Sampah"** tersedia khusus untuk kelompok nasabah dalam bentuk collapsible section yang defaultnya collapsed. Section ini memungkinkan upload foto sampah yang akan dijemput menggunakan image uploader dengan fitur image editor. File maksimal 5MB dan bersifat opsional.

Tombol **"Simpan"** berwarna primer terletak di bagian bawah form. Ketika diklik, sistem akan memvalidasi semua field yang required. Jika validasi gagal, field yang error akan ditandai dengan pesan error di bawahnya. Jika validasi berhasil, sistem akan menyimpan data ke table PENJEMPUTAN dengan status 'pending' dan PENJEMPUTAN_SAMPAH_DETAILS untuk detail setiap jenis sampah. Sistem kemudian menampilkan success notification dan redirect ke halaman list penjemputan. Untuk kelompok nasabah, alamat penjemputan otomatis mengambil dari lokasi kelompok yang tersimpan di database.

---

## e. Collector Dashboard Page (Dashboard Pengepul)

**Gambar 4.5: Dashboard Pengepul**  
![Collector Dashboard](screenshots/05-collector-dashboard.png)

Dashboard pengepul merupakan halaman utama untuk pengguna dengan role pengepul yang diimplementasikan menggunakan Filament Page dengan custom view. Halaman ini dirancang dengan fokus pada efisiensi operasional pengepul dengan menyediakan sistem First Come First Served (FCFS) untuk pengambilan order, statistik performa, shortcut akses cepat, dan analytics dashboard.

Section **"Order Tersedia"** terletak di bagian paling atas dashboard sebagai prioritas utama, menampilkan jumlah order pending yang menunggu untuk diambil dengan badge angka dan tombol refresh untuk update data real-time. Section ini merupakan core feature dashboard pengepul yang menampilkan daftar order penjemputan dengan status pending yang tersedia untuk diambil menggunakan sistem First Come First Served (FCFS). Data order pending di-fetch dari controller dengan query `Penjemputan::where('status', 'pending')->with(['kelompok', 'sampahDetails.jenisSampah'])->orderBy('tanggal_penjemputan', 'asc')` sehingga order yang lebih awal akan ditampilkan terlebih dahulu sesuai prinsip FCFS yang fair.

Setiap order ditampilkan dalam Filament card component dengan border yellow-200 dan background yellow-50 untuk memberikan visual emphasis bahwa order masih pending. Layout card menggunakan flex horizontal dengan informasi order di sebelah kiri dan tombol aksi di sebelah kanan. Di bagian header card terdapat badge status "Pending" dengan background kuning dan text yellow-800, serta tanggal-waktu penjemputan yang di-format menggunakan Carbon dengan pattern "d M Y - H:i" (contoh: "15 Jan 2024 - 10:30"). Section informasi order menampilkan nama kelompok dengan prefix **"Kelompok:"** dalam bold, alamat penjemputan lengkap dengan prefix **"Alamat:"** yang dapat berupa multi-line text, dan catatan dari kelompok jika field catatan terisi yang ditampilkan dengan conditional rendering `@if($penjemputan->catatan)`. Tombol **"Ambil Order"** terletak di bagian kanan card dengan implementasi Filament button component berukuran small, warna primary (biru), dan icon hand paper. Button diimplementasikan sebagai anchor tag yang mengarahkan ke route `pengepul.ambil-order` dengan parameter ID penjemputan. Ketika pengepul mengklik tombol ini, sistem akan melakukan HTTP request ke controller backend yang mengimplementasikan database transaction dengan pessimistic locking menggunakan method `lockForUpdate()` untuk mencegah race condition dimana dua pengepul mengambil order yang sama secara bersamaan. Jika locking berhasil acquired dan order masih berstatus pending, sistem akan update field `pengepul_id`, mengubah status menjadi ASSIGNED, dan redirect dengan flash session success notification "Order berhasil diambil!". Card yang sudah diambil akan hilang dari list pending karena query filter. Jika terjadi race condition, sistem akan menampilkan error notification "Order sudah diambil pengepul lain". Setiap order pending juga memiliki modal detail untuk melihat breakdown detail sampah per jenis dengan summary total berat dan estimasi harga. Jika tidak ada order pending (list kosong), section akan menampilkan empty state dengan SVG icon clipboard, heading "Tidak ada order pending" dengan font medium, dan deskripsi "Semua order sudah diambil atau belum ada order baru" dengan layout centered.

Section widget statistik berada di bawah section order pending dan menampilkan empat card metric dalam grid layout responsive (1 kolom untuk mobile, 4 kolom untuk desktop). Card pertama menampilkan **"Total Penjemputan"** dengan icon clipboard dalam circle background biru, yang menghitung total kumulatif seluruh penjemputan yang pernah diambil oleh pengepul menggunakan query count pada tabel PENJEMPUTAN dengan filter `pengepul_id`. Card kedua menampilkan **"Hari Ini"** dengan icon calendar dalam circle background hijau, yang menampilkan jumlah penjemputan hari ini menggunakan filter `whereDate('tanggal_penjemputan', today())`. Card ketiga menampilkan **"Total Pembayaran"** (bukan "Total Pendapatan") dengan icon dollar dalam circle background ungu, yang menghitung total pembayaran dari penjemputan completed dengan sum nilai transaksi dalam format rupiah. Card keempat menampilkan **"Upload Bukti Transaksi"** dengan badge angka menunjukkan jumlah transaksi yang masih pending untuk di-upload buktinya, berfungsi sebagai reminder untuk pengepul melengkapi dokumentasi transaksi. Semua card menggunakan design system Filament dengan border, shadow, spacing yang konsisten, dan support untuk dark mode.

Section **"Quick Actions"** berada di bawah stats cards dan menampilkan dua card navigasi dalam grid layout 2 kolom yang berfungsi sebagai shortcut akses cepat ke fitur-fitur utama workflow pengepul. Card pertama adalah shortcut **"Verifikasi Berat"** dengan icon timbangan dalam circle background orange, dilengkapi dengan label "Timbang dan verifikasi sampah" sebagai deskripsi. Card ini diimplementasikan sebagai anchor tag yang mengarahkan ke route `filament.admin.pages.verifikasi-berat`. Card kedua adalah shortcut **"Pembayaran"** dengan icon checkmark dalam circle background hijau, dilengkapi dengan label "Proses pembayaran sampah". Card mengarahkan ke route `filament.admin.pages.pembayaran`. Kedua card memiliki hover effect `hover:shadow-lg` dengan transition smooth yang memberikan visual feedback interaktif ketika cursor berada di atas card. Structure card menggunakan Filament component dengan flex layout horizontal yang menampilkan icon, text label, dan arrow icon di kanan sebagai indicator clickable.

Section **"Analytics Dashboard"** terletak di bagian bawah dashboard dan menampilkan visualisasi data analytics dalam bentuk tiga card chart yang disusun dalam grid layout responsive. Heading section menggunakan text besar "Analytics Dashboard" dengan spacing yang memisahkan dari section di atasnya. Card pertama adalah **"Total Orders"** dengan subtitle "6 bulan terakhir" yang menampilkan line chart trend jumlah total penjemputan per bulan dalam 6 bulan terakhir. Angka total kumulatif ditampilkan prominent di bagian atas card. Card kedua adalah **"Completed"** dengan subtitle "Selesai diproses" yang menampilkan chart jumlah penjemputan yang berhasil diselesaikan dengan status completed. Card ketiga adalah **"Pending"** dengan subtitle "Menunggu proses" yang menampilkan trend order pending per bulan. Semua chart menggunakan Chart.js library yang di-load dari CDN dengan konfigurasi line chart, tension 0.4 untuk smooth curve, fill area dengan opacity rendah, legend disabled, dan grid line dengan opacity rendah untuk visual yang clean. Data chart di-pass dari controller ke view menggunakan Blade directive `@json()` dan di-parse di javascript untuk initialization Chart instance. Label bulan ditampilkan dalam format singkat (Jan, Feb, Mar) dan nilai y-axis dimulai dari zero dengan grid line yang subtle.

---

## f. Details & Weight Verification Page

**Gambar 4.6: Modal Verifikasi Berat**  
![Weight Verification](screenshots/06-weight-verification.png)

Halaman verifikasi berat adalah halaman full-page (bukan modal) yang dibuka ketika pengepul mengklik tombol "Verifikasi Berat" dari dashboard atau list penjemputan. Halaman ini dirancang untuk memfasilitasi input berat aktual sampah yang berhasil dijemput dengan tampilan mobile-friendly.

Header halaman menampilkan judul "Verifikasi Berat Sampah" dengan tombol back arrow di kiri yang mengarahkan kembali ke dashboard pengepul. Di pojok kanan atas ditampilkan nama pengepul yang sedang login.

Card informasi order menampilkan detail penjemputan: nama kelompok, tanggal penjemputan, waktu, alamat lengkap, dan catatan dari kelompok jika ada. Jika kelompok sudah menimbang sendiri (field `estimasi_berat` terisi), akan muncul alert box kuning yang menampilkan estimasi berat dari kelompok dengan pesan "Kelompok sudah menimbang sendiri - mohon verifikasi dengan timbangan yang akurat".

Form verifikasi menggunakan sistem repeater untuk multiple jenis sampah. Jika kelompok sudah mengisi detail sampah saat membuat request, form akan ter-prefill dengan data tersebut dalam format readonly display untuk jenis sampah dan berat kelompok, dengan field editable **"Berat Verifikasi (kg)"** yang harus diisi pengepul. Untuk setiap jenis sampah, tersedia field **"Catatan"** (textarea opsional) dan **"Gambar"** (file upload opsional untuk foto sampah).

Pengepul dapat menambahkan jenis sampah tambahan yang tidak ada di request kelompok dengan mengklik tombol **"+ Tambah Jenis Sampah"**. Untuk item baru, pengepul memilih dari dropdown jenis sampah, mengisi berat verifikasi, dan opsional menambahkan catatan/gambar. Sistem mencegah duplikasi dengan menyembunyikan option yang sudah dipilih. Setiap item memiliki tombol **"Hapus"** (warna merah) di bagian bawah, kecuali jika hanya ada satu item.

Di bagian bawah form terdapat display real-time **"Total Berat"** yang secara otomatis menjumlahkan semua berat verifikasi yang diinput, ditampilkan dalam format kilogram dengan 1 desimal.

Tombol navigasi terletak di bagian bawah dengan border-top separator. Tombol **"Batal"** berwarna abu-abu di kiri mengarahkan kembali ke dashboard tanpa menyimpan. Tombol **"Verifikasi Berat"** berwarna biru di kanan melakukan validasi (minimum 0.1 kg per item, total berat > 0, tidak ada duplikasi jenis). Jika validasi berhasil, form di-submit dengan loading state (icon spinner + text "Memproses..."), data disimpan ke database, status penjemputan diupdate menjadi WEIGHT_VERIFIED, dan redirect ke halaman pembayaran.

---

## g. Payment Page (Payment Gateway & Donation Options)

**Gambar 4.7: Halaman Pembayaran Midtrans**  
![Payment Page](screenshots/07-payment-page.png)

Halaman pembayaran merupakan step terakhir dalam workflow penjemputan dimana pengepul memproses pembayaran melalui payment gateway Midtrans. Halaman ini menampilkan ringkasan order dan memfasilitasi inisiasi transaksi pembayaran.

Section ringkasan order di bagian atas menampilkan informasi key dengan layout card. **"No. Penjemputan"** menunjukkan kode unik order. **"Nasabah"** menampilkan nama penerima pembayaran. **"Tanggal Penjemputan"** menunjukkan kapan sampah dijemput. **"Total Berat"** adalah akumulasi dari semua berat terverifikasi dalam satuan kg.

Tabel detail pembayaran menampilkan breakdown per jenis sampah. Kolom menunjukkan jenis sampah, berat terverifikasi, harga per kg, dan total untuk masing-masing jenis. Footer tabel menampilkan grand total dari semua row.

Section summary pembayaran dibagi menjadi beberapa row dengan alignment kanan untuk nilai rupiah. Row **"Subtotal"** menampilkan total sebelum split opsi pembayaran. Row **"Opsi Pembayaran"** menampilkan label deskriptif seperti "Sebagian (50% Nasabah, 50% Sistem)" dengan badge berwarna sesuai kategori. Row **"Pembayaran ke Nasabah"** menunjukkan nominal yang akan masuk ke saldo nasabah, dihighlight dengan warna hijau. Row **"Donasi ke Sistem"** menunjukkan nominal yang akan masuk ke kas donasi, dengan warna biru. Row **"Grand Total"** menampilkan total yang harus dibayarkan dengan font size lebih besar dan bold, sama dengan subtotal karena tidak ada potongan.

Box informasi berwarna biru muda menampilkan instruksi: "Klik tombol Proses Pembayaran untuk membuka payment gateway Midtrans. Setelah pembayaran berhasil, transaksi akan otomatis tercatat."

Tombol **"Kembali"** dengan tampilan outline abu-abu terletak di kiri bawah. Ketika diklik akan kembali ke modal verifikasi berat untuk koreksi jika diperlukan, tanpa submit pembayaran. Tombol **"Proses Pembayaran"** berwarna hijau dengan icon lock terletak di kanan, berukuran lebih besar dan prominent. 

Ketika tombol Proses Pembayaran diklik, sistem akan memanggil MidtransService untuk generate Snap Token. Backend membuat payload berisi order_id (format: PAY-{penjemputan_id}-{timestamp}), gross_amount (grand total), customer details (nama, email, phone dari nasabah), dan item details per jenis sampah. Midtrans API mengembalikan snap_token yang kemudian digunakan frontend untuk membuka Snap popup. Sebelum popup dibuka, status penjemputan di-update menjadi PAYMENT_PENDING.

Snap popup Midtrans muncul sebagai modal overlay dengan background gelap. Popup menampilkan order summary di bagian atas dengan order ID dan total amount. Tab payment methods tersedia di tengah dengan pilihan **QRIS**, **Virtual Account**, **E-wallet**, dan **Credit Card**. 

Pada tab QRIS, muncul QR code yang dapat di-scan menggunakan aplikasi e-wallet atau mobile banking. Instruksi step-by-step ditampilkan di bawah QR code. QR valid selama 15 menit, setelah itu expired dan harus generate ulang.

Pada tab Virtual Account, pengepul memilih bank dari list (BCA, BNI, Mandiri, Permata, dll). Setelah bank dipilih, sistem generate VA number yang unique. VA number ditampilkan dalam format easy-to-read dengan copy button. Instruksi pembayaran via ATM dan mobile banking ditampilkan step-by-step. VA valid hingga pembayaran sukses atau maksimal 24 jam.

Pada tab E-wallet, tersedia pilihan GoPay, OVO, DANA, ShopeePay, LinkAja. Ketika salah satu dipilih dan tombol Bayar diklik, akan redirect atau deep-link ke aplikasi e-wallet yang dipilih untuk authorization pembayaran.

Pada tab Credit Card, muncul form input untuk card number (16 digit dengan auto-spacing), expiry date (MM/YY format), CVV (3 digit), dan cardholder name. Terdapat checkbox untuk save card untuk transaksi future (jika user login). Tombol Bayar akan submit ke Midtrans untuk processing.

Setelah pembayaran diproses di Midtrans (baik sukses atau gagal), Midtrans mengirim webhook notification ke endpoint `/api/midtrans/webhook` di backend sistem. Backend memverifikasi signature webhook, mengecek status transaksi, dan update status PENJEMPUTAN menjadi PAYMENT_PAID jika sukses. Sistem kemudian create records TRANSAKSI untuk setiap jenis sampah, create records KAS untuk cash flow nasabah dan sistem, update saldo NASABAH, dan jika ada donasi, create record SEDEKAH_SAMPAH. 

Setelah webhook processing selesai, Snap popup akan otomatis close dan redirect pengepul ke halaman success dengan konfirmasi "Pembayaran berhasil! Transaksi telah tercatat." Jika pembayaran gagal, muncul error notification dengan opsi untuk retry pembayaran.

---

## h. Admin Dashboard Page

**Gambar 4.8: Dashboard Admin**  
![Admin Dashboard](screenshots/08-admin-dashboard.png)

Dashboard admin merupakan central control panel yang diimplementasikan menggunakan Filament Page dengan sistem widgets modular. Halaman ini memberikan overview menyeluruh tentang kondisi sistem secara real-time untuk memudahkan admin memonitor aktivitas, statistik keuangan, dan performa operasional.

Header dashboard menampilkan judul **"Dashboard Admin - Monitoring Sistem"** dengan subtitle "Pantau seluruh aktivitas sistem dan performa aplikasi" dalam layout card putih dengan shadow. Di pojok kanan header terdapat informasi **"Terakhir diperbarui"** yang menampilkan timestamp real-time dalam format "d M Y H:i" (contoh: "04 Jan 2026 13:09") yang di-generate menggunakan `now()->format()` untuk memberikan informasi freshness data kepada admin.

Widget pertama adalah **AdminStatsOverview** yang menampilkan sepuluh card metric yang dikelompokkan secara visual berdasarkan kategori. Grup pertama adalah statistik hari ini dengan tiga card. Card **"Penjemputan Hari Ini"** menampilkan count penjemputan dengan filter `whereDate('tanggal_penjemputan', today())` dengan subtitle "Total penjemputan hari ini". Card **"Transaksi Hari Ini"** menampilkan count transaksi hari ini dengan filter `whereDate('created_at', today())` dengan subtitle "Total transaksi hari ini". Card **"Pendapatan Hari Ini"** menampilkan sum dari `berat * harga_per_kg` untuk transaksi hari ini dalam format rupiah dengan subtitle "Total pendapatan hari ini". Grup kedua adalah statistik status penjemputan dengan dua card. Card **"Penjemputan Pending"** menampilkan count penjemputan dengan status pending dengan subtitle "Menunggu konfirmasi". Card **"Penjemputan Completed"** menampilkan count penjemputan completed dengan subtitle "Sudah selesai". Grup ketiga adalah statistik keuangan sistem dengan lima card yang memiliki visual emphasis lebih prominent. Card **"Sedekah Bulan Ini"** menampilkan total sedekah sampah bulan ini (ini adalah nama field/label, meskipun di backend mungkin query dari transaksi sistem). Card **"Total Sumbangan Sistem"** menampilkan akumulasi total dana yang masuk ke sistem dari transaksi dengan flag `sistem=true` atau dari penjemputan dengan `payment_option` donate_all/donate_partial, dengan query `Transaksi::where('sistem', true)->sum('total_harga')`. Card **"Total Pengeluaran Admin"** menampilkan sum pengeluaran yang sudah approved dengan query `Pengeluaran::where('status', 'approved')->sum('jumlah')`. Card **"Saldo Sistem"** menampilkan hasil perhitungan `$totalSumbanganSistem - $totalPengeluaranAdmin` dalam format rupiah dengan subtitle "Saldo tersisa sistem". Card **"Total Saldo Nasabah"** menampilkan total saldo semua nasabah dengan query sum transaksi nasabah dalam format rupiah dengan subtitle "Total saldo nasabah". Semua card menggunakan Filament StatsOverview widget dengan icon, color coding, dan description yang konsisten.

Widget kedua adalah **AdminPenjemputanChart** yang menampilkan line chart dengan heading **"Penjemputan per Bulan (6 Bulan Terakhir)"** yang memvisualisasikan trend jumlah penjemputan dalam 6 bulan terakhir. Data di-generate oleh method `getChartPenjemputanBulan()` yang melakukan query optimized menggunakan `selectRaw` dengan DATE_FORMAT untuk grouping per bulan, filter `where('tanggal_penjemputan', '>=', now()->subMonths(5))`, dan loop untuk memastikan semua 6 bulan ditampilkan meskipun tidak ada data (nilai 0). Chart menggunakan Chart.js dengan konfigurasi line chart, tension untuk smooth curve, dan tooltip yang menampilkan detail saat hover. Label sumbu X menampilkan bulan dalam format "M Y" (contoh: "Jan 2026"), sumbu Y menampilkan count penjemputan dengan scale yang dimulai dari zero.

Widget ketiga adalah **AdminPendapatanChart** yang menampilkan line chart dengan heading **"Pendapatan per Bulan (6 Bulan Terakhir)"** yang memvisualisasikan trend total pendapatan dalam rupiah untuk 6 bulan terakhir. Data di-generate oleh method `getChartPendapatanBulan()` dengan query optimized menggunakan JOIN antara transaksi dan jenis_sampah, `selectRaw` untuk calculate `SUM(transaksi.berat * jenis_sampah.harga)`, grouping per bulan, dan loop 6 bulan. Chart menampilkan nilai dalam rupiah (akan di-format di tooltip) dengan line chart configuration yang sama seperti chart penjemputan. Visual color yang berbeda digunakan untuk distinguish dari chart sebelumnya.

Widget keempat adalah **AdminLogAktivitasTable** yang menampilkan table dengan heading **"Log Aktivitas Terbaru"** yang berisi 10 log aktivitas terakhir dari semua user di sistem. Data di-query dengan `LogAktivitas::with('user')->latest()->limit(10)` untuk eager loading relasi user dan optimize query. Table menggunakan Filament Table widget dengan kolom yang menampilkan timestamp aktivitas, nama user dengan badge role, deskripsi aktivitas, dan mungkin action button untuk view detail. Jika tidak ada logging system aktif atau belum ada aktivitas, table akan menampilkan empty state. Widget ini memberikan real-time monitoring terhadap aktivitas user di sistem untuk audit trail dan security monitoring.

Widget kelima adalah **AdminUpcomingPickupsWidget** yang menampilkan card atau table dengan heading **"Jadwal Penjemputan Mendatang"** yang berisi list penjemputan yang dijadwalkan untuk hari ini atau hari-hari mendatang dengan status pending atau assigned. Widget ini membantu admin memonitor penjemputan yang perlu attention atau koordinasi. Data di-query dari table PENJEMPUTAN dengan filter status dan tanggal penjemputan yang upcoming. Jika tidak ada jadwal mendatang, widget menampilkan empty state dengan message yang appropriate.

Seluruh dashboard menggunakan Filament Panel navigation sidebar standard di sebelah kiri dengan menu-menu resource yang sudah dikonfigurasi dalam Filament Panel. Navigation ditampilkan secara otomatis oleh Filament berdasarkan resources dan pages yang registered dengan permission check menggunakan `shouldRegisterNavigation()`. Menu utama termasuk Dashboard, resources untuk User Management, Penjemputan, Transaksi, Kelompok, Nasabah, Master Data (Jenis Sampah, Harga), Verifikasi pages, Reports, dan Settings. Setiap menu dilengkapi dengan icon heroicon dan badge count jika applicable. Dark mode support dan responsive layout dihandle secara otomatis oleh Filament framework.

---

## i. User Management Page (Admin)

**Gambar 4.9: Manajemen Pengguna**  
![User Management](screenshots/09-user-management.png)

Halaman manajemen pengguna diimplementasikan sebagai navigation group dalam Filament Panel yang berisi tiga Filament Resources terpisah. Di sidebar navigasi Filament, section **"Manajemen Pengguna"** dapat di-expand untuk menampilkan tiga sub-menu: **"Manajemen Pengguna"** (untuk manage user accounts), **"Kelompok"** (KelompokResource untuk manage kelompok nasabah), dan **"Nasabah"** (NasabahResource untuk manage data nasabah individual). Setiap sub-menu adalah Filament Resource yang terpisah dengan table, form, dan action masing-masing, memberikan admin kontrol granular terhadap setiap entitas. Ketika admin mengklik salah satu sub-menu, halaman Filament Resource standard akan dibuka dengan header menampilkan judul resource dan breadcrumb navigation menunjukkan hierarchy.

Berikut adalah deskripsi untuk resource **"Manajemen Pengguna"** (user accounts). Halaman ini memungkinkan admin untuk melakukan CRUD operations terhadap data user dan role assignment. Header halaman menampilkan judul "Manajemen Pengguna" dan breadcrumb navigation "Dashboard > Manajemen Pengguna > Manajemen Pengguna".

Tombol **"+ Tambah Pengguna Baru"** terletak di pojok kanan atas dengan warna hijau dan icon plus. Ketika diklik, muncul modal form dengan fields: Nama Lengkap (required), Email (required, unique), Password (required, min 8 char), Konfirmasi Password (required, must match), Nomor Telepon (optional), Role (required, dropdown: Admin, Pengepul, Nasabah, Kelompok Nasabah), Kelompok (conditional, muncul jika role Nasabah atau Kelompok Nasabah). Checkbox "Kirim email notifikasi" jika checked akan trigger email welcome ke user baru. Tombol **"Batal"** close modal tanpa save. Tombol **"Simpan"** validate form, jika pass akan insert ke table USERS, assign role via MODEL_HAS_ROLES, create NASABAH jika role nasabah, dan tampilkan success notification.

Section filter di atas tabel memiliki beberapa elemen. Field **"Search"** dengan icon magnifying glass memungkinkan pencarian by nama atau email. Input ini memiliki debounce 500ms sehingga query tidak execute setiap keystroke. Dropdown **"Filter Role"** dengan opsi "Semua Role", "Admin", "Pengepul", "Nasabah", "Kelompok Nasabah". Dropdown **"Status"** dengan opsi "Semua", "Aktif", "Nonaktif". Tombol **"Reset Filter"** clear semua filter dan kembali ke view default. Tombol **"Export Excel"** download daftar user sesuai filter yang aktif dalam format .xlsx.

Tabel pengguna menampilkan data dalam format responsive dengan kolom: **"Nama"** menampilkan nama lengkap user dengan avatar circle di sebelah kiri (jika ada foto profil, jika tidak ada muncul initial), kolom **"Email"** menampilkan alamat email, kolom **"Role"** menampilkan badge berwarna sesuai role (admin=merah, pengepul=hijau, nasabah=biru, kelompok=kuning), kolom **"Kelompok"** menampilkan nama kelompok jika user punya kelompok, kolom **"Status"** menampilkan toggle switch yang bisa diklik untuk activate/deactivate user, kolom **"Bergabung"** menampilkan tanggal created_at, kolom **"Aksi"** berisi icon buttons.

Icon button **"Edit"** berbentuk pensil berwarna biru yang ketika diklik membuka modal edit dengan data user ter-populate di form. Semua field bisa diubah kecuali email (readonly untuk prevent conflict). Password field opsional, jika diisi akan update password, jika kosong password tidak berubah. Tombol Simpan akan update table USERS dan related tables.

Icon button **"Hapus"** berbentuk trash berwarna merah yang ketika diklik menampilkan confirmation dialog "Apakah Anda yakin ingin menghapus {nama}? Data yang terkait dengan user ini akan terpengaruh." Tombol "Ya, Hapus" akan perform soft delete (set deleted_at), bukan hard delete, untuk preserve data integrity. Tombol "Batal" close dialog.

Icon button **"Detail"** berbentuk eye yang membuka modal view-only menampilkan informasi lengkap user termasuk timestamp created_at, updated_at, last_login, jumlah transaksi, jumlah penjemputan (jika nasabah), dan activity logs terbaru.

Pagination di bawah tabel menampilkan dropdown **"Show per page"** dengan opsi 10, 25, 50, 100. Button **"Previous"** navigate ke halaman sebelumnya. Number buttons (1 2 3 ... 10) untuk langsung jump ke halaman tertentu. Button **"Next"** navigate ke halaman berikutnya. Text "Showing X to Y of Z entries" memberikan info tentang data yang sedang ditampilkan.

---

## j. Customer Transaction Verification Page (Nasabah)

**Gambar 4.10: Verifikasi Transaksi Nasabah**  
![Customer Transaction Verification](screenshots/10-customer-verification.png)

Halaman verifikasi transaksi nasabah merupakan halaman yang diakses oleh **pengguna dengan role nasabah/kelompok nasabah** untuk memverifikasi dan menyetujui transaksi pembayaran yang telah diproses oleh pengepul. Halaman ini memberikan transparansi kepada nasabah untuk mengecek detail transaksi sebelum dana dicairkan ke saldo mereka. Nasabah dapat melihat breakdown detail sampah yang telah dijemput, berat yang terverifikasi, harga, dan total pembayaran yang akan mereka terima.

Header halaman menampilkan judul **"Verifikasi Pembayaran Nasabah"** dengan subtitle "Validasi seluruh transaksi pembayaran nasabah sebelum dana dicatat sebagai pengeluaran resmi kepada kelompok nasabah" yang menjelaskan purpose halaman. Di pojok kanan atas terdapat tombol **"Refresh"** untuk reload data terbaru dari server.

Section heading **"Daftar Pembayaran Nasabah"** menampilkan total count penjemputan yang perlu diverifikasi dalam format "(X penjemputan)" di samping heading. Data ditampilkan dalam table yang di-group berdasarkan penjemputan_id untuk memudahkan verifikasi dalam satu batch pembayaran.

Tabel pembayaran memiliki kolom: **"No"** menampilkan nomor urut row, **"Penjemputan"** menampilkan ID dalam format **"#XX"** (contoh: "#23") dengan badge kecil di bawahnya menunjukkan jumlah item dalam format **(X item)** (contoh: "(2 item)"), **"Tanggal"** menampilkan timestamp dalam format "dd/mm/yyyy HH:mm" (contoh: "03/01/2026 20:43"), **"Total Pembayaran"** menampilkan total nilai pembayaran yang merupakan sum dari semua jenis sampah dalam format rupiah (contoh: "Rp 31.000"), **"Bukti"** menampilkan status upload bukti transfer dengan text "Belum Ada" atau link "Lihat" jika bukti tersedia, **"Status"** menampilkan badge (Pending=kuning, Verified=hijau, Rejected=merah), **"Aksi"** berisi tombol verifikasi.

Setiap row penjemputan dapat di-expand untuk menampilkan **"Detail Pembayaran:"** dalam nested table breakdown dengan kolom: **"Jenis Sampah"** nama jenis sampah, **"Berat (Kg)"** berat dengan 2 desimal (contoh: "5.00"), **"Harga/Kg"** harga per kilogram dalam rupiah (contoh: "Rp 5.000"), **"Total"** hasil kalkulasi berat × harga/kg (contoh: "Rp 25.000"), **"Status"** badge status verifikasi per item. Detail breakdown ini membantu admin cross-check validitas data sebelum approve.

Tombol **"Verifikasi"** di kolom aksi berwarna biru yang ketika diklik oleh nasabah akan membuka modal konfirmasi verifikasi. Modal menampilkan detail pembayaran yang akan diverifikasi termasuk breakdown jenis sampah, total pembayaran, dan informasi pengepul yang memproses. Terdapat textarea opsional untuk **"Catatan Verifikasi"** jika nasabah ingin menambahkan notes. Tombol **"Batal"** close modal tanpa action. Tombol **"Ya, Verifikasi"** akan submit verifikasi yang akan update status transaksi menjadi VERIFIED, set field `verified_by_nasabah` dengan ID nasabah yang login, set `verified_at_nasabah` dengan timestamp current, create record di table KAS untuk credit saldo nasabah, update saldo di table NASABAH, dan trigger notification bahwa dana sudah dikonfirmasi dan akan segera masuk ke saldo.

Yang penting, **nasabah dapat menolak** transaksi jika ada ketidaksesuaian data. Terdapat tombol **"Tolak"** (warna merah atau outline merah) di kolom aksi yang ketika diklik akan membuka modal rejection. Modal rejection menampilkan form dengan textarea **wajib diisi** untuk **"Alasan Penolakan"** dimana nasabah harus memberikan catatan kenapa transaksi ditolak (contoh: "Berat tidak sesuai dengan sampah yang diserahkan", "Harga lebih rendah dari kesepakatan", "Ada jenis sampah yang tidak saya serahkan"). Field ini required dan memiliki validasi minimum character untuk memastikan nasabah memberikan alasan yang jelas dan spesifik. Tombol **"Batal"** close modal tanpa action. Tombol **"Ya, Tolak Transaksi"** berwarna merah akan submit rejection yang akan update status menjadi REJECTED, set field `verified_by_nasabah` dengan ID nasabah, menyimpan catatan alasan penolakan ke field `rejection_reason`, dan trigger notification ke pengepul dan admin dengan alasan penolakan agar dapat dilakukan investigasi atau klarifikasi. Data yang ditolak akan tetap tercatat di database untuk audit trail dan dapat di-review ulang jika ada kesalahan atau dispute resolution.

Pagination standard ditampilkan di bawah table untuk navigasi multi-page data jika transaksi yang perlu diverifikasi banyak. Halaman ini hanya menampilkan transaksi milik nasabah yang sedang login (filter by `nasabah_id` atau `kelompok_id`), sehingga nasabah tidak dapat melihat atau memverifikasi transaksi nasabah lain untuk menjaga privacy dan security.

---

## k. Admin Transaction Verification Page (Admin)

**Gambar 4.11: Verifikasi Transaksi Sistem**  
![Admin Transaction Verification](screenshots/11-admin-verification.png)

Halaman verifikasi transaksi sistem merupakan halaman yang diakses oleh **pengguna dengan role admin** untuk memverifikasi dan menyetujui transaksi donasi/dana sistem yang telah diproses. Halaman ini memberikan kontrol kepada admin untuk memvalidasi transaksi yang menggunakan dana sistem (donasi) sebelum dana dicatat sebagai pengeluaran resmi dari kas sistem. Admin dapat melihat breakdown detail sampah, berat yang terverifikasi, harga, dan alokasi dana untuk sistem (donasi).

Header halaman menampilkan judul **"Verifikasi Transaksi Sistem"** dengan subtitle "Validasi seluruh transaksi dana sistem/donasi sebelum dana dicatat sebagai pengeluaran resmi dari kas sistem" yang menjelaskan purpose halaman. Di pojok kanan atas terdapat tombol **"Refresh"** untuk reload data terbaru dari server.

Section heading **"Daftar Transaksi Sistem"** menampilkan total count penjemputan yang memiliki komponen donasi yang perlu diverifikasi dalam format "(X penjemputan)" di samping heading. Data ditampilkan dalam table yang di-group berdasarkan penjemputan_id untuk memudahkan verifikasi dalam satu batch transaksi sistem.

Tabel transaksi sistem memiliki struktur kolom yang sama dengan verifikasi nasabah: **"No"** menampilkan nomor urut row, **"Penjemputan"** menampilkan ID dalam format **"#XX"** dengan badge **(X item)**, **"Tanggal"** menampilkan timestamp dalam format "dd/mm/yyyy HH:mm", **"Total Donasi"** (bukan "Total Pembayaran") menampilkan total nilai donasi yang masuk ke kas sistem dalam format rupiah, **"Bukti"** menampilkan status upload bukti dengan text "Belum Ada" atau link "Lihat", **"Status"** menampilkan badge (Pending=kuning, Verified=hijau, Rejected=merah), **"Aksi"** berisi tombol verifikasi.

Setiap row penjemputan dapat di-expand untuk menampilkan **"Detail Transaksi Sistem:"** dalam nested table breakdown dengan kolom yang sama: **"Jenis Sampah"**, **"Berat (Kg)"** dengan 2 desimal, **"Harga/Kg"** dalam rupiah, **"Total Donasi"** hasil kalkulasi untuk komponen sistem, **"Status"** badge status verifikasi per item. Detail breakdown ini membantu admin cross-check validitas dana donasi yang masuk.

Tombol **"Verifikasi"** di kolom aksi berwarna biru yang ketika diklik oleh admin akan membuka modal konfirmasi verifikasi. Modal menampilkan detail transaksi donasi yang akan diverifikasi termasuk breakdown jenis sampah, total donasi, dan informasi kelompok donor. Terdapat textarea opsional untuk **"Catatan Verifikasi"** jika admin ingin menambahkan notes. Tombol **"Batal"** close modal tanpa action. Tombol **"Ya, Verifikasi"** akan submit verifikasi yang akan update status transaksi menjadi VERIFIED, set field `verified_by_admin` dengan ID admin yang login, set `verified_at_admin` dengan timestamp current, create atau update record di table KAS untuk mencatat dana masuk ke kas sistem, dan trigger notification bahwa dana donasi sudah dikonfirmasi dan tercatat di sistem.

Yang penting, **admin dapat menolak** transaksi donasi jika ada ketidaksesuaian atau kecurigaan fraud. Terdapat tombol **"Tolak"** (warna merah atau outline merah) di kolom aksi yang ketika diklik akan membuka modal rejection. Modal rejection menampilkan form dengan textarea **wajib diisi** untuk **"Alasan Penolakan"** dimana admin harus memberikan catatan kenapa transaksi sistem ditolak (contoh: "Data tidak valid", "Bukti pembayaran tidak sesuai", "Dugaan manipulasi data", "Perlu investigasi lebih lanjut"). Field ini required dan memiliki validasi minimum character untuk memastikan admin memberikan alasan yang jelas dan spesifik untuk audit trail. Tombol **"Batal"** close modal tanpa action. Tombol **"Ya, Tolak Transaksi"** berwarna merah akan submit rejection yang akan update status menjadi REJECTED, set field `verified_by_admin` dengan ID admin, menyimpan catatan alasan penolakan ke field `rejection_reason_admin`, dan trigger notification ke pengepul dengan alasan penolakan agar dapat dilakukan klarifikasi atau perbaikan data. Data yang ditolak akan tetap tercatat di database untuk audit trail dan compliance.

Pagination standard ditampilkan di bawah table untuk navigasi multi-page data. Halaman ini menampilkan semua transaksi sistem dari semua kelompok (tidak di-filter per kelompok) karena admin memiliki akses penuh untuk monitoring seluruh dana donasi yang masuk ke sistem untuk transparansi dan akuntabilitas pengelolaan dana publik.

---

---

## l. Financial Report Page

**Gambar 4.12: Laporan Keuangan**  
![Financial Report](screenshots/12-financial-report.png)

Halaman Laporan Admin merupakan halaman yang diakses oleh **pengguna dengan role admin** untuk memonitor pemasukan dan pengeluaran sistem secara real-time. Halaman ini diimplementasikan sebagai Filament Page dengan custom view `laporan-admin.blade.php` yang menyediakan overview finansial sistem dengan filter periode dan breakdown detail transaksi.

Header halaman menampilkan judul **"Laporan Admin - Financial Overview"** dengan subtitle "Monitor pemasukan dan pengeluaran sistem secara real-time" yang menjelaskan purpose halaman. Di pojok kanan atas terdapat informasi **"Terakhir diperbarui"** yang menampilkan timestamp dalam format "d M Y H:i" untuk menunjukkan freshness data.

Section filter periode ditampilkan dalam card putih dengan Filament form component. Dropdown **"Periode"** menyediakan quick filter dengan opsi: "Hari Ini", "Minggu Ini", "Bulan Ini" (default), "Tahun Ini", dan "Custom". Form menggunakan Filament Select component dengan reactive property sehingga perubahan nilai langsung trigger update field lain. Jika admin memilih "Custom", muncul dua field date picker: **"Tanggal Mulai"** dan **"Tanggal Selesai"** yang diimplementasikan dengan conditional visibility `visible(fn ($get) => $get('periode') === 'custom')` dan validation required untuk memastikan kedua tanggal diisi. Tombol **"Generate Laporan"** berwarna biru dengan icon refresh yang ketika diklik akan memanggil method Livewire `wire:click="generateReport"`. Method ini akan query database sesuai periode filter, aggregate data pemasukan dan pengeluaran, calculate summary values, dan render tampilan laporan.

Setelah laporan di-generate (variabel `$this->report` tidak null), ditampilkan tiga summary cards dalam grid layout responsive (1 kolom untuk mobile, 3 kolom untuk desktop). Card pertama adalah **"Total Pemasukan"** dengan icon dollar dalam circle hijau yang menampilkan total akumulasi pemasukan dalam format rupiah dengan pemisah ribuan. Value dihitung dari `sum('amount')` dari collection pemasukan yang berisi transaksi donasi (donate_all dan donate_partial), sedekah sampah, dan sumber pemasukan lain. Card kedua adalah **"Total Pengeluaran"** dengan icon wallet dalam circle merah yang menampilkan total akumulasi pengeluaran dalam format rupiah. Value dihitung dari `sum('amount')` dari collection pengeluaran yang berisi Pengeluaran Admin (approved), Penggunaan Dana, dan Kas Keluar. Card ketiga adalah **"Saldo"** dengan icon chart bars yang menampilkan hasil perhitungan `total_pemasukan - total_pengeluaran`. Background icon dan text color bersifat conditional: jika saldo positif (>= 0) menggunakan warna biru/hijau, jika negatif menggunakan warna orange/merah untuk visual warning bahwa pengeluaran melebihi pemasukan.

Table **"Pemasukan"** ditampilkan dalam card dengan heading yang menyertakan emoji 📈 dan text hijau. Table menggunakan struktur HTML standard dengan responsive wrapper dan dark mode support. Header table memiliki kolom: **"Tanggal"** menampilkan timestamp transaksi dalam format "d M Y H:i" (contoh: "03 Jan 2026 20:43"), **"Jenis"** menampilkan badge dengan background hijau dan text putih yang berisi type pemasukan (contoh: "Sumbangan Penuh", "Sumbangan Sebagian", "Sedekah Sampah"), **"Deskripsi"** menampilkan deskripsi lengkap pemasukan yang di-generate dari controller (contoh: "Sumbangan dari Kelompok Mawar"), **"Jumlah"** menampilkan nilai pemasukan dalam format rupiah dengan text hijau untuk emphasis positif income. Body table di-render menggunakan Blade loop `@forelse` untuk iterate collection pemasukan. Jika tidak ada data pemasukan dalam periode yang dipilih, ditampilkan empty state dengan text "Tidak ada data pemasukan" yang centered dengan styling gray.

Table **"Pengeluaran"** memiliki struktur similar dengan table pemasukan namun dengan perbedaan visual (badge merah, text merah) dan tambahan kolom **"Aksi"**. Header table memiliki kolom: **"Tanggal"**, **"Jenis"** (badge merah untuk "Pengeluaran Admin", "Penggunaan Dana", "Kas Keluar"), **"Deskripsi"**, **"Jumlah"** (text merah), dan **"Aksi"** yang menampilkan action buttons untuk CRUD operations. Kolom Aksi hanya menampilkan button edit dan delete jika item adalah "Pengeluaran Admin" (conditional check `isset($item['pengeluaran_id']) && $item['type'] === 'Pengeluaran Admin'`). Button edit dengan icon FontAwesome fa-edit berwarna biru yang ketika diklik memanggil Livewire method `wire:click="editPengeluaran($id)"`. Button delete dengan icon fa-trash berwarna merah yang ketika diklik menampilkan confirmation dialog JavaScript native `onclick="return confirm('Yakin hapus pengeluaran ini?')"` sebelum memanggil Livewire method `wire:click="deletePengeluaran($id)"`. Untuk item pengeluaran jenis lain (Penggunaan Dana, Kas Keluar), kolom aksi menampilkan dash "-" karena tidak editable/deletable.

Action **"Tambah Pengeluaran"** tersedia sebagai header action button di pojok kanan atas halaman yang diimplementasikan menggunakan Filament `getHeaderActions()` method. Button berwarna hijau dengan icon plus dan label "Tambah Pengeluaran". Ketika diklik, membuka Filament modal form dengan fields untuk input pengeluaran baru. Form action melakukan validasi saldo sistem sebelum menyimpan: jika saldo tidak mencukupi, tampilkan danger notification dan return tanpa save. Jika saldo cukup, create new record dengan status approved dan trigger success notification.

Halaman menggunakan Livewire reactive components sehingga setiap action (generate report, tambah/edit/delete pengeluaran) tidak require full page reload, memberikan user experience yang smooth dan responsive.

---

## Kesimpulan Antarmuka

Implementasi antarmuka sistem manajemen sampah dirancang dengan mempertimbangkan user experience, efisiensi workflow, dan kebutuhan bisnis. Setiap halaman dilengkapi dengan feedback visual yang jelas melalui toast notifications, confirmation dialogs, loading states, dan validation messages untuk memandu pengguna dalam menyelesaikan task mereka.

Konsistensi desain dijaga melalui penggunaan color scheme yang uniform, typography hierarchy yang jelas, spacing yang konsisten, dan pattern interaction yang predictable. Button styling mengikuti prinsip: primary actions berwarna hijau atau biru dengan tampilan solid, secondary actions outline, dan destructive actions merah.

Responsive design memastikan semua halaman dapat diakses dengan baik di berbagai ukuran layar dari desktop, tablet, hingga mobile. Sidebar navigation collapse menjadi hamburger menu di mobile. Tabel menggunakan horizontal scroll atau card view di layar kecil.

Security measures terimplementasi di level UI seperti CSRF token di semua form, password masking dengan toggle visibility, session timeout dengan warning, dan confirmation dialog untuk critical actions. Role-based UI rendering memastikan user hanya melihat menu dan fitur yang sesuai dengan permission mereka.

Performance optimization dilakukan melalui lazy loading images, debounce pada search input, pagination untuk large datasets, dan asynchronous loading untuk data-heavy components. Error handling graceful dengan user-friendly error messages dan option untuk retry failed actions.
