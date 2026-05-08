# Entity Relationship Diagram (ERD) - Sistem Manajemen Sampah

## ERD dalam Format Mermaid

```mermaid
erDiagram
    USERS ||--o{ NASABAH : "has one"
    USERS ||--o{ PENJEMPUTAN : "pengepul"
    USERS ||--o{ TRANSAKSI : "pengepul"
    USERS ||--o{ JADWAL_PENGEPUL : "has many"
    USERS ||--o{ ARTIKEL : "author"
    USERS ||--o{ LOG_AKTIVITAS : "has many"
    USERS ||--o{ PENGELUARAN : "approved_by"
    USERS ||--o{ PENGGUNAAN_DANAS : "created_by"
    USERS }o--|| KELOMPOK : "belongs to"

    KELOMPOK ||--o{ NASABAH : "has many"
    KELOMPOK ||--o{ PENJEMPUTAN : "has many"
    KELOMPOK ||--o{ SEDEKAH_SAMPAH : "has many"
    KELOMPOK ||--o{ USERS : "has many"

    NASABAH ||--o{ PENJEMPUTAN : "has many"
    NASABAH ||--o{ TRANSAKSI : "has many"
    NASABAH ||--o{ SEDEKAH_SAMPAH : "has many"
    NASABAH ||--o{ NOTIFICATIONS : "has many"
    NASABAH ||--o{ KAS : "has many"
    NASABAH }o--|| USERS : "belongs to"
    NASABAH }o--o| KELOMPOK : "belongs to"

    PENJEMPUTAN ||--o{ TRANSAKSI : "has many"
    PENJEMPUTAN ||--o{ PENJEMPUTAN_SAMPAH_DETAILS : "has many"
    PENJEMPUTAN }o--|| NASABAH : "belongs to"
    PENJEMPUTAN }o--o| KELOMPOK : "belongs to"
    PENJEMPUTAN }o--o| USERS : "pengepul"
    PENJEMPUTAN }o--o| JADWAL_PENGEPUL : "belongs to"

    JADWAL_PENGEPUL ||--o{ PENJEMPUTAN : "has many"
    JADWAL_PENGEPUL }o--|| USERS : "pengepul"

    PENJEMPUTAN_SAMPAH_DETAILS }o--|| PENJEMPUTAN : "belongs to"
    PENJEMPUTAN_SAMPAH_DETAILS }o--|| JENIS_SAMPAH : "belongs to"

    TRANSAKSI ||--o{ SEDEKAH_SAMPAH : "has one"
    TRANSAKSI ||--o{ KAS : "has many"
    TRANSAKSI }o--|| NASABAH : "belongs to"
    TRANSAKSI }o--|| USERS : "pengepul"
    TRANSAKSI }o--o| PENJEMPUTAN : "belongs to"
    TRANSAKSI }o--|| JENIS_SAMPAH : "belongs to"

    JENIS_SAMPAH ||--o{ TRANSAKSI : "has many"
    JENIS_SAMPAH ||--o{ PENJEMPUTAN_SAMPAH_DETAILS : "has many"
    JENIS_SAMPAH ||--o{ HARGA_SAMPAH : "has many"

    HARGA_SAMPAH }o--|| JENIS_SAMPAH : "belongs to"

    SEDEKAH_SAMPAH ||--o{ KAS : "has many"
    SEDEKAH_SAMPAH }o--|| TRANSAKSI : "belongs to"
    SEDEKAH_SAMPAH }o--|| NASABAH : "belongs to"
    SEDEKAH_SAMPAH }o--o| KELOMPOK : "belongs to"

    KAS }o--o| NASABAH : "belongs to"
    KAS }o--o| TRANSAKSI : "belongs to"
    KAS }o--o| SEDEKAH_SAMPAH : "belongs to"

    NOTIFICATIONS }o--o| USERS : "belongs to"
    NOTIFICATIONS }o--o| NASABAH : "belongs to"

    USERS ||--o{ MODEL_HAS_ROLES : "has roles"
    USERS ||--o{ MODEL_HAS_PERMISSIONS : "has permissions"
    ROLES ||--o{ MODEL_HAS_ROLES : "assigned to"
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : "has"
    PERMISSIONS ||--o{ MODEL_HAS_PERMISSIONS : "assigned to"
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : "assigned to"

    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string phone
        text address
        boolean is_active
        boolean is_verified
        bigint kelompok_id FK
        timestamp created_at
        timestamp updated_at
    }

    KELOMPOK {
        bigint id PK
        string nama
        string kode UK
        text deskripsi
        string koordinator
        text lokasi
        json jadwal_rutin
        boolean is_active
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    NASABAH {
        bigint id PK
        bigint user_id FK
        bigint kelompok_id FK
        string kode_nasabah UK
        string nama
        string email
        string phone
        text address
        date tanggal_bergabung
        decimal saldo
        boolean is_active
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    PENJEMPUTAN {
        bigint id PK
        bigint nasabah_id FK
        bigint kelompok_id FK
        bigint pengepul_id FK
        bigint jadwal_pengepul_id FK
        date tanggal_penjemputan
        datetime waktu_penjemputan
        datetime jadwal_penjemputan
        text alamat_penjemputan
        text catatan
        string gambar
        enum status
        boolean is_sorted
        boolean is_request_khusus
        datetime waktu_diambil
        datetime waktu_mulai
        datetime waktu_selesai
        datetime waktu_dibatalkan
        string payment_option
        decimal donation_amount
        decimal nasabah_amount
        decimal estimasi_berat
        decimal berat_final
        string weight_status
        boolean self_weighted
        decimal berat_difference
        text weight_notes
        string midtrans_order_id
        string payment_status
        string payment_method
        datetime payment_time
        decimal total_amount
        timestamp created_at
        timestamp updated_at
    }

    JADWAL_PENGEPUL {
        bigint id PK
        bigint pengepul_id FK
        string hari
        datetime waktu_mulai
        datetime waktu_selesai
        string lokasi
        text catatan
        timestamp created_at
        timestamp updated_at
    }

    PENJEMPUTAN_SAMPAH_DETAILS {
        bigint id PK
        bigint penjemputan_id FK
        bigint jenis_sampah_id FK
        decimal berat_nasabah
        decimal berat_verifikasi
        text catatan
        string gambar
        timestamp created_at
        timestamp updated_at
    }

    TRANSAKSI {
        bigint id PK
        bigint nasabah_id FK
        bigint pengepul_id FK
        bigint penjemputan_id FK
        bigint jenis_sampah_id FK
        decimal berat
        decimal total_harga
        datetime tanggal_transaksi
        text catatan
        boolean sistem
        boolean nasabah
        string gambar_bukti_nasabah
        string gambar_bukti_sistem
        string bukti_pembayaran
        integer verified_by_nasabah
        integer verified_by_admin
        datetime verified_at_nasabah
        datetime verified_at_admin
        integer status
        text alasan_penolakan
        timestamp created_at
        timestamp updated_at
    }

    JENIS_SAMPAH {
        bigint id PK
        string nama
        enum kategori
        text deskripsi
        enum satuan
        decimal harga
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    HARGA_SAMPAH {
        bigint id PK
        bigint jenis_sampah_id FK
        decimal harga
        date tanggal_berlaku
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    SEDEKAH_SAMPAH {
        bigint id PK
        bigint transaksi_id FK
        bigint nasabah_id FK
        bigint kelompok_id FK
        decimal jumlah_sedekah
        decimal persentase
        datetime tanggal_sedekah
        tinyInteger bulan_sedekah
        year tahun_sedekah
        text keterangan
        enum status
        timestamp created_at
        timestamp updated_at
    }

    KAS {
        bigint id PK
        bigint nasabah_id FK
        bigint transaksi_id FK
        bigint sedekah_sampah_id FK
        enum tipe
        decimal jumlah
        string deskripsi
        datetime tanggal
        decimal saldo_sebelum
        decimal saldo_sesudah
        timestamp created_at
        timestamp updated_at
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        bigint nasabah_id FK
        enum type
        string title
        text message
        json data
        boolean is_read
        timestamp read_at
        timestamp created_at
        timestamp updated_at
    }

    LOG_AKTIVITAS {
        bigint id PK
        bigint user_id FK
        string activity
        text description
        string ip_address
        text user_agent
        timestamp created_at
        timestamp updated_at
    }

    ARTIKEL {
        bigint id PK
        bigint author_id FK
        string title
        string slug UK
        longText content
        text excerpt
        string featured_image
        boolean is_published
        datetime published_at
        json tags
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    SETTINGS {
        bigint id PK
        string key UK
        string label
        text value
        enum type
        string group
        text description
        boolean is_public
        timestamp created_at
        timestamp updated_at
    }

    PENGELUARAN {
        bigint id PK
        string kategori
        string nama_pengeluaran
        text deskripsi
        decimal jumlah
        date tanggal_pengeluaran
        string metode_pembayaran
        string bukti_pembayaran
        string status
        bigint approved_by FK
        datetime approved_at
        text catatan_approval
        timestamp created_at
        timestamp updated_at
    }

    PENGGUNAAN_DANAS {
        bigint id PK
        date tanggal_penggunaan
        string kategori
        text deskripsi
        decimal jumlah_pengeluaran
        string bukti_pengeluaran
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }

    PERMISSIONS {
        bigint id PK
        string name UK
        string guard_name UK
        timestamp created_at
        timestamp updated_at
    }

    ROLES {
        bigint id PK
        string name UK
        string guard_name UK
        timestamp created_at
        timestamp updated_at
    }

    MODEL_HAS_PERMISSIONS {
        bigint permission_id PK_FK
        string model_type PK
        bigint model_id PK
    }

    MODEL_HAS_ROLES {
        bigint role_id PK_FK
        string model_type PK
        bigint model_id PK
    }

    ROLE_HAS_PERMISSIONS {
        bigint permission_id PK_FK
        bigint role_id PK_FK
    }
```

