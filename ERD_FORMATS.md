# ERD - Format Copy-Paste untuk Berbagai Tools

## 1. Format dbdiagram.io (dbml)

Copy-paste ke: https://dbdiagram.io/

```dbml
// ERD Sistem Manajemen Sampah

Table users {
  id integer [primary key]
  name varchar
  email varchar [unique]
  email_verified_at timestamp
  password varchar
  phone varchar
  address text
  is_active boolean [default: true]
  is_verified boolean [default: false]
  kelompok_id integer
  created_at timestamp
  updated_at timestamp
}

Table kelompok {
  id integer [primary key]
  nama varchar
  kode varchar [unique]
  deskripsi text
  koordinator varchar
  lokasi text
  jadwal_rutin json [note: 'Array jadwal rutin']
  is_active boolean [default: true]
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp
}

Table nasabah {
  id integer [primary key]
  user_id integer
  kelompok_id integer
  kode_nasabah varchar [unique]
  nama varchar
  email varchar
  phone varchar [not null]
  address text [not null]
  tanggal_bergabung date [not null]
  saldo decimal [default: 0, note: 'Saldo nasabah']
  is_active boolean [default: true]
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp
}

Table penjemputan {
  id integer [primary key]
  nasabah_id integer [not null]
  kelompok_id integer
  pengepul_id integer
  jadwal_pengepul_id integer
  tanggal_penjemputan date [not null]
  waktu_penjemputan timestamp [not null]
  jadwal_penjemputan timestamp
  alamat_penjemputan text [not null]
  catatan text
  gambar varchar
  status varchar [default: 'pending', note: 'pending, assigned, on_progress, weight_verified, completed, cancelled']
  is_sorted boolean [default: false]
  is_request_khusus boolean [default: false]
  waktu_diambil timestamp
  waktu_mulai timestamp
  waktu_selesai timestamp
  waktu_dibatalkan timestamp
  payment_option varchar
  donation_amount decimal
  nasabah_amount decimal
  estimasi_berat decimal
  berat_final decimal
  weight_status varchar
  self_weighted boolean [default: false]
  berat_difference decimal
  weight_notes text
  midtrans_order_id varchar
  payment_status varchar
  payment_method varchar
  payment_time timestamp
  total_amount decimal
  created_at timestamp
  updated_at timestamp
}

Table jadwal_pengepul {
  id integer [primary key]
  pengepul_id integer [not null]
  hari varchar [not null]
  waktu_mulai timestamp [not null]
  waktu_selesai timestamp [not null]
  lokasi varchar [not null]
  catatan text
  created_at timestamp
  updated_at timestamp
}

Table penjemputan_sampah_details {
  id integer [primary key]
  penjemputan_id integer [not null]
  jenis_sampah_id integer [not null]
  berat_nasabah decimal [default: 0]
  berat_verifikasi decimal
  catatan text
  gambar varchar
  created_at timestamp
  updated_at timestamp
}

Table transaksi {
  id integer [primary key]
  nasabah_id integer [not null]
  pengepul_id integer [not null]
  penjemputan_id integer
  jenis_sampah_id integer [not null]
  berat decimal [not null]
  total_harga decimal [not null]
  tanggal_transaksi timestamp [not null]
  catatan text
  sistem boolean [default: false]
  nasabah boolean [default: false]
  gambar_bukti_nasabah varchar
  gambar_bukti_sistem varchar
  bukti_pembayaran varchar
  verified_by_nasabah integer
  verified_by_admin integer
  verified_at_nasabah timestamp
  verified_at_admin timestamp
  status integer [default: 0, note: '0=pending, 1=verified, 99=rejected']
  alasan_penolakan text
  created_at timestamp
  updated_at timestamp
}

Table jenis_sampah {
  id integer [primary key]
  nama varchar [not null]
  kategori varchar [note: 'plastik, kertas, logam, kaca, lainnya']
  deskripsi text
  satuan varchar [note: 'kg, gram, pcs, liter']
  harga decimal
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp
}

Table harga_sampah {
  id integer [primary key]
  jenis_sampah_id integer [not null]
  harga decimal [not null]
  tanggal_berlaku date [not null]
  is_active boolean [default: true]
  created_at timestamp
  updated_at timestamp
}

Table sedekah_sampah {
  id integer [primary key]
  transaksi_id integer [not null]
  nasabah_id integer [not null]
  kelompok_id integer
  jumlah_sedekah decimal [not null]
  persentase decimal [default: 50]
  tanggal_sedekah timestamp [not null]
  bulan_sedekah integer [note: '1-12']
  tahun_sedekah integer
  keterangan text
  status varchar [default: 'pending', note: 'pending, approved, used']
  created_at timestamp
  updated_at timestamp
}

Table kas {
  id integer [primary key]
  nasabah_id integer
  transaksi_id integer
  sedekah_sampah_id integer
  tipe varchar [not null, note: 'masuk, keluar']
  jumlah decimal [not null]
  deskripsi varchar [not null]
  tanggal timestamp [not null]
  saldo_sebelum decimal [default: 0]
  saldo_sesudah decimal [default: 0]
  created_at timestamp
  updated_at timestamp
}

Table notifications {
  id integer [primary key]
  user_id integer
  nasabah_id integer
  type varchar [not null, note: 'transaksi, penjemputan, sedekah, system']
  title varchar [not null]
  message text [not null]
  data json
  is_read boolean [default: false]
  read_at timestamp
  created_at timestamp
  updated_at timestamp
}

Table log_aktivitas {
  id integer [primary key]
  user_id integer
  activity varchar [not null]
  description text [not null]
  ip_address varchar
  user_agent text
  created_at timestamp
  updated_at timestamp
}

Table artikel {
  id integer [primary key]
  author_id integer [not null]
  title varchar [not null]
  slug varchar [unique, not null]
  content text [not null]
  excerpt text
  featured_image varchar
  is_published boolean [default: false]
  published_at timestamp
  tags json
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp
}

Table settings {
  id integer [primary key]
  key varchar [unique, not null]
  label varchar
  value text [not null]
  type varchar [default: 'string', note: 'string, number, boolean, json, text']
  group varchar
  description text
  is_public boolean [default: false]
  created_at timestamp
  updated_at timestamp
}

Table pengeluaran {
  id integer [primary key]
  kategori varchar [not null]
  nama_pengeluaran varchar [not null]
  deskripsi text
  jumlah decimal [not null]
  tanggal_pengeluaran date [not null]
  metode_pembayaran varchar [default: 'tunai']
  bukti_pembayaran varchar
  status varchar [default: 'pending']
  approved_by integer
  approved_at timestamp
  catatan_approval text
  created_at timestamp
  updated_at timestamp
}

Table penggunaan_danas {
  id integer [primary key]
  tanggal_penggunaan date [not null]
  kategori varchar [not null]
  deskripsi text [not null]
  jumlah_pengeluaran decimal [not null]
  bukti_pengeluaran varchar
  created_by integer [not null]
  created_at timestamp
  updated_at timestamp
}

// Spatie Permission Tables
Table permissions {
  id integer [primary key]
  name varchar [not null]
  guard_name varchar [not null]
  created_at timestamp
  updated_at timestamp
}

Table roles {
  id integer [primary key]
  name varchar [not null]
  guard_name varchar [not null]
  created_at timestamp
  updated_at timestamp
}

Table model_has_permissions {
  permission_id integer [primary key]
  model_type varchar [primary key]
  model_id integer [primary key]
}

Table model_has_roles {
  role_id integer [primary key]
  model_type varchar [primary key]
  model_id integer [primary key]
}

Table role_has_permissions {
  permission_id integer [primary key]
  role_id integer [primary key]
}

// Relationships
Ref user_nasabah: users.id > nasabah.user_id
Ref user_kelompok: users.kelompok_id > kelompok.id
Ref nasabah_kelompok: nasabah.kelompok_id > kelompok.id
Ref penjemputan_nasabah: penjemputan.nasabah_id > nasabah.id [note: 'Nasabah yang meminta penjemputan']
Ref penjemputan_kelompok: penjemputan.kelompok_id > kelompok.id
Ref penjemputan_pengepul: penjemputan.pengepul_id > users.id [note: 'User dengan role pengepul yang melakukan penjemputan']
Ref penjemputan_jadwal: penjemputan.jadwal_pengepul_id > jadwal_pengepul.id
Ref jadwal_pengepul_user: jadwal_pengepul.pengepul_id > users.id [note: 'User dengan role pengepul']
Ref detail_penjemputan: penjemputan_sampah_details.penjemputan_id > penjemputan.id
Ref detail_jenis_sampah: penjemputan_sampah_details.jenis_sampah_id > jenis_sampah.id
Ref transaksi_nasabah: transaksi.nasabah_id > nasabah.id
Ref transaksi_pengepul: transaksi.pengepul_id > users.id [note: 'User dengan role pengepul yang melakukan pembayaran']
Ref transaksi_penjemputan: transaksi.penjemputan_id > penjemputan.id
Ref transaksi_jenis_sampah: transaksi.jenis_sampah_id > jenis_sampah.id
Ref harga_jenis_sampah: harga_sampah.jenis_sampah_id > jenis_sampah.id
Ref sedekah_transaksi: sedekah_sampah.transaksi_id > transaksi.id
Ref sedekah_nasabah: sedekah_sampah.nasabah_id > nasabah.id
Ref sedekah_kelompok: sedekah_sampah.kelompok_id > kelompok.id
Ref kas_nasabah: kas.nasabah_id > nasabah.id
Ref kas_transaksi: kas.transaksi_id > transaksi.id
Ref kas_sedekah: kas.sedekah_sampah_id > sedekah_sampah.id
Ref notif_user: notifications.user_id > users.id
Ref notif_nasabah: notifications.nasabah_id > nasabah.id
Ref log_user: log_aktivitas.user_id > users.id
Ref artikel_author: artikel.author_id > users.id
Ref pengeluaran_approver: pengeluaran.approved_by > users.id
Ref penggunaan_creator: penggunaan_danas.created_by > users.id
Ref model_permissions: model_has_permissions.permission_id > permissions.id
Ref model_roles: model_has_roles.role_id > roles.id
Ref role_permissions: role_has_permissions.permission_id > permissions.id
Ref role_permissions_role: role_has_permissions.role_id > roles.id
```

