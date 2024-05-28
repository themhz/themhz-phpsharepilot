<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
use SharePilotV2\Components\UpdateManager;

class Controller
{
    public $baseurl;
    private $auth;

    public function __construct()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];  // Get the host from the server variables
        $base_url = $protocol . '://' . $host;  // Concatenate to form the base URL

        $this->baseurl = $base_url;
        
    }

    public function get(){
       
    }

    public function post(){
        
    }    

    public function update(){        
        try {
            $updateManager = new UpdateManager('Update.zip');        
            $filePath = 'Update.zip';
            $checksum = $updateManager->generateChecksum($filePath);
            echo "Checksum: " . $checksum;
        } catch (Exception $e) {
            // Here you might log the error and display a user-friendly message
            error_log($e->getMessage()); // Log the error to the server's error log or your custom log
            echo "Failed to update: " . $e->getMessage();
        }
    }

    public function checkupdate(){
        $currentVersion = 'v1.0.1';
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


}