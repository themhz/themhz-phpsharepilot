<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Socials;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Models\Channels;
use SharePilotV2\Models\Channel_social_keys;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
 class Controller{
    public function get()
    {
        $c = new Channels();
        $data = $c->select()->execute();
        ResponseHandler::respond($data);
    }
     public function delete()
     {
         $c = new Channels();
         $result = $c->delete()->where("id","=",RequestHandler::get("id"))->execute();
         ResponseHandler::respond(["result"=>$result]);
     }
     public function put(){
//         $c = new Channels();
//         $c->id = RequestHandler::get("id");
//         $c->name = RequestHandler::get("name");
//         $data = $c->update();
//         ResponseHandler::respond($data);
//        die();
         $keylist = RequestHandler::get("keylist");
         $keys = new Channel_social_keys();
         $social_id = RequestHandler::get("social_id");
         $channel_id = RequestHandler::get("id");
         $keys->delete()->where("channel_id","=",$channel_id)->where("social_id","=", $social_id)->execute();


         foreach ($keylist as $key){
             $keys->channel_id = $channel_id;
             $keys->social_id = $social_id;
             $keys->name =$key["name"];
             $keys->value =$key["value"];
             $keys->insert();
         }

         ResponseHandler::respond(["status"=>"success"]);
     }
     public function getsocials(){
        $s = new Socials();
         ResponseHandler::respond($s->select()->execute());
     }
     public function loadkeys(){
         $channelId = RequestHandler::get("channelId");
         $socialId = RequestHandler::get("socialId");
         $csk = new Channel_social_keys();
         //ResponseHandler::respond($csk->select(["channel_id ="=>$channelId, " and social_id ="=>$socialId]));
         ResponseHandler::respond($csk->select()
             ->where("channel_id", "=", $channelId)
             ->where("social_id", "=", $socialId)->execute());
     }
 }
 
