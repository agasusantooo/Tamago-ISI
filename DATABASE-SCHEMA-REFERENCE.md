# Database Schema Reference - Dosen Pembimbing

## Tabel-tabel yang Digunakan

### 1. users
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(255) UNIQUE,
    nidn VARCHAR(20),           -- Unique ID untuk dosen (penting!)
    role_id BIGINT,
    password VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Field Penting:**
- `id`: Primary key user
- `nidn`: Unique identifier untuk dosen pembimbing (harus ada jika role = dospem)
- `role_id`: FK ke tabel roles, harus punya role 'dospem'

---

### 2. mahasiswa
```sql
CREATE TABLE mahasiswa (
    id BIGINT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    user_id BIGINT NOT NULL,        -- FK ke users
    nama VARCHAR(255),
    email VARCHAR(255),
    dosen_pembimbing_id VARCHAR(20), -- FK ke users.nidn (PENTING!)
    status VARCHAR(50),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Field Penting:**
- `nim`: Nomor Induk Mahasiswa (unique)
- `user_id`: Referensi ke users.id
- `dosen_pembimbing_id`: NIDN dosen pembimbing (untuk filter mahasiswa)
- `status`: Status akademik mahasiswa

---

### 3. bimbingan
```sql
CREATE TABLE bimbingan (
    id_bimbingan BIGINT PRIMARY KEY,
    id BIGINT,                      -- Duplicate PK?
    nim VARCHAR(20),                -- FK ke mahasiswa.nim
    mahasiswa_id BIGINT,            -- Alternative FK
    dosen_nidn VARCHAR(20),         -- FK ke users.nidn (PENTING!)
    topik VARCHAR(255),
    catatan_mahasiswa TEXT,
    catatan_bimbingan TEXT,
    catatan_dosen TEXT,
    tanggal DATE,
    waktu_mulai TIME,
    waktu_selesai TIME,
    status VARCHAR(50),             -- 'pending', 'disetujui', 'ditolak'
    file_pendukung VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Index untuk performa
CREATE INDEX idx_dosen_nidn ON bimbingan(dosen_nidn);
CREATE INDEX idx_nim ON bimbingan(nim);
CREATE INDEX idx_status ON bimbingan(status);
```

**Field Penting:**
- `nim` atau `mahasiswa_id`: Identitas mahasiswa
- `dosen_nidn`: NIDN dosen (untuk filter yang tepat)
- `status`: 'pending', 'disetujui', 'ditolak'
- `tanggal`, `waktu_mulai`: Jadwal bimbingan

**Status Values:**
- `'pending'`: Mahasiswa baru mengajukan, menunggu approval dosen
- `'disetujui'`: Dosen sudah approve jadwal
- `'ditolak'`: Dosen menolak jadwal

---

### 4. proposal
```sql
CREATE TABLE proposal (
    id BIGINT PRIMARY KEY,
    mahasiswa_nim VARCHAR(20),      -- FK ke mahasiswa.nim
    judul VARCHAR(255),
    deskripsi TEXT,
    file_proposal VARCHAR(255),
    status VARCHAR(50),             -- 'pending', 'review', 'revisi', 'disetujui', 'ditolak'
    feedback TEXT,
    dosen_id BIGINT,                -- FK ke users.id
    tanggal_pengajuan TIMESTAMP,
    tanggal_review TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE INDEX idx_mahasiswa_nim ON proposal(mahasiswa_nim);
CREATE INDEX idx_status ON proposal(status);
```

**Status Values:**
- `'pending'`: Baru diajukan
- `'review'`: Sedang di-review
- `'revisi'`: Perlu revisi
- `'disetujui'`: ACC/disetujui
- `'ditolak'`: Ditolak

---

### 5. tim_produksi (Produksi)
```sql
CREATE TABLE tim_produksi (
    id BIGINT PRIMARY KEY,
    mahasiswa_id BIGINT,            -- FK ke users.id
    proposal_id BIGINT,
    dosen_id BIGINT,
    file_skenario VARCHAR(255),
    file_storyboard VARCHAR(255),
    file_dokumen_pendukung VARCHAR(255),
    file_produksi_akhir VARCHAR(255),
    file_luaran_tambahan VARCHAR(255),
    catatan_produksi TEXT,
    
    -- Pra Produksi
    status_pra_produksi VARCHAR(50),        -- 'belum_upload', 'menunggu_review', 'disetujui', 'revisi', 'ditolak'
    tanggal_upload_pra TIMESTAMP,
    tanggal_review_pra TIMESTAMP,
    feedback_pra_produksi TEXT,
    
    -- Produksi Akhir
    status_produksi_akhir VARCHAR(50),      -- Sama seperti pra
    tanggal_upload_akhir TIMESTAMP,
    tanggal_review_akhir TIMESTAMP,
    feedback_produksi_akhir TEXT,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE INDEX idx_mahasiswa_id ON tim_produksi(mahasiswa_id);
CREATE INDEX idx_status_pra ON tim_produksi(status_pra_produksi);
CREATE INDEX idx_status_akhir ON tim_produksi(status_produksi_akhir);
```

**Pra Produksi Status:**
- `'belum_upload'`: Belum upload file skenario/storyboard
- `'menunggu_review'`: Sudah upload, menunggu review dosen
- `'disetujui'`: Approved
- `'revisi'`: Perlu revisi
- `'ditolak'`: Ditolak

**Produksi Akhir Status:**
- Sama dengan pra produksi

---

### 6. roles
```sql
CREATE TABLE roles (
    id BIGINT PRIMARY KEY,
    name VARCHAR(50) UNIQUE,        -- 'admin', 'mahasiswa', 'dospem', 'kaprodi', etc
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

### 7. proyek_akhir (Optional, untuk join)
```sql
CREATE TABLE proyek_akhir (
    id BIGINT PRIMARY KEY,
    mahasiswa_nim VARCHAR(20),      -- FK ke mahasiswa.nim
    judul_proyek VARCHAR(255),
    progress_persentase INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE INDEX idx_mahasiswa_nim ON proyek_akhir(mahasiswa_nim);
```

---

## Query Examples

### Query untuk Controller Methods

#### 1. Get Mahasiswa untuk Dosen
```sql
SELECT m.*, u.name, u.email, pa.judul_proyek, pa.progress_persentase
FROM mahasiswa m
LEFT JOIN users u ON m.user_id = u.id
LEFT JOIN proyek_akhir pa ON m.nim = pa.mahasiswa_nim
WHERE m.dosen_pembimbing_id = 'XX.XX.XXXXX'
ORDER BY m.created_at DESC;
```

#### 2. Get Jadwal Bimbingan untuk Dosen
```sql
SELECT * FROM bimbingan
WHERE dosen_nidn = 'XX.XX.XXXXX'
ORDER BY tanggal ASC;
```

#### 3. Get Jadwal Pending untuk Dosen
```sql
SELECT * FROM bimbingan
WHERE dosen_nidn = 'XX.XX.XXXXX'
AND status = 'pending'
ORDER BY tanggal ASC;
```

#### 4. Get Produksi untuk Mahasiswa
```sql
SELECT * FROM tim_produksi
WHERE mahasiswa_id = (SELECT id FROM users WHERE nidn = ?)
ORDER BY created_at DESC;
```

---

## Controller-Database Mapping

### MahasiswaBimbinganController.php

| Method | Query | Status Update |
|--------|-------|---------------|
| `index()` | Mahasiswa by `dosen_pembimbing_id` | - |
| `show()` | Mahasiswa by `nim`, Jadwal by `nim` + `dosen_nidn`, Proposal, Produksi | - |
| `approveBimbingan()` | Bimbingan by `id` | `status = 'disetujui'` |
| `rejectBimbingan()` | Bimbingan by `id` | `status = 'ditolak'` |
| `approveProposal()` | Proposal by `id` | `status = 'disetujui'` |
| `rejectProposal()` | Proposal by `id` | `status = 'ditolak'` |

### MahasiswaProduksiController.php

| Method | Query | Status Update |
|--------|-------|---------------|
| `getProduksiList()` | Produksi by `mahasiswa_id` | - |
| `approvePraProduksi()` | Produksi by `id` | `status_pra_produksi = 'disetujui'/'revisi'/'ditolak'` |
| `approveProduksiAkhir()` | Produksi by `id` | `status_produksi_akhir = 'disetujui'/'revisi'/'ditolak'` |

### JadwalBimbinganController.php

| Method | Query | Status |
|--------|-------|--------|
| `index()` | Bimbingan by `dosen_nidn` | - |

---

## Migration Reference

Jika perlu membuat tabel baru atau modify:

```php
// Tambah column jika belum ada
Schema::table('bimbingan', function (Blueprint $table) {
    $table->string('dosen_nidn', 20)->nullable()->after('id');
    $table->string('status', 50)->default('pending')->after('file_pendukung');
    $table->index('dosen_nidn');
    $table->index('status');
});

// Foreign key (jika perlu)
Schema::table('bimbingan', function (Blueprint $table) {
    $table->foreign('dosen_nidn')
        ->references('nidn')
        ->on('users')
        ->onDelete('set null');
});
```

---

## Testing Data untuk Development

```sql
-- Insert test dosen
INSERT INTO users (id, name, email, username, nidn, role_id, password)
VALUES (1, 'Dr. Budi Santoso', 'budi@example.com', 'budi', '19700101199101001', 2, bcrypt('password'));

-- Insert test mahasiswa
INSERT INTO mahasiswa (id, nim, user_id, nama, email, dosen_pembimbing_id, status)
VALUES 
(10, '2021001', 10, 'Ahmad Fauzi', 'ahmad@example.com', '19700101199101001', 'aktif'),
(11, '2021002', 11, 'Budi Suwanto', 'budi_sw@example.com', '19700101199101001', 'aktif');

-- Insert test bimbingan
INSERT INTO bimbingan (nim, dosen_nidn, topik, tanggal, waktu_mulai, waktu_selesai, status)
VALUES 
('2021001', '19700101199101001', 'Diskusi Progress', '2025-12-05', '10:00:00', '11:00:00', 'pending'),
('2021002', '19700101199101001', 'Review Proposal', '2025-12-06', '14:00:00', '15:00:00', 'pending');
```