## Deskripsi Tabel dan Relasi

### 1. **USERS** (Tabel Pengguna)
Tabel utama untuk semua pengguna sistem (Admin, Pengepul, Kelompok Nasabah, Nasabah).
- **Primary Key**: `id`
- **Foreign Keys**: 
  - `kelompok_id` → `KELOMPOK.id` (nullable)
- **Relasi**:
  - `hasOne` → NASABAH
  - `hasMany` → PENJEMPUTAN (sebagai pengepul)
  - `hasMany` → TRANSAKSI (sebagai pengepul)
  - `hasMany` → JADWAL_PENGEPUL
  - `hasMany` → ARTIKEL (sebagai author)
  - `hasMany` → LOG_AKTIVITAS
  - `belongsTo` → KELOMPOK

### 2. **KELOMPOK** (Tabel Kelompok Nasabah)
Tabel untuk mengelompokkan nasabah berdasarkan wilayah atau komunitas.
- **Primary Key**: `id`
- **Unique Key**: `kode`
- **Relasi**:
  - `hasMany` → NASABAH
  - `hasMany` → PENJEMPUTAN
  - `hasMany` → SEDEKAH_SAMPAH
  - `hasMany` → USERS

### 3. **NASABAH** (Tabel Nasabah)
Tabel untuk data nasabah yang meminta penjemputan sampah.
- **Primary Key**: `id`
- **Unique Key**: `kode_nasabah`
- **Foreign Keys**:
  - `user_id` → `USERS.id` (nullable)
  - `kelompok_id` → `KELOMPOK.id` (nullable)
