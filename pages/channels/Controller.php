<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\Config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Models\Channels;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;

 class Controller{

    public function get()
    {
        $c = new Channels();
        $data = $c->select();
        ResponseHandler::respond($data);
    }

     public function delete()
     {
         $c = new Channels();
         $data = $c->delete(["id="=>RequestHandler::get("id")]);
         ResponseHandler::respond($data);

     }

     public function put(){
         $c = new Channels();
         $c->id = RequestHandler::get("id");
         $c->name = RequestHandler::get("name");
         $data = $c->update();
         ResponseHandler::respond($data);
     }
 }





 