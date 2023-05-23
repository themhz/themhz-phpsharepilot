<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\Config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;

 class Controller{
    public function get(){


    }

     public function delete(){
         $u = new Scheduled_posts();

         $data = $u->delete(["id="=>$_POST["id"]],false);;
         ResponseHandler::respond($data);
     }

     public function getscheduledlinks()
     {
         $u = new Urls();
         //$videos = $u->select([],["id"=>"desc"]);;
         $videos = $u->customselect("SELECT u.*, sp.id as 'scheduled_id', sp.post_time, sp.is_posted 
                                         FROM urls u 
                                         INNER JOIN scheduled_posts sp ON u.id = sp.url_id order by id desc",[]);

         ResponseHandler::respond($videos);
     }

     public function updateschedulepost(){
         $u = new Scheduled_posts();
         $u->id = RequestHandler::get("id");
         $post_time_string = RequestHandler::get("post_time");
         $u->post_time = new \DateTime($post_time_string);

         $data = $u->update();;
         ResponseHandler::respond($data);
     }

    public function fetchTasks()
    {
        $u = new Scheduled_posts();
        //$videos = $u->select();
        $videos = $u->customselect("SELECT a.title as task, DATE(b.post_time) AS date, TIME(b.post_time) AS time, b.is_posted as posted FROM urls a INNER JOIN scheduled_posts b ON a.id = b.url_id", []);

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

     public function autoscheduleposts(){
         $u = new urls();
         $start_datetime = RequestHandler::get("start_datetime");
         $hourInterval = RequestHandler::get("hourInterval");

         ResponseHandler::respond($u->autoscheduleposts($start_datetime, $hourInterval));
     }

     public function deleteautoscheduleposts(){
         $u = new Scheduled_posts();
         ResponseHandler::respond($u->delete());
     }

     public function clearautoscheduleposts(){
         $u = new Scheduled_posts();
         ResponseHandler::respond($u->customselect("UPDATE scheduled_posts SET post_time = NULL;"));
     }
 }

 