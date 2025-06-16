# Sistem Optimalisasi Penyaluran Bantuan Sosial

Sistem ini menggunakan algoritma K-Means Clustering untuk mengelompokkan penerima bantuan sosial berdasarkan kriteria tertentu.

## Mapping Centroid Awal
| Centroid | Nama Penduduk | Usia | Tanggungan | Kondisi Rumah | Status Kepemilikan | Penghasilan |
|----------|---------------|------|------------|---------------|-------------------|-------------|
| C1 | Riduan | 4 | 3 | 3 | 2 | 4 |
| C2 | UJANG | 4 | 4 | 2 | 1 | 3 |
| C3 | ISMAIL | 4 | 3 | 1 | 1 | 1 |

## Hasil Perhitungan Jarak dan Penentuan Cluster

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