- **Relasi**:
  - `belongsTo` → USERS
  - `belongsTo` → KELOMPOK
  - `hasMany` → PENJEMPUTAN (nasabah yang meminta penjemputan)
  - `hasMany` → TRANSAKSI (nasabah yang menerima pembayaran)
  - `hasMany` → SEDEKAH_SAMPAH
  - `hasMany` → NOTIFICATIONS
  - `hasMany` → KAS

### 4. **PENJEMPUTAN** (Tabel Penjemputan Sampah)
Tabel untuk mencatat permintaan penjemputan sampah dari nasabah.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `nasabah_id` → `NASABAH.id`
  - `kelompok_id` → `KELOMPOK.id` (nullable)
  - `pengepul_id` → `USERS.id` (nullable)
  - `jadwal_pengepul_id` → `JADWAL_PENGEPUL.id` (nullable)
- **Status**: `pending`, `assigned`, `on_progress`, `weight_verified`, `completed`, `cancelled`
- **Relasi**:
  - `belongsTo` → NASABAH (nasabah yang meminta penjemputan)
  - `belongsTo` → KELOMPOK
  - `belongsTo` → USERS (pengepul yang melakukan penjemputan)
  - `belongsTo` → JADWAL_PENGEPUL
  - `hasMany` → TRANSAKSI
  - `hasMany` → PENJEMPUTAN_SAMPAH_DETAILS

