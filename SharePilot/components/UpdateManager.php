<?php
namespace SharePilotV2\Components;
use SharePilotV2\Components\EnvironmentDetails;
use SharePilotV2\Components\DirectoryFilter;

class UpdateManager {    
    private $tempDir;
    private $projectDir;

    public function __construct($projectDir, $tempDir) {
        $this->projectDir = $projectDir;
        $this->tempDir = $tempDir;
    }

    public function downloadAndUnzipRelease($url) {
        $startTime = microtime(true);  // Start timer
    
        $zipFile = $this->tempDir . '/release.zip';  // Path to save the downloaded zip file
    
        // Initialize curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);  // Direct output to file instead of memory
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // Increase timeout for large files
        curl_setopt($ch, CURLOPT_USERAGENT, 'sharepilot (https://sharepilot.gr)');
    
        // Open file handle
        $fp = fopen($zipFile, 'w+');
        if (!$fp) {
            throw new Exception("Unable to open file: $zipFile");
        }
        curl_setopt($ch, CURLOPT_FILE, $fp);
    
        // Execute download
        curl_exec($ch);
        if (curl_errno($ch)) {
            fclose($fp); // Close the file handle
            unlink($zipFile); // Delete partial file
            throw new \Exception('Curl error: ' . curl_error($ch));
        }
        fclose($fp);
        curl_close($ch);
    
        // Check if download was successful
        if (!filesize($zipFile)) {
            throw new \Exception("Failed to download file.");
        }
    
        $downloadTime = microtime(true);  // End timer for download
        $downloadDuration = $downloadTime - $startTime;
    
        // Extract using 7z
        ini_set('memory_limit', '4048M');
        set_time_limit(300); // Ensure the script has enough time to execute
    
        //$command = "7z x -y '$zipFile' -o'{$this->tempDir}'"; // Using 7z for extraction
        $command = "7z x -y -mmt=on '$zipFile' -o'{$this->tempDir}'"; // Using 7z for extraction with multi-threading enabled
        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            throw new \Exception("Failed to unzip file: " . implode("\n", $output));
        }
    
        unlink($zipFile); // Optionally delete the zip file after extracting
    
        $endTime = microtime(true);  // End timer for unzip
        $unzipDuration = $endTime - $downloadTime;
        $totalDuration = $endTime - $startTime;
    
        return [
            'download_duration' => round($downloadDuration, 2),
            'unzip_duration' => round($unzipDuration, 2),
            'total_duration' => round($totalDuration, 2),
            'success' => true
        ];
    }
    
    
    
    public function generateManifest($softwareVersion) {
        $directory = $_SERVER['DOCUMENT_ROOT'];
        if (empty($directory)) {
            $directory = getcwd();
        }
       
        // Define a local class inside the function to filter the directories        
        $excludeDirs = ['vendor', 'newpage', 'temp'];  // Add directories to exclude
        $excludedFiles = ['logfile.log', '.env', '.git'];
        // Create a Recursive Directory Iterator
        $directoryIterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        // Use the defined filter to exclude directories
        $filterIterator = new DirectoryFilter($directoryIterator, $excludeDirs, $excludedFiles);
        // Flatten the iterator
        $files = new \RecursiveIteratorIterator($filterIterator, \RecursiveIteratorIterator::SELF_FIRST);
    
        $fileDetails = [];
    
        foreach ($files as $file) {
            // Only include files in the manifest
            if ($file->isFile()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($directory) + 1);
    
                // Extract filename and directory
                $filename = basename($filePath);
                $directoryPath = dirname($relativePath);
    
                // Generate SHA-256 checksum for the file content
                $contentChecksum = hash_file('sha256', $filePath);
    
                // Generate SHA-256 checksum for the file path
                $pathChecksum = hash('sha256', $relativePath);
    
                // Add file info to the file details array
                $fileDetails[] = [
                    'filename' => $filename,
                    'content_hash' => $contentChecksum,
                    'path_hash' => $pathChecksum,
                    'directory' => str_replace('\\', '/', $directoryPath)  // Normalize directory path
                ];
            }
        }
    
        // Define the software version
        //$softwareVersion = '1.0.0';  // Replace with your actual version
    
        // Create the manifest with version and files
        $manifest = [
            'sharepilot' => [
                'version' => $softwareVersion,
                'files' => $fileDetails
            ]
        ];
    
        // Save the manifest as a JSON file in the directory
        file_put_contents($directory . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
        return "Manifest generated successfully.";
    }
    
    public function updateProjectFromManifest() {
        $newManifestPath = $this->tempDir . '/manifest.json';
        $currentManifestPath = $this->projectDir . '/manifest.json';

        $newManifest = json_decode(file_get_contents($newManifestPath), true);
        $currentManifest = json_decode(file_get_contents($currentManifestPath), true);

        // Update and add new files
        foreach ($newManifest as $file => $info) {
            $newFilePath = $this->tempDir . '/' . $info['directory'] . '/' . $file;
            $projectFilePath = $this->projectDir . '/' . $info['directory'] . '/' . $file;

            // Check if file needs to be updated or is new
            if (!isset($currentManifest[$file]) || $currentManifest[$file]['content_hash'] !== $info['content_hash']) {
                // Ensure the directory exists
                if (!is_dir(dirname($projectFilePath))) {
                    mkdir(dirname($projectFilePath), 0777, true);
                }
                copy($newFilePath, $projectFilePath);
            }
        }

        // Remove old files
        foreach ($currentManifest as $file => $info) {
            $projectFilePath = $this->projectDir . '/' . $info['directory'] . '/' . $file;
            if (!isset($newManifest[$file])) {
                unlink($projectFilePath);
            }
        }

        //echo "Update completed successfully.";
        return;
    }


    public function checkupdate($currentVersion, $repo){
        //$currentVersion = 'v1.0.0';
        //$repo = 'themhz/themhz-phpsharepilot';  // Your GitHub repository
        $url = "https://api.github.com/repos/themhz/themhz-phpsharepilot/releases/latest";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Update Checker');

        $response = curl_exec($ch);
        curl_close($ch);
        $releaseInfo = json_decode($response, true);        

        if ($releaseInfo && $releaseInfo['tag_name'] != $currentVersion) {
            //echo "A new version is available: " . $releaseInfo['tag_name'];
            //ResponseHandler::respond(["result"=>true, "message"=>$releaseInfo['tag_name']]);
            //echo "\nPlease update at: " . $releaseInfo['html_url'];
            return ["result"=>true, "message"=>$releaseInfo['tag_name']];
        } else {
            //echo "You are using the latest version.";
            return ["result"=>false, "message"=>$releaseInfo];
        }
    }
}
