<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\Config;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Components\ResponseHandler;

 class Controller{
    public function get(){


    }

    public function fetchTasks()
    {
        $u = new Scheduled_posts();
        $videos = $u->select();;
        ResponseHandler::respond($videos);
//        foreach ($videos as $video) {
//
//            $postDateValue = "";
//            $postTimeValue = "";
//            if (!is_null($video['post_time'])) {
//                $postDateTime = new DateTime($video['post_time']);
//                $postDateValue = $postDateTime->format('Y-m-d');
//                $postTimeValue = $postDateTime->format('H:i');
//            }
//
//            $rowClass = $video['is_posted'] == 1 ? 'posted' : '';
//        }

    }
 }

 