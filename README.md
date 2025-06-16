# Sistem Optimalisasi Penyaluran Bantuan Sosial

Sistem ini menggunakan algoritma K-Means Clustering untuk mengelompokkan penerima bantuan sosial berdasarkan kriteria tertentu.

## Daftar Isi
1. [Keterangan Nilai](#keterangan-nilai)
2. [Mapping Centroid](#mapping-centroid)
3. [Hasil Clustering](#hasil-clustering)
4. [Struktur Data](#struktur-data)
5. [Cara Penggunaan](#cara-penggunaan)
6. [Teknologi](#teknologi)
7. [Instalasi](#instalasi)

## Keterangan Nilai

### Kriteria dan Nilai
1. **Usia**:
   - 1 = 15-25 Tahun
   - 2 = 26-35 Tahun
   - 3 = 36-45 Tahun
   - 4 = >46 Tahun

2. **Jumlah Tanggungan**:
   - 1 = 1 Anak
   - 2 = 2 Anak
   - 3 = 3 Anak
   - 4 = >3 Anak

3. **Kondisi Rumah**:
   - 1 = Baik
   - 2 = Cukup
   - 3 = Kurang

4. **Status Kepemilikan**:
   - 1 = Hak Milik
   - 2 = Numpang
   - 3 = Sewa

5. **Penghasilan**:
   - 1 = >4000000 (lebih dari 4 juta)
   - 2 = 3000001-4000000 (3 juta lebih sampai 4 juta)
   - 3 = 2000001-3000000 (2 juta lebih sampai 3 juta)
   - 4 = 1000000-2000000 (1 juta sampai 2 juta)

### Keterangan Cluster
- **C1**: Sangat membutuhkan bantuan (Rendah)
- **C2**: Tidak membutuhkan bantuan (Tinggi)
- **C3**: Prioritas sedang (Menengah)

## Mapping Centroid

### Centroid Awal
| Centroid | Nama Penduduk | Usia | Tanggungan | Kondisi Rumah | Status Kepemilikan | Penghasilan |
|----------|---------------|------|------------|---------------|-------------------|-------------|
| C1 | Riduan | 4 | 3 | 3 | 2 | 4 |
| C2 | UJANG | 4 | 4 | 2 | 1 | 4 |
| C3 | ISMAIL | 4 | 3 | 1 | 1 | 2 |

## Hasil Clustering

### Hasil Perhitungan Jarak dan Penentuan Cluster
| Nama | Usia | Tanggungan | Kondisi Rumah | Status Kepemilikan | Penghasilan | Jarak ke C1 | Jarak ke C2 | Jarak ke C3 | Jarak Terdekat | Cluster |
|------|------|------------|---------------|-------------------|-------------|-------------|-------------|-------------|----------------|---------|
| ALBAR | 2 | 4 | 3 | 2 | 4 | 2.236067977 | 2.449489743 | 3.741657387 | 2.236067977 | C1 |
| IBRAHIM | 2 | 4 | 2 | 1 | 2 | 3.316624790 | 2.828427125 | 2.449489743 | 2.449489743 | C3 |
| KARNAIN | 2 | 3 | 1 | 1 | 3 | 3.162277660 | 2.645751311 | 2.236067977 | 2.236067977 | C3 |
| Riduan | 4 | 3 | 3 | 2 | 4 | 0.000000000 | 1.732050808 | 3.000000000 | 0.000000000 | C1 |
| Sarkowi | 4 | 3 | 3 | 1 | 4 | 1.000000000 | 1.414213562 | 2.828427125 | 1.000000000 | C1 |
| SUPARMI | 4 | 3 | 1 | 1 | 4 | 2.236067977 | 1.414213562 | 2.000000000 | 1.414213562 | C2 |
| UJANG | 4 | 4 | 2 | 1 | 4 | 1.732050808 | 0.000000000 | 2.449489743 | 0.000000000 | C2 |
| Usmina | 4 | 1 | 3 | 2 | 4 | 2.000000000 | 3.316624790 | 3.605551275 | 2.000000000 | C1 |
| SIAH | 4 | 1 | 3 | 2 | 4 | 2.000000000 | 3.316624790 | 3.605551275 | 2.000000000 | C1 |
| ISMAIL | 4 | 3 | 1 | 1 | 2 | 3.000000000 | 2.449489743 | 0.000000000 | 0.000000000 | C3 |
| M. YASIN | 3 | 4 | 1 | 1 | 1 | 4.000000000 | 3.316624790 | 1.732050808 | 1.732050808 | C3 |
| SAUYA | 4 | 1 | 3 | 2 | 4 | 2.000000000 | 3.316624790 | 3.605551275 | 2.000000000 | C1 |
| ROMNAH | 4 | 1 | 2 | 1 | 4 | 2.449489743 | 3.000000000 | 3.000000000 | 2.449489743 | C1 |
| Mat Sani | 3 | 4 | 1 | 1 | 2 | 3.316624790 | 2.449489743 | 1.414213562 | 1.414213562 | C3 |
| Muhammad saudi | 3 | 2 | 1 | 1 | 3 | 2.828427125 | 2.645751311 | 1.732050808 | 1.732050808 | C3 |
| M.Hata Rajasa | 1 | 3 | 1 | 3 | 1 | 4.795831523 | 4.898979486 | 3.741657387 | 3.741657387 | C3 |
| Safarudin | 3 | 2 | 1 | 1 | 2 | 3.316624790 | 3.162277660 | 1.414213562 | 1.414213562 | C3 |
| Randi Sulpadila | 2 | 2 | 2 | 1 | 2 | 3.316624790 | 3.464101615 | 2.449489743 | 2.449489743 | C3 |
| Pahrul | 3 | 2 | 1 | 1 | 2 | 3.316624790 | 3.162277660 | 1.414213562 | 1.414213562 | C3 |
| Juniper Aditansil | 2 | 2 | 1 | 1 | 1 | 4.358898944 | 4.242640687 | 2.449489743 | 2.449489743 | C3 |

### Ringkasan Hasil Clustering
- **Cluster C1 (Rendah)**: 7 penduduk
- **Cluster C2 (Tinggi)**: 2 penduduk
- **Cluster C3 (Menengah)**: 11 penduduk

## Struktur Data

### Data Penduduk (data_masukan.csv)
Data penduduk berisi informasi:
- NIK (Nomor Induk Kependudukan)
- Nama
- Tahun
- Jenis Kelamin (L/P)
- Usia
- RT
- Tanggungan (jumlah anggota keluarga)
- Kondisi Rumah (baik/cukup/kurang)
- Status Kepemilikan (hak milik/numpang/sewa)
- Penghasilan (per bulan)

## Cara Penggunaan

1. Login ke sistem
2. Masukkan data penduduk
3. Lakukan proses clustering
4. Cek hasil pengelompokan dengan mencari NIK penduduk

## Teknologi

- Laravel 10
- PHP 8.1
- MySQL
- Bootstrap 5
- Font Awesome 6

## Instalasi

1. Clone repository
2. Install dependencies: `composer install`
3. Copy .env.example ke .env
4. Generate key: `php artisan key:generate`
5. Setup database di .env
6. Migrate database: `php artisan migrate`
7. Seed data: `php artisan db:seed`
8. Jalankan server: `php artisan serve`

## Kontributor

- Septriani
- Fadi
