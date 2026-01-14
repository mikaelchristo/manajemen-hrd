# Update Data Karyawan - Dokumentasi

Tanggal Update: 14 Januari 2026

## Perubahan yang Dilakukan

Aplikasi HRD telah diupdate untuk menambahkan field-field baru pada data karyawan sesuai dengan kebutuhan sistem yang lebih lengkap.

### Field Baru yang Ditambahkan

Berikut adalah field-field baru yang telah ditambahkan ke database karyawan:

1. **TGL_MASUK_KERJA** (`tglMulaiKerja`) - Tanggal karyawan mulai bekerja
2. **SK_TETAP** (`skTetap`) - Tanggal SK Tetap karyawan
3. **PENDIDIKAN** (`pendidikan`) - Jenjang pendidikan (SMA, D3, S1, S2, dll)
4. **TAMATAN** (`tamatan`) - Nama institusi pendidikan dan jurusan
5. **NO_HP** (`noHp`) - Nomor HP karyawan
6. **EMAIL** (`email`) - Email karyawan
7. **ALAMAT** (`alamat`) - Alamat lengkap karyawan

### Field yang Sudah Ada Sebelumnya

1. **NIK_KARYAWAN** (`nikKry`) - NIK internal karyawan
2. **NAMA_KARYAWAN** (`namaKaryawan`) - Nama lengkap karyawan
3. **NIK_KTP** (`nikKtp`) - Nomor KTP
4. **UNIT** (`unit`) - Unit kerja/departemen
5. **GOL** (`gol`) - Golongan kepegawaian
6. **PROFESI** (`profesi`) - Jabatan/profesi
7. **STATUS_PEGAWAI** (`statusPegawai`) - Status (Tetap/PKWT/Kontrak)
8. **TEMPAT_LAHIR** (`tempatLahir`) - Tempat lahir
9. **TGL_LAHIR** (`tglLahir`) - Tanggal lahir
10. **UMUR_TAHUN** - Dihitung otomatis dari tanggal lahir (dalam tahun)
11. **UMUR_BULAN** - Dihitung otomatis dari tanggal lahir (dalam bulan)
12. **JENIS_KELAMIN** (`jenisKelamin`) - Jenis kelamin (Laki-laki/Perempuan)

## File yang Dimodifikasi

### 1. Database Migration
- **File Baru**: `database/migrations/2026_01_14_032439_add_additional_fields_to_karyawan_table.php`
- Menambahkan 7 kolom baru ke tabel karyawan

### 2. Model
- **File**: `app/Models/Karyawan.php`
- Menambahkan field baru ke `$fillable`
- Menambahkan casting untuk field tanggal (`tglMulaiKerja`, `skTetap`)
- Menambahkan accessor untuk `umurTahun` dan `umurBulan`

### 3. Controller
- **File**: `app/Http/Controllers/KaryawanController.php`
- Update validation rules untuk field baru
- Update method `show()` untuk menampilkan data lengkap
- Update method `getData()` untuk DataTables dengan kolom baru
- Update method `downloadTemplate()` dengan kolom baru

### 4. View
- **File**: `resources/views/karyawan/index.blade.php`
- Menambahkan kolom baru di tabel DataTables
- Menambahkan form input untuk field baru
- Update modal detail untuk menampilkan informasi lengkap
- Update JavaScript untuk handle field baru

### 5. Export Excel
- **File**: `app/Exports/KaryawanExport.php`
- Update header dan mapping untuk field baru
- Menambahkan kolom Umur Tahun dan Umur Bulan terpisah

### 6. Import Excel
- **File**: `app/Imports/KaryawanImport.php`
- Support import untuk field baru
- Transformasi tanggal untuk SK Tetap

## Cara Menggunakan

### 1. Migrasi Database
Jalankan migration untuk update struktur database:
```bash
php artisan migrate
```

### 2. Template Import Excel
Download template Excel terbaru yang sudah include kolom baru:
- Klik tombol "Import Excel" di halaman Data Karyawan
- Download template dari modal import

### 3. Format Data

#### Tanggal
Format tanggal yang didukung:
- `dd-mm-yyyy` (contoh: 01-01-1990)
- `dd/mm/yyyy` (contoh: 01/01/1990)
- `dd Bulan yyyy` (contoh: 01 Januari 1990)

#### Email
Format email valid: `nama@domain.com`

#### No HP
Format: `08xxxxxxxxxx` atau `+62xxxxxxxxxx`

### 4. Form Tambah/Edit Karyawan

Form telah diupdate dengan field-field baru:
- **Required**: NIK Karyawan, Nama, NIK KTP, Unit, Profesi, Status, Tempat Lahir, Tanggal Lahir, Tanggal Masuk Kerja, Jenis Kelamin
- **Optional**: Golongan, SK Tetap, Pendidikan, Tamatan, No HP, Email, Alamat

### 5. Export Excel

Export Excel akan menghasilkan file dengan kolom lengkap:
- Semua field karyawan
- Umur dalam tahun dan bulan (dihitung otomatis)
- Format tanggal: dd-mm-yyyy

## Contoh Data

```
NIK: K001
Nama: John Doe
NIK KTP: 1234567890123456
Unit: IT Department
Golongan: III/A
Profesi: Software Developer
Status: Tetap
Tempat Lahir: Jakarta
Tanggal Lahir: 01-01-1990
Umur Tahun: 35
Umur Bulan: 420
Jenis Kelamin: Laki-laki
Tanggal Masuk Kerja: 01-01-2015
SK Tetap: 01-01-2017
Pendidikan: S1
Tamatan: Universitas Indonesia - Teknik Informatika
No HP: 081234567890
Email: john.doe@example.com
Alamat: Jl. Sudirman No. 123, Jakarta
```

## Catatan Penting

1. **Umur** dihitung secara otomatis dari tanggal lahir
2. **SK Tetap** dan field lainnya bersifat opsional
3. **Email** akan divalidasi formatnya
4. Import Excel akan **skip** baris yang memiliki NIK duplikat
5. Data yang gagal di-import akan ditampilkan di error message

## Troubleshooting

### Migration Error
Jika terjadi error saat migration:
```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

### Clear Cache
Jika perubahan tidak terlihat:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Support

Untuk pertanyaan atau masalah, silakan hubungi tim IT Development.