---

## 2. Format SQL CREATE TABLE

Copy-paste ke SQL editor atau dokumentasi:

```sql
-- ERD Sistem Manajemen Sampah
-- Format SQL CREATE TABLE

-- USERS
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NULL,
    address TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_verified BOOLEAN DEFAULT FALSE,
    kelompok_id BIGINT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (kelompok_id) REFERENCES kelompok(id) ON DELETE SET NULL
);

-- KELOMPOK
CREATE TABLE kelompok (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    kode VARCHAR(255) UNIQUE NOT NULL,
    deskripsi TEXT NULL,
    koordinator VARCHAR(255) NULL,
    lokasi TEXT NULL,
    jadwal_rutin JSON NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- NASABAH
CREATE TABLE nasabah (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    kelompok_id BIGINT NULL,
    kode_nasabah VARCHAR(255) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    tanggal_bergabung DATE NOT NULL,
    saldo DECIMAL(15,2) DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (kelompok_id) REFERENCES kelompok(id) ON DELETE SET NULL
);

-- PENJEMPUTAN
CREATE TABLE penjemputan (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nasabah_id BIGINT NOT NULL,
    kelompok_id BIGINT NULL,
    pengepul_id BIGINT NULL,
    jadwal_pengepul_id BIGINT NULL,
    tanggal_penjemputan DATE NOT NULL,
    waktu_penjemputan DATETIME NOT NULL,
    jadwal_penjemputan DATETIME NULL,
    alamat_penjemputan TEXT NOT NULL,
    catatan TEXT NULL,
    gambar VARCHAR(255) NULL,
    status ENUM('pending', 'assigned', 'on_progress', 'weight_verified', 'completed', 'cancelled') DEFAULT 'pending',
    is_sorted BOOLEAN DEFAULT FALSE,
    is_request_khusus BOOLEAN DEFAULT FALSE,
    waktu_diambil DATETIME NULL,
    waktu_mulai DATETIME NULL,
    waktu_selesai DATETIME NULL,
    waktu_dibatalkan DATETIME NULL,
    payment_option VARCHAR(255) NULL,
    donation_amount DECIMAL(15,2) NULL,
    nasabah_amount DECIMAL(15,2) NULL,
    estimasi_berat DECIMAL(8,2) NULL,
    berat_final DECIMAL(8,2) NULL,
    weight_status VARCHAR(255) NULL,
    self_weighted BOOLEAN DEFAULT FALSE,
    berat_difference DECIMAL(8,2) NULL,
    weight_notes TEXT NULL,
    midtrans_order_id VARCHAR(255) NULL,
    payment_status VARCHAR(255) NULL,
    payment_method VARCHAR(255) NULL,
    payment_time DATETIME NULL,
    total_amount DECIMAL(15,2) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE CASCADE,
    FOREIGN KEY (kelompok_id) REFERENCES kelompok(id) ON DELETE SET NULL,
    FOREIGN KEY (pengepul_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (jadwal_pengepul_id) REFERENCES jadwal_pengepul(id) ON DELETE SET NULL
);

-- JADWAL_PENGEPUL
CREATE TABLE jadwal_pengepul (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    pengepul_id BIGINT NOT NULL,
    hari VARCHAR(255) NOT NULL,
    waktu_mulai DATETIME NOT NULL,
    waktu_selesai DATETIME NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pengepul_id) REFERENCES users(id) ON DELETE CASCADE
);

-- PENJEMPUTAN_SAMPAH_DETAILS
CREATE TABLE penjemputan_sampah_details (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    penjemputan_id BIGINT NOT NULL,
    jenis_sampah_id BIGINT NOT NULL,
    berat_nasabah DECIMAL(8,2) DEFAULT 0,
    berat_verifikasi DECIMAL(8,2) NULL,
    catatan TEXT NULL,
    gambar VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (penjemputan_id) REFERENCES penjemputan(id) ON DELETE CASCADE,
    FOREIGN KEY (jenis_sampah_id) REFERENCES jenis_sampah(id) ON DELETE CASCADE
);

-- TRANSAKSI
CREATE TABLE transaksi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nasabah_id BIGINT NOT NULL,
    pengepul_id BIGINT NOT NULL,
    penjemputan_id BIGINT NULL,
    jenis_sampah_id BIGINT NOT NULL,
    berat DECIMAL(8,2) NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    tanggal_transaksi DATETIME NOT NULL,
    catatan TEXT NULL,
    sistem BOOLEAN DEFAULT FALSE,
    nasabah BOOLEAN DEFAULT FALSE,
    gambar_bukti_nasabah VARCHAR(255) NULL,
    gambar_bukti_sistem VARCHAR(255) NULL,
    bukti_pembayaran VARCHAR(255) NULL,
    verified_by_nasabah INT NULL,
    verified_by_admin INT NULL,
    verified_at_nasabah DATETIME NULL,
    verified_at_admin DATETIME NULL,
    status INT DEFAULT 0,
    alasan_penolakan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE CASCADE,
    FOREIGN KEY (pengepul_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (penjemputan_id) REFERENCES penjemputan(id) ON DELETE SET NULL,
    FOREIGN KEY (jenis_sampah_id) REFERENCES jenis_sampah(id) ON DELETE CASCADE
);

-- JENIS_SAMPAH
CREATE TABLE jenis_sampah (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    kategori ENUM('plastik', 'kertas', 'logam', 'kaca', 'lainnya') NOT NULL,
    deskripsi TEXT NULL,
    satuan ENUM('kg', 'gram', 'pcs', 'liter') NOT NULL,
    harga DECIMAL(10,2) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- HARGA_SAMPAH
CREATE TABLE harga_sampah (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    jenis_sampah_id BIGINT NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    tanggal_berlaku DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (jenis_sampah_id) REFERENCES jenis_sampah(id) ON DELETE CASCADE
);

-- SEDEKAH_SAMPAH
CREATE TABLE sedekah_sampah (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    transaksi_id BIGINT NOT NULL,
    nasabah_id BIGINT NOT NULL,
    kelompok_id BIGINT NULL,
    jumlah_sedekah DECIMAL(15,2) NOT NULL,
    persentase DECIMAL(5,2) DEFAULT 50,
    tanggal_sedekah DATETIME NOT NULL,
    bulan_sedekah TINYINT NOT NULL,
    tahun_sedekah YEAR NOT NULL,
    keterangan TEXT NULL,
    status ENUM('pending', 'approved', 'used') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE CASCADE,
    FOREIGN KEY (kelompok_id) REFERENCES kelompok(id) ON DELETE SET NULL
);

-- KAS
CREATE TABLE kas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nasabah_id BIGINT NULL,
    transaksi_id BIGINT NULL,
    sedekah_sampah_id BIGINT NULL,
    tipe ENUM('masuk', 'keluar') NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    deskripsi VARCHAR(255) NOT NULL,
    tanggal DATETIME NOT NULL,
    saldo_sebelum DECIMAL(15,2) DEFAULT 0,
    saldo_sesudah DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE SET NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE SET NULL,
    FOREIGN KEY (sedekah_sampah_id) REFERENCES sedekah_sampah(id) ON DELETE SET NULL
);

-- NOTIFICATIONS
CREATE TABLE notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    nasabah_id BIGINT NULL,
    type ENUM('transaksi', 'penjemputan', 'sedekah', 'system') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (nasabah_id) REFERENCES nasabah(id) ON DELETE CASCADE
);

-- LOG_AKTIVITAS
CREATE TABLE log_aktivitas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    activity VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    ip_address VARCHAR(255) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ARTIKEL
CREATE TABLE artikel (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    author_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT NULL,
    featured_image VARCHAR(255) NULL,
    is_published BOOLEAN DEFAULT FALSE,
    published_at DATETIME NULL,
    tags JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- SETTINGS
CREATE TABLE settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    key VARCHAR(255) UNIQUE NOT NULL,
    label VARCHAR(255) NULL,
    value TEXT NOT NULL,
    type ENUM('string', 'number', 'boolean', 'json', 'text') DEFAULT 'string',
    group VARCHAR(255) NULL,
    description TEXT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- PENGELUARAN
CREATE TABLE pengeluaran (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    kategori VARCHAR(255) NOT NULL,
    nama_pengeluaran VARCHAR(255) NOT NULL,
    deskripsi TEXT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    tanggal_pengeluaran DATE NOT NULL,
    metode_pembayaran VARCHAR(255) DEFAULT 'tunai',
    bukti_pembayaran VARCHAR(255) NULL,
    status VARCHAR(255) DEFAULT 'pending',
    approved_by BIGINT NULL,
    approved_at DATETIME NULL,
    catatan_approval TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);

-- PENGGUNAAN_DANAS
CREATE TABLE penggunaan_danas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tanggal_penggunaan DATE NOT NULL,
    kategori VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    jumlah_pengeluaran DECIMAL(15,2) NOT NULL,
    bukti_pengeluaran VARCHAR(255) NULL,
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- PERMISSIONS (Spatie Permission)
CREATE TABLE permissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (name, guard_name)
);

-- ROLES (Spatie Permission)
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (name, guard_name)
);

-- MODEL_HAS_PERMISSIONS (Spatie Permission)
CREATE TABLE model_has_permissions (
    permission_id BIGINT NOT NULL,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT NOT NULL,
    PRIMARY KEY (permission_id, model_id, model_type),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- MODEL_HAS_ROLES (Spatie Permission)
CREATE TABLE model_has_roles (
    role_id BIGINT NOT NULL,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT NOT NULL,
    PRIMARY KEY (role_id, model_id, model_type),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- ROLE_HAS_PERMISSIONS (Spatie Permission)
CREATE TABLE role_has_permissions (
    permission_id BIGINT NOT NULL,
    role_id BIGINT NOT NULL,
    PRIMARY KEY (permission_id, role_id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);
```

