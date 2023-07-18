<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Socials;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Models\Channels;
use SharePilotV2\Models\Channel_social_keys;
use SharePilotV2\Models\Lists;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
 class Controller{
    public function get()
    {
        $id = RequestHandler::get("id");

        if($id==null){

            $c = new Lists();
            $data = $c->select()
                ->fields("lists.id","lists.name","lists.channel_id","channels.name as channelName")
                ->join("inner", "channels", "lists.channel_id = channels.id")
                ->execute();

        }else{
            $c = new Lists();
            $data = $c->select()->where("id","=",$id)->execute()[0];
        }

        ResponseHandler::respond($data);
    }

     public function delete(){
         $c = new Lists();
         $result = $c->delete()->where("id","=",RequestHandler::get("id"))->execute();
         ResponseHandler::respond(["result"=>$result]);
     }

     public function update(){
        $channel_id = RequestHandler::get("channel_id");
        $name = RequestHandler::get("name");
        $l = new Lists();
        $l->channel_id = $channel_id;
        $l->name = $name;
        $result = $l->update()->where("id","=",RequestHandler::get("id"))->execute();
        ResponseHandler::respond(["result"=>$result]);
     }

     public function add(){
         $channel_id = RequestHandler::get("channel_id");
         $name = RequestHandler::get("name");
         $l = new Lists();
         $l->channel_id = $channel_id;
         $l->name = $name;
         $result = $l->insert();
         ResponseHandler::respond(["result"=>$result]);
     }


 }
 
