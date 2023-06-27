<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Components\ResponseHandler;

 class Controller{
    public function get(){
    }

    public function getvideo(){
        $youtubeapiKey= config::read('youtubeapiKey');
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

        $json = json_encode($videos, JSON_PRETTY_PRINT);

        echo $json;
    }

    public function addvideo(){
        $video_id = $_POST['video_id'];
        $title = $_POST['title'];
        $video_url = $_POST['video_url'];
        $thumbnail_url = $_POST['thumbnail_url'];
        $published_at = $_POST['published_at'];

        $urls =  new Urls();
        $result = $urls->add($video_id, $title, $video_url, $thumbnail_url, $published_at);

        ResponseHandler::respond($result);

    }


 }

 