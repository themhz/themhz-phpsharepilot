<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
use SharePilotV2\Components\EnvironmentDetails;
use SharePilotV2\Components\UpdateManager;

class Controller{
    public function get($id = null, $method = 'GET', $templatePath = null) {
        
        //echo "Welcome to the Default Action!";
        // Specify the content file
        $content = dirname(__FILE__) . '/help.php';

        // Include the master template
        if ($templatePath) {
            include $templatePath;
        } else {
            echo "Master template not found!";
        }
    }

    public function getversion(){
        $updateManager = new UpdateManager('/', 'temp');        
        $version = $updateManager->getcurrentversion();
        ResponseHandler::respond(["result"=>true, "message"=>$version]);   
    }
    
}
 
