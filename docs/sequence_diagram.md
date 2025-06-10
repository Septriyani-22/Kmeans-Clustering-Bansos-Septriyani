# Sequence Diagram - K-means Clustering Process

```mermaid
sequenceDiagram
    actor Admin
    participant DC as DashboardController
    participant CC as ClusteringController
    participant HK as HasilKmeans Model
    participant C as Centroid Model
    participant P as Penduduk Model
    participant DB as Database

    Admin->>DC: Access Dashboard
    DC->>P: Get Total Penduduk
    P->>DB: Count Records
    DB-->>P: Return Count
    P-->>DC: Return Total
    DC->>HK: Get Clustering Results
    HK->>DB: Query Results
    DB-->>HK: Return Data
    HK-->>DC: Return Results
    DC->>Admin: Display Dashboard

    Admin->>CC: Access Clustering Page
    CC->>P: Get All Penduduk
    P->>DB: Query Records
    DB-->>P: Return Data
    P-->>CC: Return Penduduk List

    Admin->>CC: Submit Clustering Request
    CC->>HK: Delete Existing Results
    HK->>DB: Delete Records
    CC->>C: Delete Existing Centroids
    C->>DB: Delete Records

    CC->>P: Get Random Penduduk
    P->>DB: Query Random Records
    DB-->>P: Return Data
    P-->>CC: Return Random Data

    loop For Each Random Record
        CC->>C: Create Initial Centroid
        C->>DB: Insert Record
        DB-->>C: Confirm Insert
        C-->>CC: Return Centroid
    end

    loop Until Convergence
        loop For Each Penduduk
            CC->>CC: Calculate Distances
            CC->>CC: Assign to Nearest Cluster
        end

        loop For Each Centroid
            CC->>CC: Calculate New Centroid Position
            CC->>C: Update Centroid
            C->>DB: Update Record
            DB-->>C: Confirm Update
        end
    end

    loop For Each Penduduk
        CC->>HK: Save Clustering Result
        HK->>DB: Insert Record
        DB-->>HK: Confirm Insert
        HK-->>CC: Return Result
    end

    CC->>Admin: Redirect to Results
```

## Description

This sequence diagram illustrates the flow of the K-means clustering process in the application:

1. **Dashboard Access**
   - Admin accesses the dashboard
   - System retrieves total penduduk count
   - System retrieves clustering results
   - Dashboard displays statistics and charts

2. **Clustering Process**
   - Admin accesses clustering page
   - System retrieves all penduduk data
   - Admin initiates clustering process
   - System clears existing results and centroids
   - System creates initial centroids from random penduduk
   - System performs clustering iterations until convergence
   - System saves final clustering results

3. **Key Components**
   - DashboardController: Handles dashboard display
   - ClusteringController: Manages clustering process
   - HasilKmeans Model: Stores clustering results
   - Centroid Model: Manages cluster centroids
   - Penduduk Model: Contains penduduk data

4. **Process Flow**
   - Data preparation
   - Initial centroid selection
   - Iterative clustering
   - Result storage
   - Dashboard update 