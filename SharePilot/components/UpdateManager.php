<?php

namespace SharePilotV2\Components;

class UpdateManager {
    private $tempDir;
    private $projectDir;

    public function __construct($projectDir, $tempDir) {
        $this->projectDir = $projectDir;
        $this->tempDir = $tempDir;
    }

    public function downloadAndUnzipRelease($url) {
        $zipFile = $this->tempDir . '/release.zip';  // Path to save the downloaded zip file
        
        // Download the release zip file
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout in seconds
        curl_setopt($ch, CURLOPT_USERAGENT, 'sharepilot/1.0.1 (https://sharepilot.gr)'); // Set a User-Agent

        $download = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);

        if (!$download) {
            throw new Exception("Failed to download file.");
        }

        file_put_contents($zipFile, $download);

        
        $zip = new \ZipArchive();
          
        if ($zip->open($zipFile) === true) {            
            $zip->extractTo($this->tempDir);
            $zip->close();
            unlink($zipFile);  // Optionally delete the zip file after extracting
            return true;
        } else {
            //throw new Exception("Failed to unzip file.");
            return false;
        }
        
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

        echo "Update completed successfully.";
    }
}