### 5. **JADWAL_PENGEPUL** (Tabel Jadwal Pengepul)
Tabel untuk jadwal rutin pengepul melakukan penjemputan.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `pengepul_id` → `USERS.id`
- **Relasi**:
  - `belongsTo` → USERS (pengepul)
  - `hasMany` → PENJEMPUTAN

### 6. **PENJEMPUTAN_SAMPAH_DETAILS** (Tabel Detail Sampah Penjemputan)
Tabel untuk detail jenis sampah dan berat dalam setiap penjemputan.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `penjemputan_id` → `PENJEMPUTAN.id`
  - `jenis_sampah_id` → `JENIS_SAMPAH.id`
- **Relasi**:
  - `belongsTo` → PENJEMPUTAN
  - `belongsTo` → JENIS_SAMPAH

### 7. **TRANSAKSI** (Tabel Transaksi)
Tabel untuk mencatat transaksi pembayaran dari pengepul ke nasabah atau sistem.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `nasabah_id` → `NASABAH.id`
  - `pengepul_id` → `USERS.id`
  - `penjemputan_id` → `PENJEMPUTAN.id` (nullable)
  - `jenis_sampah_id` → `JENIS_SAMPAH.id`
- **Status**: 
  - `0` = Pending
  - `1` = Verified
  - `99` = Rejected
- **Relasi**:
  - `belongsTo` → NASABAH
  - `belongsTo` → USERS (pengepul)
  - `belongsTo` → PENJEMPUTAN
  - `belongsTo` → JENIS_SAMPAH
  - `hasOne` → SEDEKAH_SAMPAH
  - `hasMany` → KAS

### 8. **JENIS_SAMPAH** (Tabel Jenis Sampah)
Tabel master untuk jenis-jenis sampah yang dapat dijual.
- **Primary Key**: `id`
- **Kategori**: `plastik`, `kertas`, `logam`, `kaca`, `lainnya`
- **Satuan**: `kg`, `gram`, `pcs`, `liter`
- **Relasi**:
  - `hasMany` → TRANSAKSI
  - `hasMany` → PENJEMPUTAN_SAMPAH_DETAILS
  - `hasMany` → HARGA_SAMPAH

### 9. **HARGA_SAMPAH** (Tabel Harga Sampah)
Tabel untuk riwayat perubahan harga sampah berdasarkan waktu.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `jenis_sampah_id` → `JENIS_SAMPAH.id`
- **Relasi**:
  - `belongsTo` → JENIS_SAMPAH

### 10. **SEDEKAH_SAMPAH** (Tabel Sedekah Sampah)
Tabel untuk mencatat sedekah sampah dari nasabah ke sistem.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `transaksi_id` → `TRANSAKSI.id`
  - `nasabah_id` → `NASABAH.id`
  - `kelompok_id` → `KELOMPOK.id` (nullable)
- **Status**: `pending`, `approved`, `used`
- **Relasi**:
  - `belongsTo` → TRANSAKSI
  - `belongsTo` → NASABAH
  - `belongsTo` → KELOMPOK
  - `hasMany` → KAS

### 11. **KAS** (Tabel Kas)
Tabel untuk mencatat semua transaksi kas masuk dan keluar (cash flow).
- **Primary Key**: `id`
- **Foreign Keys**:
  - `nasabah_id` → `NASABAH.id` (nullable)
  - `transaksi_id` → `TRANSAKSI.id` (nullable)
  - `sedekah_sampah_id` → `SEDEKAH_SAMPAH.id` (nullable)
