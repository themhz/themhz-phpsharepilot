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



    public function checkupdate(){
        $url = "https://api.github.com/repos/themhz/themhz-phpsharepilot/releases/latest";
        $currentVersion = $this->getcurrentversion();
        
        //$repo = 'themhz/themhz-phpsharepilot';  // Your GitHub repository
        //$url = "https://api.github.com/repos/themhz/themhz-phpsharepilot/releases/latest";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Update Checker');

        $response = curl_exec($ch);
        curl_close($ch);
        $releaseInfo = json_decode($response, true);

        if (version_compare($releaseInfo['tag_name'],$currentVersion , '>')) {
            //echo "New version available please update from $currentVersion to ".$releaseInfo['tag_name'];
            return ["result"=>true, "message"=>"New version available please update from $currentVersion to ".$releaseInfo['tag_name'], "version"=>$releaseInfo['tag_name']];
        }else{

            return ["result"=>false, "message"=>"no update available"];
        }

        // if ($releaseInfo['tag_name'] != $currentVersion) {
        //     //echo "A new version is available: " . $releaseInfo['tag_name'];
        //     //ResponseHandler::respond(["result"=>true, "message"=>$releaseInfo['tag_name']]);
        //     //echo "\nPlease update at: " . $releaseInfo['html_url'];
        //     return ["result"=>true, "message"=>$releaseInfo['tag_name']];
        // } else {
        //     //echo "You are using the latest version.";
        //     return ["result"=>false, "message"=>$releaseInfo];
        // }
    }

    public function updateVersionOnManifest($newVersion) {
        $manifestFile = 'manifest.json';  // Adjust the file path as necessary
    
        // Load the manifest file
        if (!file_exists($manifestFile)) {
            //throw new \Exception("Manifest file not found. in $manifestFile");           
            return ["result"=>false, "message"=>"Manifest file not found. in $manifestFile"];
        }
    
        $manifestContent = file_get_contents($manifestFile);
        $manifest = json_decode($manifestContent, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            //throw new \Exception('Invalid JSON in manifest file.');
            return ["result"=>false, "message"=>'Invalid JSON in manifest file.'];
        }
    
        // Validate SemVer format
        if (!$this->isValidSemVer($newVersion)) {
            //throw new \Exception('Invalid Semantic Versioning format.');
            return ["result"=>false, "message"=>'Invalid Semantic Versioning format.'];
        }
    
        // Compare current version with new version
        $currentVersion = $manifest['sharepilot']['version'];
        if (version_compare($currentVersion, $newVersion, '>=')) {
            //throw new \Exception('New version must be greater than the current version.');
            return ["result"=>false, "message"=>'New version must be greater than the current version.'];
            
        }
    
        // Update version in manifest
        $manifest['sharepilot']['version'] = $newVersion;
        $newManifestContent = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            //throw new \Exception('Failed to encode JSON.');
            return ["result"=>false, "message"=>'Failed to encode JSON.'];
        }
    
        // Save the updated manifest file
        file_put_contents($manifestFile, $newManifestContent);
    
        //echo "Version updated successfully to $newVersion.\n";
        return ["result"=>true, "message"=>'Failed to encode JSON.'];
    }


    public function getcurrentversion() {
        $manifestFile = 'manifest.json';  // Adjust the file path as necessary
    
        // Load the manifest file
        if (!file_exists($manifestFile)) {            
            return ["result"=>false, "message"=>"Manifest file not found. in $manifestFile"];
        }
    
        $manifestContent = file_get_contents($manifestFile);
        $manifest = json_decode($manifestContent, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {            
            return ["result"=>false, "message"=>'Invalid JSON in manifest file.'];
        }            
    
        // Compare current version with new version
        $currentVersion = $manifest['sharepilot']['version'];
        return $currentVersion;
    }
        
}
