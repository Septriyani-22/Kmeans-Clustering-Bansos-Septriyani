# Sistem Bantuan Sosial - K-means Clustering

Sistem bantuan sosial berbasis web yang menggunakan algoritma K-means Clustering untuk mengelompokkan penduduk berdasarkan kriteria tertentu untuk menentukan kelayakan bantuan.

## Fitur Utama

- 🏠 Manajemen Data Penduduk
- 📊 K-means Clustering untuk Pengelompokan
- 📈 Dashboard dengan Visualisasi Data
- 👥 Manajemen Pengguna (Admin)
- 📋 Laporan Hasil Clustering

## Teknologi yang Digunakan

- PHP 8.1
- Laravel 10.x
- MySQL
- Bootstrap 5
- Chart.js
- AdminLTE 3

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM
- Web Server (Apache/Nginx)

## Instalasi

1. Clone repository
```bash
cd kmeans-clustering-app
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kmeans_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi dan seeder
```bash
php artisan migrate --seed
```

6. Compile assets
```bash
npm run dev
```

7. Jalankan server
```bash
php artisan serve
```

## Struktur Aplikasi

### Models
- `User`: Manajemen pengguna
- `Penduduk`: Data penduduk
- `Centroid`: Data centroid untuk clustering
- `HasilKmeans`: Hasil pengelompokan

### Controllers
- `DashboardController`: Manajemen dashboard
- `ClusteringController`: Proses K-means clustering
- `HasilKmeansController`: Manajemen hasil clustering
- `PendudukController`: Manajemen data penduduk

### Views
- Dashboard dengan visualisasi
- Form input data penduduk
- Halaman proses clustering
- Tampilan hasil clustering

## Algoritma K-means Clustering

Aplikasi menggunakan algoritma K-means untuk mengelompokkan penduduk berdasarkan kriteria:
1. Usia
2. Jumlah Tanggungan
3. Kondisi Rumah
4. Status Kepemilikan
5. Penghasilan

### Proses Clustering
1. Inisialisasi centroid secara random
2. Hitung jarak setiap data ke centroid
3. Kelompokkan data ke centroid terdekat
4. Update posisi centroid
5. Ulangi hingga konvergen

## Penggunaan

1. Login sebagai admin
2. Input data penduduk
3. Jalankan proses clustering
4. Lihat hasil di dashboard
5. Export hasil jika diperlukan

## Dokumentasi

- [Sequence Diagram](docs/sequence_diagram.md)
- [Class Diagram](docs/class_diagram.md)

## Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Distribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

## Kontak

Nama - [@your_twitter](https://twitter.com/your_twitter)
Email - your.email@example.com

Link Project: [https://github.com/your-username/kmeans-clustering-app](https://github.com/your-username/kmeans-clustering-app)

## Data Dummy dan Perhitungan Manual

### 1. Kriteria dan Bobot
Berdasarkan `dummy_kriteria.csv`, bobot untuk setiap kriteria adalah:
- Penghasilan: 30 poin
- Tanggungan: 25 poin
- Kondisi Rumah: 20 poin
- Status Kepemilikan Rumah: 15 poin
- Umur: 10 poin
Total bobot: 100 poin

### 2. Centroid (Pusat Cluster)
Berdasarkan `dummy_centroid.csv`, terdapat 3 cluster:
1. Cluster 1: Penghasilan rendah (1.5jt) & Tanggungan banyak (5)
2. Cluster 2: Penghasilan menengah (3jt) & Tanggungan sedang (3)
3. Cluster 3: Penghasilan tinggi (5jt) & Tanggungan sedikit (1)

### 3. Contoh Perhitungan Manual

#### Contoh 1: Andreas Tjandra (NIK: 100000000001)
- Penghasilan: 500-1 juta (70% dari 30 = 21 poin)
- Tanggungan: 3 orang (80% dari 25 = 20 poin)
- Kondisi Rumah: Baik (30% dari 20 = 6 poin)
- Status Kepemilikan: Milik sendiri (30% dari 15 = 4.5 poin)
- Umur: 41-50 (70% dari 10 = 7 poin)
Total Skor: 58.5 poin
Status: Layak (≥45 poin)

#### Contoh 2: Jessica Lim (NIK: 100000000004)
- Penghasilan: 500-1 juta (70% dari 30 = 21 poin)
- Tanggungan: 4 orang (80% dari 25 = 20 poin)
- Kondisi Rumah: Rusak (100% dari 20 = 20 poin)
- Status Kepemilikan: Sewa (80% dari 15 = 12 poin)
- Umur: 51-60 (90% dari 10 = 9 poin)
Total Skor: 82 poin
Status: Layak (≥45 poin)

#### Contoh 3: William Susanto (NIK: 100000000005)
- Penghasilan: Kurang Dari 500 (100% dari 30 = 30 poin)
- Tanggungan: 5 lebih (100% dari 25 = 25 poin)
- Kondisi Rumah: Baik (30% dari 20 = 6 poin)
- Status Kepemilikan: Sewa (80% dari 15 = 12 poin)
- Umur: 31-40 (50% dari 10 = 5 poin)
Total Skor: 78 poin
Status: Layak (≥45 poin)

### 4. Tabel Hasil Perhitungan (Contoh 10 Data)

| NIK | Nama | Penghasilan | Tanggungan | Kondisi Rumah | Status Kepemilikan | Umur | Total Skor | Status |
|-----|------|-------------|------------|---------------|-------------------|------|------------|---------|
| 100000000001 | Andreas Tjandra | 21 | 20 | 6 | 4.5 | 7 | 58.5 | Layak |
| 100000000002 | Meliana Gunawan | 9 | 15 | 6 | 4.5 | 5 | 39.5 | Tidak Layak |
| 100000000003 | Kevin Wijaya | 30 | 10 | 12 | 15 | 3 | 70 | Layak |
| 100000000004 | Jessica Lim | 21 | 20 | 20 | 12 | 9 | 82 | Layak |
| 100000000005 | William Susanto | 30 | 25 | 6 | 12 | 5 | 78 | Layak |
| 100000000006 | Clara Hartono | 21 | 15 | 12 | 4.5 | 7 | 59.5 | Layak |
| 100000000007 | Richard Santoso | 9 | 20 | 6 | 4.5 | 10 | 49.5 | Layak |
| 100000000008 | Stephanie Lie | 30 | 10 | 20 | 15 | 3 | 78 | Layak |
| 100000000009 | Leonardo Setiawan | 21 | 20 | 6 | 12 | 9 | 68 | Layak |
| 100000000010 | Michelle Tan | 9 | 15 | 12 | 4.5 | 5 | 45.5 | Layak |

### 5. Rumus Perhitungan

1. **Penghasilan**:
   - Kurang Dari 500: 100% dari 30 = 30 poin
   - 500-1 juta: 70% dari 30 = 21 poin
   - Lebih Dari 1 juta: 30% dari 30 = 9 poin

2. **Tanggungan**:
   - 5 lebih: 100% dari 25 = 25 poin
   - 3-4: 80% dari 25 = 20 poin
   - 2: 60% dari 25 = 15 poin
   - 1: 40% dari 25 = 10 poin

3. **Kondisi Rumah**:
   - Rusak: 100% dari 20 = 20 poin
   - Cukup: 60% dari 20 = 12 poin
   - Baik: 30% dari 20 = 6 poin

4. **Status Kepemilikan**:
   - Tidak Memiliki: 100% dari 15 = 15 poin
   - Sewa: 80% dari 15 = 12 poin
   - Milik sendiri: 30% dari 15 = 4.5 poin

5. **Umur**:
   - 61 keatas: 100% dari 10 = 10 poin
   - 51-60: 90% dari 10 = 9 poin
   - 41-50: 70% dari 10 = 7 poin
   - 31-40: 50% dari 10 = 5 poin
   - 21-30: 30% dari 10 = 3 poin

### 6. Penentuan Status
- Layak: Total skor ≥ 45 poin
- Tidak Layak: Total skor < 45 poin

### 7. Penentuan Cluster
Cluster ditentukan berdasarkan kedekatan dengan centroid:
1. Cluster 1: Penghasilan rendah & Tanggungan banyak
2. Cluster 2: Penghasilan menengah & Tanggungan sedang
3. Cluster 3: Penghasilan tinggi & Tanggungan sedikit

### 8. Kesimpulan
Dari 50 data dummy:
- Total data: 50 penduduk
- Layak: 38 penduduk (76%)
- Tidak Layak: 12 penduduk (24%)

Distribusi cluster:
- Cluster 1: 15 penduduk
- Cluster 2: 20 penduduk
- Cluster 3: 15 penduduk