- **Tipe**: `masuk`, `keluar`
- **Relasi**:
  - `belongsTo` → NASABAH
  - `belongsTo` → TRANSAKSI
  - `belongsTo` → SEDEKAH_SAMPAH

### 12. **NOTIFICATIONS** (Tabel Notifikasi)
Tabel untuk menyimpan notifikasi untuk pengguna dan nasabah.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `user_id` → `USERS.id` (nullable)
  - `nasabah_id` → `NASABAH.id` (nullable)
- **Type**: `transaksi`, `penjemputan`, `sedekah`, `system`
- **Relasi**:
  - `belongsTo` → USERS
  - `belongsTo` → NASABAH

### 13. **LOG_AKTIVITAS** (Tabel Log Aktivitas)
Tabel untuk mencatat aktivitas pengguna dalam sistem.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `user_id` → `USERS.id` (nullable)
- **Relasi**:
  - `belongsTo` → USERS

### 14. **ARTIKEL** (Tabel Artikel)
Tabel untuk menyimpan artikel/blog yang dipublikasikan.
- **Primary Key**: `id`
- **Unique Key**: `slug`
- **Foreign Keys**:
  - `author_id` → `USERS.id`
- **Relasi**:
  - `belongsTo` → USERS (author)

### 15. **SETTINGS** (Tabel Pengaturan)
Tabel untuk menyimpan konfigurasi sistem.
- **Primary Key**: `id`
- **Unique Key**: `key`
- **Type**: `string`, `number`, `boolean`, `json`, `text`

### 16. **PENGELUARAN** (Tabel Pengeluaran)
Tabel untuk mencatat pengeluaran sistem (sebelumnya).
- **Primary Key**: `id`
- **Foreign Keys**:
  - `approved_by` → `USERS.id` (nullable)
- **Relasi**:
  - `belongsTo` → USERS (approved_by)

### 17. **PENGGUNAAN_DANAS** (Tabel Penggunaan Dana)
Tabel untuk mencatat penggunaan dana dari sedekah sampah.
- **Primary Key**: `id`
- **Foreign Keys**:
  - `created_by` → `USERS.id`
- **Relasi**:
  - `belongsTo` → USERS (created_by)

### 18. **PERMISSIONS** (Tabel Permissions - Spatie Permission)
Tabel untuk menyimpan permissions (izin) yang dapat diberikan ke user atau role.
- **Primary Key**: `id`
- **Unique Keys**: `name` + `guard_name`
- **Relasi**:
  - `belongsToMany` → USERS (melalui MODEL_HAS_PERMISSIONS)
  - `belongsToMany` → ROLES (melalui ROLE_HAS_PERMISSIONS)

### 19. **ROLES** (Tabel Roles - Spatie Permission)
Tabel untuk menyimpan roles (peran) seperti admin, pengepul, nasabah, kelompok_nasabah.
- **Primary Key**: `id`
- **Unique Keys**: `name` + `guard_name`
- **Relasi**:
  - `belongsToMany` → USERS (melalui MODEL_HAS_ROLES)
  - `belongsToMany` → PERMISSIONS (melalui ROLE_HAS_PERMISSIONS)

### 20. **MODEL_HAS_PERMISSIONS** (Tabel Pivot - User Permissions)
Tabel pivot untuk relasi many-to-many antara USERS dan PERMISSIONS.
- **Primary Keys**: `permission_id` + `model_type` + `model_id`
- **Foreign Keys**:
  - `permission_id` → `PERMISSIONS.id`
- **Relasi**:
  - `belongsTo` → PERMISSIONS
  - Polymorphic relation ke USERS (model_type = 'App\Models\User', model_id = user.id)

### 21. **MODEL_HAS_ROLES** (Tabel Pivot - User Roles)
Tabel pivot untuk relasi many-to-many antara USERS dan ROLES.
- **Primary Keys**: `role_id` + `model_type` + `model_id`
- **Foreign Keys**:
  - `role_id` → `ROLES.id`
- **Relasi**:
  - `belongsTo` → ROLES
  - Polymorphic relation ke USERS (model_type = 'App\Models\User', model_id = user.id)

