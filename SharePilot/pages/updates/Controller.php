<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
use SharePilotV2\Components\EnvironmentDetails;
use SharePilotV2\Components\UpdateManager;



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

    public function generatechecksum(){        
        try {
            $updateManager = new UpdateManager('/', 'temp');           
            $message = $updateManager->generateManifest("v1.0.2");
            //echo "Checksum: " . $checksum;
            ResponseHandler::respond(["result"=>true, "message"=>$message]);
        } catch (Exception $e) {
            // Here you might log the error and display a user-friendly message
            //error_log($e->getMessage()); // Log the error to the server's error log or your custom log
            ResponseHandler::respond(["result"=>false, "message"=>$e->getMessage()]);            
        }
    }

    public function checkupdate(){
        $currentVersion = 'v1.0.0';        
        $url = "https://api.github.com/repos/themhz/themhz-phpsharepilot/releases/latest";

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