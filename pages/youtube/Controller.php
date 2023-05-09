<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\Config;

 class Controller{
    public function get(){





    }

    public function getvideo(){
        $youtubeapiKey= Config::read('youtubeapiKey');
        $youtubeService = new YoutubeService($youtubeapiKey);
        $searchQuery= "";
        $videoCategoryId = '10';

        if(!isset($_POST["txtsearch"]) || empty($_POST["txtsearch"])){
            $searchQuery = 'boxing workout, motivation, hip hop, rap';

        }else{
            $searchQuery = $_POST["txtsearch"];
            $videoCategoryId = null;
        }

        $maxResults = 50;
        $videos = $youtubeService->getVideosBySearchQuery($searchQuery, $maxResults, 0, $videoCategoryId);

        //print_r($videos);
        $json = json_encode($videos, JSON_PRETTY_PRINT);

        echo $json;
        //die();
        //return $json;
    }
 }

 