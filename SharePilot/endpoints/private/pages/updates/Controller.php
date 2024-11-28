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

    public function get($id = null, $method = 'GET', $templatePath = null) {
        
        //echo "Welcome to the Default Action!";
        // Specify the content file
        $content = dirname(__FILE__) . '/updates.php';

        // Include the master template
        if ($templatePath) {
            include $templatePath;
        } else {
            echo "Master template not found!";
        }
    }

    public function post(){
        
    }

    public function checkupdate(){                
        $updateManager = new UpdateManager('/', 'temp');             
        $result = $updateManager->checkupdate();
        ResponseHandler::respond($result);
    }
   

    public function downloadupdate(){     
        $updateManager = new UpdateManager('/', 'temp');        
        $result = $updateManager->checkupdate();

        if($result["result"] == true){
            $url = 'https://api.github.com/repos/themhz/themhz-phpsharepilot/zipball/'.$result["version"];
        
            try {
                $result = $updateManager->downloadAndUnzipRelease($url);                         
                               
                if ($result["success"]) {
                    //$updateManager->updateProjectFromManifest();
                    //asasdasd
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
        }else{
            ResponseHandler::respond($result);
        }
        
    }

    public function update(){        
        $updateManager = new UpdateManager('/', 'temp');        
        $result = $updateManager->update();
        ResponseHandler::respond($result);
    }


    public function downloadandupdate(){
        $updateManager = new UpdateManager('/', 'temp');        
        $result = $updateManager->checkupdate();

        if($result["result"] == true){
            $url = 'https://api.github.com/repos/themhz/themhz-phpsharepilot/zipball/'.$result["version"];
        
            try {
                $result = $updateManager->downloadAndUnzipRelease($url);                         
                               
                if ($result["success"]) {
                    $result = $updateManager->update();
                    // ResponseHandler::respond(["result"=>true, 
                    // "message"=>"was downloaded and unziped",
                    // "download_duration"=>$result["download_duration"], 
                    // "unzip_duration"=>$result["unzip_duration"], 
                    // "total_duration"=>$result["total_duration"]]);
                    ResponseHandler::respond($result);
                }else{
                    ResponseHandler::respond(["result"=>false, "message"=>"there was a problem downloading the release"]);   
                }
            } catch (Exception $e) {
                //echo "An error occurred: " . $e->getMessage();
                ResponseHandler::respond(["result"=>true, "message"=>"An error occurred: " . $e->getMessage()]);
            }
        }else{
            ResponseHandler::respond($result);
        }
    }

   

   

}