### 22. **ROLE_HAS_PERMISSIONS** (Tabel Pivot - Role Permissions)
Tabel pivot untuk relasi many-to-many antara ROLES dan PERMISSIONS.
- **Primary Keys**: `permission_id` + `role_id`
- **Foreign Keys**:
  - `permission_id` → `PERMISSIONS.id`
  - `role_id` → `ROLES.id`
- **Relasi**:
  - `belongsTo` → PERMISSIONS
  - `belongsTo` → ROLES

## Catatan Penting

1. **Soft Deletes**: Tabel `KELOMPOK`, `NASABAH`, `JENIS_SAMPAH`, dan `ARTIKEL` menggunakan soft deletes.

2. **Role-Based Access Control (RBAC)**:
   - Sistem menggunakan **Spatie Permission** package untuk mengelola roles dan permissions
   - **Roles** yang digunakan: `admin`, `pengepul`, `nasabah`, `kelompok_nasabah`
   - **Permissions** dapat diberikan langsung ke user atau melalui role
   - Tabel `MODEL_HAS_ROLES` dan `MODEL_HAS_PERMISSIONS` menggunakan polymorphic relation ke `USERS`
   - Tabel `ROLE_HAS_PERMISSIONS` menghubungkan roles dengan permissions mereka

3. **Status Transaksi**:
   - `0` = Pending (menunggu verifikasi)
   - `1` = Verified (sudah diverifikasi)
   - `99` = Rejected (ditolak dengan alasan)

4. **Status Penjemputan**:
   - `pending` = Menunggu pengepul
   - `assigned` = Sudah diambil pengepul
   - `on_progress` = Sedang diproses
   - `weight_verified` = Berat sudah diverifikasi
   - `completed` = Selesai
   - `cancelled` = Dibatalkan

5. **Status Sedekah Sampah**:
   - `pending` = Menunggu persetujuan
   - `approved` = Disetujui
   - `used` = Sudah digunakan

6. **Tipe Kas**:
   - `masuk` = Kas masuk (dari transaksi atau sedekah)
   - `keluar` = Kas keluar (penggunaan dana)

7. **Payment Options** (di PENJEMPUTAN):
   - Sistem mendukung pembayaran melalui Midtrans
   - `payment_option`: metode pembayaran yang dipilih
   - `donation_amount`: jumlah donasi ke sistem
   - `nasabah_amount`: jumlah untuk nasabah

8. **Verifikasi Ganda**:
   - Transaksi dapat diverifikasi oleh nasabah (`verified_by_nasabah`)
   - Transaksi dapat diverifikasi oleh admin (`verified_by_admin`)
   - Status `99` digunakan untuk transaksi yang ditolak dengan `alasan_penolakan`

## Diagram Alur Data

### Alur Penjemputan Sampah:
1. **NASABAH** membuat/meminta **PENJEMPUTAN**
2. **PENJEMPUTAN** memiliki banyak **PENJEMPUTAN_SAMPAH_DETAILS** (detail jenis sampah)
3. **USERS** (pengepul dengan role pengepul) mengambil dan melakukan **PENJEMPUTAN**
4. Setelah selesai, **PENJEMPUTAN** menghasilkan **TRANSAKSI**
5. **TRANSAKSI** dapat menghasilkan **SEDEKAH_SAMPAH** jika ada donasi
6. **TRANSAKSI** dan **SEDEKAH_SAMPAH** dicatat di **KAS**

### Alur Verifikasi Transaksi:
1. **PENGEPUL** mengupload bukti pembayaran ke **TRANSAKSI**
2. **NASABAH** memverifikasi transaksi (`verified_by_nasabah`)
3. Jika ditolak, status menjadi `99` dengan `alasan_penolakan`
4. Jika diterima, status menjadi `1` (verified)
5. Untuk transaksi sistem, **ADMIN** juga memverifikasi (`verified_by_admin`)

### Alur Sedekah Sampah:
1. **TRANSAKSI** dengan donasi menghasilkan **SEDEKAH_SAMPAH**
2. **SEDEKAH_SAMPAH** dicatat di **KAS** sebagai kas masuk
3. **PENGGUNAAN_DANAS** menggunakan dana dari sedekah, dicatat di **KAS** sebagai kas keluar

