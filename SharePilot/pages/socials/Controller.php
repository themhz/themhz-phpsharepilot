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
        $c = new Socials();
        $data = $c->select()->execute();
        ResponseHandler::respond($data);
    }
     public function delete()
     {
         $c = new Socials();
         $result = $c->delete()->where("id","=",RequestHandler::get("id"))->execute();
         ResponseHandler::respond(["result"=>$result]);
     }
     public function put(){
         $social_id = RequestHandler::get("id");
         $socials = new Socials();
         $socials->name = RequestHandler::get("name");
         $socials->update()->where("id","=",$social_id)->execute();

         ResponseHandler::respond(["status"=>"success"]);
     }
     public function getsocials(){
//        $s = new Socials();
//         ResponseHandler::respond($s->select()->execute());
     }
     public function loadkeys(){
//         $channelId = RequestHandler::get("channelId");
//         $socialId = RequestHandler::get("socialId");
//         $csk = new Channel_social_keys();
//         //ResponseHandler::respond($csk->select(["channel_id ="=>$channelId, " and social_id ="=>$socialId]));
//         ResponseHandler::respond($csk->select()
//             ->where("channel_id", "=", $channelId)
//             ->where("social_id", "=", $socialId)->execute());
     }

     public function addsocial(){

        //ppostrint_r(RequestHandler::get("socialName")."adsda");
        //die();
         $social = new Socials();
         $social->name = RequestHandler::get("socialName");
         $social->regdate = date("Y-m-d H:i");
         $social->active = 1;
         if(empty($social->select()->where("name", "=", trim($social->name))->execute())){
             if($social->insert()>0){
                 ResponseHandler::respond(["result"=>true, "message"=>"Social has been successfully inserted"]);
             }else{
                 ResponseHandler::respond(["result"=>false, "message"=>"there was an error trying to insert the channel"]);
             }
         }else{
             ResponseHandler::respond(["result"=>false, "message"=>"social name already exists"]);
         }
     }

     public function loadchannels(){
//         $c = new Channels();
//         $channels = $c->select()->orderBy("id","desc")->execute();
//
//         ResponseHandler::respond($channels);
     }
 }
 
