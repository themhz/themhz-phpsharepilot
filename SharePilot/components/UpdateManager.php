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
        $this->emptyDirectory($this->tempDir);
      
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
            throw new \Exception("Unable to open file: $zipFile");
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
    
       
        // Extract using ZipArchive
        $zip = new \ZipArchive();
        if ($zip->open($zipFile) === true) {
            $zip->extractTo($this->tempDir); // Extract to the $this->tempDir directory
            $zip->close();
    
            // Find the dynamically-named top-level directory
            $directories = glob($this->tempDir . '/*', GLOB_ONLYDIR); // Get all directories in the $this->tempDir directory
            if (count($directories) === 1) {
                $topLevelDirectory = $directories[0]; // The dynamically-named top-level directory
            } else {
                throw new \Exception("Failed to find the dynamically-named top-level directory.");
            }
    
            // Move the files and directories to the $this->tempDir directory
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($topLevelDirectory, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
    
            foreach ($files as $fileInfo) {
                $targetPath = $this->tempDir . DIRECTORY_SEPARATOR . $files->getSubPathName();
                if ($fileInfo->isDir()) {
                    @mkdir($targetPath); // Create directory if it doesn't exist
                } else {
                    copy($fileInfo->getPathname(), $targetPath);
                    unlink($fileInfo->getPathname()); // Remove the original file
                }
            }
    
            // Remove the top-level directory and its contents
            $this->deleteDir($topLevelDirectory);
        } else {
            throw new \Exception("Failed to unzip file.");
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

    private function generateManifest($softwareVersion) {
        $directory = $_SERVER['DOCUMENT_ROOT'];
              
      
        // Define a local class inside the function to filter the directories        
        $excludeDirs = ['vendor', 'newpage', 'temp'];  // Add directories to exclude
        $excludedFiles = ['logfile.log', '.env', '.git'];
        // Create a Recursive Directory Iterator
    
       
        $directoryIterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
         
        // Use the defined filter to exclude directories
        $filterIterator = new \DirectoryFilter($directoryIterator, $excludeDirs, $excludedFiles);
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
                    'file' => $filename,
                    'file_content_hash' => $contentChecksum,                
                    'directory_path' => str_replace('\\', '/', $directoryPath),  // Normalize directory path
                    'directory_hash' => $pathChecksum,
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
    
    private function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, -1) != DIRECTORY_SEPARATOR) {
            $dirPath .= DIRECTORY_SEPARATOR;
        }
        $files = glob($dirPath . '{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE); // Including hidden files and directories
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function emptyDirectory($dir) {

     
        if (!file_exists($dir) || !is_dir($dir)) {
            return false;
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
           
            if (is_dir($itemPath)) {
                $this->emptyDirectory($itemPath); // Recursively empty subdirectories
                rmdir($itemPath); // Remove the now-empty subdirectory
            } else {               
                unlink($itemPath); // Delete file
            }
        }

        return true;
    }
    
    private function copyDir($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDir($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
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

    public function isValidSemVer($version) {
        $semverRegex = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)'
                     . '(?:-((?:0|[1-9]\d*|[a-zA-Z-][0-9a-zA-Z-]*)'
                     . '(?:\.(?:0|[1-9]\d*|[a-zA-Z-][0-9a-zA-Z-]*))*))?'
                     . '(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';
    
        return preg_match($semverRegex, $version) === 1;
    }        

    public function update() {        
        $tempDirProjectFilesPath = $this->tempDir . DIRECTORY_SEPARATOR . 'SharePilot' . DIRECTORY_SEPARATOR;
        $newManifestPath = $tempDirProjectFilesPath . 'manifest.json';
        $currentManifestPath = 'manifest.json';
        $logFilePath = 'update.log';

        $newManifest = json_decode(file_get_contents($newManifestPath), true);
        $currentManifest = json_decode(file_get_contents($currentManifestPath), true);
        
        /*print_r($newManifest["sharepilot"]["version"]);
        die();*/
        // Open the log file in append mode
        $logFile = fopen($logFilePath, 'a');

        // Update and add new files
        foreach ($newManifest["sharepilot"]["files"] as $newfile) {
            $currentFile = $this->findFileInJson($newfile);
            if ($currentFile != null) {
                $newFilePath = ($newfile["directory_path"] == ".") ? 
                    $tempDirProjectFilesPath . $newfile["file"] : 
                    $tempDirProjectFilesPath . $newfile["directory_path"] . DIRECTORY_SEPARATOR . $newfile["file"];
                    
                $currentFilePath = ($currentFile["directory_path"] == ".") ? 
                    $currentFile["file"] : 
                    $currentFile["directory_path"] . DIRECTORY_SEPARATOR . $currentFile["file"];
    
                if ($currentFile["file_content_hash"] != $newfile["file_content_hash"]) {
                    echo $newFilePath . " will replace: " . $currentFilePath . "\n";
                    $logMessage = $newFilePath . " will replace: " . $currentFilePath . "\n";
                    fwrite($logFile, $logMessage);
                    copy($newFilePath, $currentFilePath);
                } elseif ($currentFile["directory_path"] != $newfile["directory_path"]) {
                    echo $newFilePath . " will move to: " . $currentFilePath . "\n";
                    $logMessage = $newFilePath . " will move to: " . $currentFilePath . "\n";
                    fwrite($logFile, $logMessage);
                    rename($currentFilePath, $newFilePath);
                }
            } else {
                // Adding a new file
                $newFilePath = ($newfile["directory_path"] == ".") ? 
                    $tempDirProjectFilesPath . $newfile["file"] : 
                    $tempDirProjectFilesPath . $newfile["directory_path"] . DIRECTORY_SEPARATOR . $newfile["file"];
                $destinationPath = ($newfile["directory_path"] == ".") ? 
                    $newfile["file"] : 
                    $newfile["directory_path"] . DIRECTORY_SEPARATOR . $newfile["file"];
                
                echo $newFilePath . " is a new file.\n";
                $logMessage = $newFilePath . " is a new file.\n";
                fwrite($logFile, $logMessage);
                copy($newFilePath, $destinationPath);
            }
        }
    
        // Check for deleted files
        foreach ($currentManifest["sharepilot"]["files"] as $currentFile) {
            $fileExists = false;
            foreach ($newManifest["sharepilot"]["files"] as $newfile) {
                if ($newfile["file"] == $currentFile["file"] && $newfile["directory_path"] == $currentFile["directory_path"]) {
                    $fileExists = true;
                    break;
                }
            }
            if (!$fileExists) {
                $currentFilePath = ($currentFile["directory_path"] == ".") ? 
                    $currentFile["file"] : 
                    $currentFile["directory_path"] . DIRECTORY_SEPARATOR . $currentFile["file"];
                    
                echo "Deleting file: " . $currentFilePath . "\n";
                fwrite($logFile, $logMessage);
                unlink($currentFilePath);
            }
        }
    
        // Close the log file
        fclose($logFile);


        //$this->generateManifest($newManifest["sharepilot"]["version"]);

        return ["result" => true, "message" => 'finished'];
    }
    
    public function findFileInJson($newfile) {
        $currentManifestPath = 'manifest.json';
        $currentManifest = json_decode(file_get_contents($currentManifestPath), true);
        foreach ($currentManifest["sharepilot"]["files"] as $currentFile) {
            if ($currentFile["file"] == $newfile["file"] && $currentFile["directory_path"] == $newfile["directory_path"]) {
                return $currentFile;
            }
        }
        return null;
    }
}