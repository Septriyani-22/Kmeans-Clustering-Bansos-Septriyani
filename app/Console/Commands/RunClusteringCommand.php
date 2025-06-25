<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\ClusteringController;
use Illuminate\Http\Request;
use App\Models\HasilKmeans;
use App\Models\MappingCentroid;

class RunClusteringCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clustering:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run K-Means clustering process automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Check if mapping centroids exist
            $mappings = MappingCentroid::count();
            if ($mappings == 0) {
                $this->info('No mapping centroids found. Please set up mapping centroids first.');
                return 0;
            }

            // Check if clustering results already exist
            $existingResults = HasilKmeans::count();
            if ($existingResults > 0) {
                $this->info('Clustering results already exist. Skipping...');
                return 0;
            }

            $this->info('Starting K-Means clustering process...');
            
            // Create a mock request
            $request = new Request();
            
            // Run clustering process
            $clusteringController = new ClusteringController();
            $clusteringController->calculateDistances();
            
            $this->info('Clustering process completed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error running clustering process: ' . $e->getMessage());
            return 1;
        }
    }
}