---

## 3. Format Text Sederhana (Untuk Dokumentasi)

```
ERD SISTEM MANAJEMEN SAMPAH
============================

TABEL UTAMA:
------------
1. users (Pengguna: Admin, Pengepul, Nasabah, Kelompok)
   ├─ id (PK)
   ├─ name, email, password
   ├─ kelompok_id (FK → kelompok)
   └─ Relasi: nasabah, penjemputan (sebagai pengepul), transaksi (sebagai pengepul), jadwal_pengepul, artikel, log_aktivitas

2. kelompok (Kelompok Nasabah)
   ├─ id (PK)
   ├─ nama, kode (UK)
   └─ Relasi: nasabah, penjemputan, sedekah_sampah, users

3. nasabah (Nasabah)
   ├─ id (PK)
   ├─ user_id (FK → users)
   ├─ kelompok_id (FK → kelompok)
   ├─ kode_nasabah (UK)
   └─ Relasi: penjemputan (meminta), transaksi (menerima), sedekah_sampah, notifications, kas

4. penjemputan (Penjemputan Sampah)
   ├─ id (PK)
   ├─ nasabah_id (FK → nasabah) [nasabah yang meminta]
   ├─ pengepul_id (FK → users) [pengepul yang melakukan]
   ├─ jadwal_pengepul_id (FK → jadwal_pengepul)
   ├─ status: pending, assigned, on_progress, weight_verified, completed, cancelled
   └─ Relasi: transaksi, penjemputan_sampah_details

5. jadwal_pengepul (Jadwal Pengepul)
   ├─ id (PK)
   ├─ pengepul_id (FK → users)
   └─ Relasi: penjemputan

6. penjemputan_sampah_details (Detail Sampah Penjemputan)
   ├─ id (PK)
   ├─ penjemputan_id (FK → penjemputan)
   ├─ jenis_sampah_id (FK → jenis_sampah)
   └─ berat_nasabah, berat_verifikasi

7. transaksi (Transaksi Pembayaran)
   ├─ id (PK)
   ├─ nasabah_id (FK → nasabah)
   ├─ pengepul_id (FK → users)
   ├─ penjemputan_id (FK → penjemputan)
   ├─ jenis_sampah_id (FK → jenis_sampah)
   ├─ status: 0 (pending), 1 (verified), 99 (rejected)
   └─ Relasi: sedekah_sampah, kas

8. jenis_sampah (Master Jenis Sampah)
   ├─ id (PK)
   ├─ kategori: plastik, kertas, logam, kaca, lainnya
   └─ Relasi: transaksi, penjemputan_sampah_details, harga_sampah

9. harga_sampah (Riwayat Harga Sampah)
   ├─ id (PK)
   └─ jenis_sampah_id (FK → jenis_sampah)

10. sedekah_sampah (Sedekah Sampah)
    ├─ id (PK)
    ├─ transaksi_id (FK → transaksi)
    ├─ nasabah_id (FK → nasabah)
    ├─ kelompok_id (FK → kelompok)
    ├─ status: pending, approved, used
    └─ Relasi: kas

11. kas (Cash Flow)
    ├─ id (PK)
    ├─ nasabah_id (FK → nasabah)
    ├─ transaksi_id (FK → transaksi)
    ├─ sedekah_sampah_id (FK → sedekah_sampah)
    └─ tipe: masuk, keluar

12. notifications (Notifikasi)
    ├─ id (PK)
    ├─ user_id (FK → users)
    ├─ nasabah_id (FK → nasabah)
    └─ type: transaksi, penjemputan, sedekah, system

13. log_aktivitas (Log Aktivitas)
    ├─ id (PK)
    └─ user_id (FK → users)

14. artikel (Artikel/Blog)
    ├─ id (PK)
    └─ author_id (FK → users)

15. settings (Pengaturan Sistem)
    ├─ id (PK)
    └─ key (UK)

16. pengeluaran (Pengeluaran Sistem)
    ├─ id (PK)
    └─ approved_by (FK → users)

17. penggunaan_danas (Penggunaan Dana)
    ├─ id (PK)
    └─ created_by (FK → users)

TABEL PERMISSION (Spatie Permission):
--------------------------------------
18. permissions (Permissions/Izin)
    ├─ id (PK)
    └─ name, guard_name (UK)

19. roles (Roles/Peran)
    ├─ id (PK)
    └─ name, guard_name (UK)

20. model_has_permissions (User Permissions)
    ├─ permission_id (PK, FK → permissions)
    ├─ model_type, model_id (PK)

21. model_has_roles (User Roles)
    ├─ role_id (PK, FK → roles)
    ├─ model_type, model_id (PK)

22. role_has_permissions (Role Permissions)
    ├─ permission_id (PK, FK → permissions)
    └─ role_id (PK, FK → roles)

RELASI UTAMA:
-------------
users → nasabah (1:1)
users → penjemputan (1:N sebagai pengepul)
users → transaksi (1:N sebagai pengepul)
kelompok → nasabah (1:N)
nasabah → penjemputan (1:N) [nasabah meminta penjemputan] [nasabah meminta penjemputan]
penjemputan → transaksi (1:N)
penjemputan → penjemputan_sampah_details (1:N)
transaksi → sedekah_sampah (1:1)
transaksi → kas (1:N)
sedekah_sampah → kas (1:N)
users → roles (N:M melalui model_has_roles)
users → permissions (N:M melalui model_has_permissions)
roles → permissions (N:M melalui role_has_permissions)
```

