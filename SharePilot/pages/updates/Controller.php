<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
use SharePilotV2\Components\EnvironmentDetails;
use SharePilotV2\Components\UpdateManager;


class DirectoryFilter extends RecursiveFilterIterator {
    private $excludeDirs;
    private $excludedFiles;

    public function __construct(RecursiveIterator $iterator, array $excludeDirs = [], array $excludedFiles = []) {
        parent::__construct($iterator);
        $this->excludeDirs = $excludeDirs;
        $this->excludedFiles = $excludedFiles;
    }

    public function accept(): bool {
        $item = $this->current();

        // Exclude specified directories
        if ($item->isDir() && in_array($item->getFilename(), $this->excludeDirs)) {
            return false;
        }

        // Exclude specific files (adjust as needed)
        if ($item->isFile()) {
            $filename = $item->getFilename();           
            if (in_array($filename, $this->excludedFiles) || $filename === 'manifest.json') {
                return false;
            }
        }

        return true;
    }
}

class Controller{
    public $baseurl;
    private $auth;

    public function __construct()
    {                
        
    }

    public function get(){
       
    }

    public function post(){
        
    }    

    public function downloadandunzip(){     
        $updateManager = new UpdateManager('/', 'temp');
        $url = 'https://api.github.com/repos/themhz/themhz-phpsharepilot/zipball/v1.0.2';  // Adjust this URL

        try {
            $result = $updateManager->downloadAndUnzipRelease($url);
            if ($result["success"]) {
                //$updateManager->updateProjectFromManifest();
                
                ResponseHandler::respond(["result"=>true, 
                "message"=>"was downloaded and unziped",
                "download_duration"=>$result["download_duration"], 
                "unzip_duration"=>$result["unzip_duration"], 
                "total_duration"=>$result["total_duration"]]);
            }else{
                ResponseHandler::respond(["result"=>false, "message"=>"there was a problem downloading the release"]);   
            }
        } catch (Exception $e) {
            //echo "An error occurred: " . $e->getMessage();
            ResponseHandler::respond(["result"=>true, "message"=>"An error occurred: " . $e->getMessage()]);
        }

    }

    public function update(){        
        // try {
        //     $updateManager = new UpdateManager('Update.zip');        
        //     $filePath = 'Update.zip';
        //     $checksum = $updateManager->generateChecksum($filePath);
        //     echo "Checksum: " . $checksum;
        // } catch (Exception $e) {
        //     // Here you might log the error and display a user-friendly message
        //     error_log($e->getMessage()); // Log the error to the server's error log or your custom log
        //     echo "Failed to update: " . $e->getMessage();
        // }
    }

    public function checkupdate(){
        $currentVersion = 'v1.0.0';
        $repo = 'themhz/themhz-phpsharepilot';  // Your GitHub repository
        $url = "https://api.github.com/repos/$repo/releases/latest";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Update Checker');

        $response = curl_exec($ch);
        curl_close($ch);

        // if (!$response) {
        //     return false;
        // }

        $releaseInfo = json_decode($response, true);

        //print_r($releaseInfo);
        //return $releaseInfo;

        if ($releaseInfo && $releaseInfo['tag_name'] != $currentVersion) {
            //echo "A new version is available: " . $releaseInfo['tag_name'];
            ResponseHandler::respond(["result"=>true, "message"=>$releaseInfo['tag_name']]);
            echo "\nPlease update at: " . $releaseInfo['html_url'];
        } else {
            echo "You are using the latest version.";
        }
    }

    

    public function generateManifest() {
        $directory = $_SERVER['DOCUMENT_ROOT'];
        // Define a local class inside the function to filter the directories        
        $excludeDirs = ['vendor', 'newpage', 'temp'];  // Add directories to exclude
        $excludedFiles = ['logfile.log', '.env', '.git'];
        // Create a Recursive Directory Iterator
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        // Use the defined filter to exclude directories
        $filterIterator = new DirectoryFilter($directoryIterator, $excludeDirs, $excludedFiles);
        // Flatten the iterator
        $files = new RecursiveIteratorIterator($filterIterator, RecursiveIteratorIterator::SELF_FIRST);

        $manifest = [];

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

                // Add file info to the manifest array
                $manifest[$filename] = [
                    'content_hash' => $contentChecksum,
                    'path_hash' => $pathChecksum,
                    'directory' => str_replace('\\', '/', $directoryPath)  // Normalize directory path
                ];
            }
        }

        // Save the manifest as a JSON file in the directory
        file_put_contents($directory . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
        echo "Manifest generated successfully.";
    }
}