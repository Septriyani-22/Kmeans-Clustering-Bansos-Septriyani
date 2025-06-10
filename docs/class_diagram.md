# Class Diagram - K-means Clustering Application

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string password
        +string role
        +timestamps()
    }

    class Penduduk {
        +int id
        +string nik
        +string nama
        +int usia
        +string tanggungan
        +string kondisi_rumah
        +string status_kepemilikan
        +string penghasilan
        +int tahun
        +timestamps()
    }

    class Centroid {
        +int id
        +string nama_centroid
        +int usia
        +float tanggungan_num
        +string kondisi_rumah
        +string status_kepemilikan
        +float penghasilan_num
        +int tahun
        +int periode
        +timestamps()
    }

    class HasilKmeans {
        +int id
        +int penduduk_id
        +int centroid_id
        +int cluster
        +float jarak
        +int iterasi
        +int tahun
        +int periode
        +timestamps()
    }

    class ClusteringController {
        +index()
        +proses(Request $request)
        +reset()
        -initializeCentroids($jumlahCluster)
        -convertKondisiRumah($value)
        -reverseConvertKondisiRumah($value)
        -convertStatusKepemilikan($value)
        -reverseConvertStatusKepemilikan($value)
        -convertToNumeric($value)
        -determineClusterNumber($centroid, $distances)
    }

    class DashboardController {
        +index()
    }

    class HasilKmeansController {
        +index()
    }

    %% Relationships
    User "1" -- "1" Penduduk : manages
    Penduduk "1" -- "1" HasilKmeans : has
    Centroid "1" -- "*" HasilKmeans : has
    ClusteringController --> Penduduk : uses
    ClusteringController --> Centroid : manages
    ClusteringController --> HasilKmeans : creates
    DashboardController --> Penduduk : queries
    DashboardController --> HasilKmeans : displays
    HasilKmeansController --> HasilKmeans : manages
```

## Class Descriptions

### Models

1. **User**
   - Represents system users (admin)
   - Manages authentication and authorization
   - Attributes: id, name, email, password, role

2. **Penduduk**
   - Represents citizen data
   - Contains demographic and economic information
   - Attributes: id, nik, nama, usia, tanggungan, kondisi_rumah, status_kepemilikan, penghasilan, tahun

3. **Centroid**
   - Represents cluster centers
   - Stores centroid values for each iteration
   - Attributes: id, nama_centroid, usia, tanggungan_num, kondisi_rumah, status_kepemilikan, penghasilan_num, tahun, periode

4. **HasilKmeans**
   - Stores clustering results
   - Links penduduk to their assigned clusters
   - Attributes: id, penduduk_id, centroid_id, cluster, jarak, iterasi, tahun, periode

### Controllers

1. **ClusteringController**
   - Manages the K-means clustering process
   - Methods:
     - index(): Display clustering page
     - proses(): Execute clustering
     - reset(): Clear clustering results
     - Various helper methods for data conversion

2. **DashboardController**
   - Manages dashboard display
   - Methods:
     - index(): Display dashboard with statistics

3. **HasilKmeansController**
   - Manages clustering results
   - Methods:
     - index(): Display clustering results

## Relationships

1. **User-Penduduk**
   - One-to-one relationship
   - User manages penduduk data

2. **Penduduk-HasilKmeans**
   - One-to-one relationship
   - Each penduduk has one clustering result

3. **Centroid-HasilKmeans**
   - One-to-many relationship
   - Each centroid can have multiple results

4. **Controller-Model Relationships**
   - Controllers use models to perform operations
   - Controllers manage data flow and business logic 