---

## 4. Format CSV (Untuk Import ke Tools)

```csv
Table,Primary Key,Foreign Keys,Relations,Description
users,id,"kelompok_id → kelompok.id","hasOne: nasabah, hasMany: penjemputan (sebagai pengepul), transaksi (sebagai pengepul), jadwal_pengepul, artikel, log_aktivitas","Tabel pengguna sistem (Admin, Pengepul, Nasabah, Kelompok)"
kelompok,id,,hasMany: nasabah, penjemputan, sedekah_sampah, users,"Tabel kelompok nasabah"
nasabah,id,"user_id → users.id, kelompok_id → kelompok.id","hasMany: penjemputan (meminta), transaksi (menerima), sedekah_sampah, notifications, kas","Tabel nasabah yang meminta penjemputan"
penjemputan,id,"nasabah_id → nasabah.id (meminta), pengepul_id → users.id (melakukan), jadwal_pengepul_id → jadwal_pengepul.id","hasMany: transaksi, penjemputan_sampah_details","Tabel penjemputan sampah (nasabah meminta, pengepul melakukan)"
jadwal_pengepul,id,pengepul_id → users.id,hasMany: penjemputan,"Tabel jadwal pengepul"
penjemputan_sampah_details,id,"penjemputan_id → penjemputan.id, jenis_sampah_id → jenis_sampah.id",,"Detail sampah per penjemputan"
transaksi,id,"nasabah_id → nasabah.id, pengepul_id → users.id, penjemputan_id → penjemputan.id, jenis_sampah_id → jenis_sampah.id","hasOne: sedekah_sampah, hasMany: kas","Tabel transaksi pembayaran"
jenis_sampah,id,,"hasMany: transaksi, penjemputan_sampah_details, harga_sampah","Master jenis sampah"
harga_sampah,id,jenis_sampah_id → jenis_sampah.id,,"Riwayat harga sampah"
sedekah_sampah,id,"transaksi_id → transaksi.id, nasabah_id → nasabah.id, kelompok_id → kelompok.id",hasMany: kas,"Tabel sedekah sampah"
kas,id,"nasabah_id → nasabah.id, transaksi_id → transaksi.id, sedekah_sampah_id → sedekah_sampah.id",,"Tabel cash flow"
notifications,id,"user_id → users.id, nasabah_id → nasabah.id",,"Tabel notifikasi"
log_aktivitas,id,user_id → users.id,,"Tabel log aktivitas"
artikel,id,author_id → users.id,,"Tabel artikel/blog"
settings,id,,,"Tabel pengaturan sistem"
pengeluaran,id,approved_by → users.id,,"Tabel pengeluaran"
penggunaan_danas,id,created_by → users.id,,"Tabel penggunaan dana"
permissions,id,,,"Tabel permissions (Spatie)"
roles,id,,,"Tabel roles (Spatie)"
model_has_permissions,permission_id + model_type + model_id,permission_id → permissions.id,,"Pivot user-permissions"
model_has_roles,role_id + model_type + model_id,role_id → roles.id,,"Pivot user-roles"
role_has_permissions,permission_id + role_id,"permission_id → permissions.id, role_id → roles.id",,"Pivot role-permissions"
```

---

## Cara Menggunakan:

1. **dbdiagram.io**: Copy format #1 (dbml), paste di https://dbdiagram.io/
2. **SQL Editor**: Copy format #2 (SQL), paste di MySQL/PostgreSQL editor
3. **Dokumentasi**: Copy format #3 (Text), paste di dokumen Word/Markdown
4. **Excel/Spreadsheet**: Copy format #4 (CSV), import ke Excel/Google Sheets

Semua format sudah siap untuk di-copy-paste!

