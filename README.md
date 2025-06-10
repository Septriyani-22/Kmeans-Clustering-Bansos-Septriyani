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
