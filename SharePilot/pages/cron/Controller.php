<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\ISocialMediaService;
use SharePilotV2\Components\PostingService;
use SharePilotV2\Implementations\Facebook;

class Controller{
    public function Get(){
       
    }    

    public function Post(){
        $postingService = new PostingService();
        $postingService->start();
    }    

    public function checkcrontab(){        
        // Check if shell_exec is available and enabled
        //if (function_exists('shell_exec') && is_callable('shell_exec')) {
            // Try to get the status of the cron service
            $output = shell_exec('service cron status');
            ResponseHandler::respond(["cronstatus"=>$output]);
            //print_r($output);
            // Check if the output contains a specific string indicating it's active
          /*  if (strpos($output, 'active (running)') !== false) {
                echo "Cron service is running.";
            } else {
                echo "Cron service is not running or not installed.";
            }*/
        //} else {
            // shell_exec is not available
          //  echo "Unable to check cron status because shell_exec is not available.";
        //}        
    }


    
}
 
