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


    
}
 
