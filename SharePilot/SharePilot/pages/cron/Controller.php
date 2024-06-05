<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Models\Settings;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\ISocialMediaService;
use SharePilotV2\Components\PostingService;
use SharePilotV2\Implementations\Facebook;

class Controller{
    public function Get(){
        $settings = new Settings();        
        $result = $settings->select()->execute()[0];
        ResponseHandler::respond(["token"=>$result["crontoken"]]);            
    }    

    public function Post(){
        $postingService = new PostingService();
        $postingService->start();
    }    
    
    public function checkcrontab(){                
        $output = shell_exec('service cron status');
        ResponseHandler::respond(["cronstatus"=>$output]);            
    }

    public function createcrontoken(){
        // Get the current date and time as a string.
        $currentDateTime = date('Y-m-d H:i:s');

        // Use the current timestamp to add uniqueness.
        $currentTimestamp = time();

        // Concatenate the date time string with the timestamp.
        $sourceString = $currentDateTime . $currentTimestamp;

        // Generate a hash token using SHA-256.
        $hashToken = hash('sha256', $sourceString);

        $settings = new Settings();
        $settings->crontoken =  $hashToken;
        $result = $settings->update()->execute();
                

        // Output the hash token.    
        ResponseHandler::respond(["token"=>$hashToken]);            

    }


    
}
 
