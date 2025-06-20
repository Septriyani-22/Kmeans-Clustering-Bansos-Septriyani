@startuml

' ======================
'   CLASS DIAGRAM DB STYLE + REAL FUNCTION + CARDINALITY
' ======================

entity "users" as User {
  * id: bigint [PK]
  * name: varchar(255)
  * username: varchar(255) [UNIQUE]
  * email: varchar(255) [UNIQUE]
  * password: varchar(255)
  * role: varchar(20)
  * created_at: timestamp
  * updated_at: timestamp
  --
  +isAdmin()
  +isUser()
  +getAuthIdentifierName()
}

entity "penduduk" as Penduduk {
  * id: bigint [PK]
  * no: int
  * nik: varchar(20) [UNIQUE]
  * nama: varchar(255)
  * tahun: int
  * jenis_kelamin: enum('L','P')
  * usia: int
  * rt: int
  * tanggungan: int
  * kondisi_rumah: enum('baik','cukup','kurang')
  * status_kepemilikan: enum('hak milik','numpang','sewa')
  * penghasilan: decimal(12,2)
  * created_at: timestamp
  * updated_at: timestamp
  --
  +getFormattedPenghasilanAttribute()
  +getNilaiKriteria()
  +getKriteriaOptions()
  +getJenisKelaminTextAttribute()
}

entity "kriteria" as Kriteria {
  * id: bigint [PK]
  * nama: varchar(100)
  * deskripsi: text
  * tipe_kriteria: varchar(50)
  * created_at: timestamp
  * updated_at: timestamp
  --
  +getTipeKriteria()
  +getNilaiUsia()
  +getNilaiTanggungan()
  +getNilaiKondisiRumah()
  +getNilaiStatusKepemilikan()
  +getNilaiPenghasilan()
  +getKriteriaOptions()
  +nilaiKriteria()
  +getNilai()
  +getNilaiOptions()
}

entity "nilai_kriteria" as NilaiKriteria {
  * id: bigint [PK]
  * kriteria_id: bigint [FK]
  * nama: varchar(100)
  * nilai: int
  * nilai_min: decimal(10,2)
  * nilai_max: decimal(10,2)
  * created_at: timestamp
  * updated_at: timestamp
  --
  +kriteria()
}

entity "centroids" as Centroid {
  * id: bigint [PK]
  * usia: int
  * tanggungan_num: int
  * kondisi_rumah: varchar(20)
  * status_kepemilikan: varchar(20)
  * penghasilan_num: int
  * tahun: int
  * periode: int
  * created_at: timestamp
  * updated_at: timestamp
  --
  +hasilKmeans()
  +mappings()
  +getPenghasilanFormattedAttribute()
  +getInitialCentroids()
  +calculateDistance()
  +updateCentroid()
  +getClusterNameAttribute()
  +mapKondisiRumah()
  +mapStatusKepemilikan()
  +mapUsia()
  +normalizeValues()
}

entity "mapping_centroids" as MappingCentroid {
  * id: bigint [PK]
  * data_ke: int
  * nama_penduduk: varchar(255)
  * cluster: varchar(10)
  * usia: int
  * jumlah_tanggungan: int
  * kondisi_rumah: varchar(20)
  * status_kepemilikan: varchar(20)
  * jumlah_penghasilan: decimal(12,2)
  * created_at: timestamp
  * updated_at: timestamp
  --
  +centroid()
  +penduduk()
  +getNamaPendudukAttribute()
  +getUsiaAttribute()
  +getTanggunganAttribute()
  +getKondisiRumahAttribute()
  +getStatusKepemilikanAttribute()
  +getJumlahPenghasilanAttribute()
}

entity "hasil_kmeans" as HasilKmeans {
  * id: bigint [PK]
  * penduduk_id: bigint [FK]
  * centroid_id: bigint [FK]
  * cluster: int
  * jarak: float
  * iterasi: int
  * tahun: int
  * periode: int
  * created_at: timestamp
  * updated_at: timestamp
  --
  +penduduk()
  +centroid()
}

entity "hasil" as Hasil {
  * id: bigint [PK]
  * penduduk_id: bigint [FK]
  * cluster: int
  * jarak: decimal(10,4)
  * iterasi: int
  * created_at: timestamp
  * updated_at: timestamp
  --
  +getClusterNameAttribute()
  +getFormattedJarakAttribute()
  +penduduk()
  +centroid()
}

entity "hasil_clustering" as HasilClustering {
  * id: bigint [PK]
  * penduduk_id: bigint [FK]
  * cluster: int
  * created_at: timestamp
  * updated_at: timestamp
  --
  +penduduk()
  +getClusterNameAttribute()
  +getClusterDescriptionAttribute()
  +getClusterBadgeAttribute()
}

' ======================
'        RELASI (dengan cardinality)
' ======================
User "1" -- "*" Penduduk : ""
Kriteria "1" -- "*" NilaiKriteria : ""
Penduduk "1" -- "*" MappingCentroid : ""
Centroid "1" -- "*" MappingCentroid : ""
Penduduk "1" -- "*" HasilKmeans : ""
Centroid "1" -- "*" HasilKmeans : ""
Penduduk "1" -- "*" Hasil : ""
Penduduk "1" -- "*" HasilClustering : ""

' Relasi proses konversi data ke numerik untuk clustering
Penduduk ..> Kriteria : <<konversi numerik>>
Penduduk ..> NilaiKriteria : <<konversi numerik>>

@enduml
