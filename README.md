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
   - 2 = 25-35 Tahun
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
   - 1 = >4000000
   - 2 = 3000000-4000000
   - 3 = 2000000-3000000
   - 4 = 1000000-2000000

### Keterangan Cluster
- **C1**: Sangat membutuhkan bantuan (Rendah)
- **C2**: Tidak membutuhkan bantuan (Tinggi)
- **C3**: Prioritas sedang (Menengah)

## Mapping Centroid

### Centroid Awal
| Centroid | Nama Penduduk | Usia | Tanggungan | Kondisi Rumah | Status Kepemilikan | Penghasilan |
|----------|---------------|------|------------|---------------|-------------------|-------------|
| C1 | Riduan | 4 | 3 | 3 | 2 | 4 |
| C2 | UJANG | 4 | 4 | 2 | 1 | 3 |
| C3 | ISMAIL | 4 | 3 | 1 | 1 | 1 |

## Hasil Clustering

### Hasil Perhitungan Jarak dan Penentuan Cluster
| Nama | Usia | Tanggungan | Kondisi Rumah | Status Kepemilikan | Penghasilan | Jarak ke C1 | Jarak ke C2 | Jarak ke C3 | Jarak Terdekat | Cluster |
|------|------|------------|---------------|-------------------|-------------|-------------|-------------|-------------|----------------|---------|
| ALBAR | 2 | 4 | 3 | 2 | 4 | 2.83 | 3.16 | 4.24 | 2.83 | C1 |
| IBRAHIM | 2 | 3 | 2 | 1 | 3 | 3.16 | 2.83 | 3.16 | 2.83 | C2 |
| KARNAIN | 2 | 3 | 1 | 1 | 2 | 3.74 | 3.16 | 2.83 | 2.83 | C3 |
| Riduan | 4 | 3 | 3 | 2 | 4 | 0.00 | 2.24 | 3.74 | 0.00 | C1 |
| Sarkowi | 4 | 3 | 3 | 1 | 4 | 1.00 | 2.45 | 3.74 | 1.00 | C1 |
| SUPARMI | 4 | 3 | 1 | 1 | 4 | 2.24 | 2.45 | 3.00 | 2.24 | C1 |
| UJANG | 4 | 4 | 2 | 1 | 3 | 2.24 | 0.00 | 2.45 | 0.00 | C2 |
| Usmina | 4 | 1 | 3 | 1 | 4 | 2.00 | 3.00 | 3.74 | 2.00 | C1 |
| SIAH | 4 | 1 | 3 | 1 | 4 | 2.00 | 3.00 | 3.74 | 2.00 | C1 |
| ISMAIL | 4 | 3 | 1 | 1 | 1 | 3.74 | 2.45 | 0.00 | 0.00 | C3 |
| M. YASIN | 3 | 4 | 1 | 1 | 1 | 3.32 | 2.24 | 1.41 | 1.41 | C3 |
| SAUYA | 4 | 1 | 3 | 1 | 4 | 2.00 | 3.00 | 3.74 | 2.00 | C1 |
| ROMNAH | 4 | 1 | 2 | 1 | 4 | 2.24 | 3.16 | 3.32 | 2.24 | C1 |
| Mat Sani | 3 | 4 | 1 | 1 | 2 | 3.32 | 2.24 | 1.73 | 1.73 | C3 |
| Muhammad saudi | 3 | 2 | 1 | 1 | 2 | 3.16 | 2.45 | 1.41 | 1.41 | C3 |
| M.Hata Rajasa | 1 | 3 | 1 | 1 | 1 | 4.24 | 3.74 | 3.00 | 3.00 | C3 |
| Safarudin | 3 | 2 | 1 | 1 | 1 | 3.32 | 2.83 | 1.73 | 1.73 | C3 |
| Randi Sulpadila | 2 | 2 | 2 | 1 | 2 | 3.46 | 2.83 | 2.24 | 2.24 | C3 |
| Pahrul | 3 | 2 | 1 | 1 | 2 | 3.16 | 2.45 | 1.41 | 1.41 | C3 |
| Juniper Aditansil | 2 | 2 | 1 | 1 | 1 | 3.74 | 3.16 | 2.24 | 2.24 | C3 |

### Ringkasan Hasil Clustering
- **Cluster C1 (Rendah)**: 8 penduduk
- **Cluster C2 (Tinggi)**: 2 penduduk
- **Cluster C3 (Menengah)**: 10 penduduk

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
