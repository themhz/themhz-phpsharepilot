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

    public function get($id = null, $method = 'GET', $templatePath = null) {
        
        //echo "Welcome to the Default Action!";
        // Specify the content file
        $content = dirname(__FILE__) . '/channels.php';

        // Include the master template
        if ($templatePath) {
            include $templatePath;
        } else {
            echo "Master template not found!";
        }
    }

    public function list()
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

         $c = new Channels();
         $c->name= RequestHandler::get("name");
         $c->update()->where("id","=",$channel_id)->execute();

         ResponseHandler::respond(["status"=>"success"]);
     }
    //  public function getsocials(){
    //     $s = new Socials();
    //      ResponseHandler::respond($s->select()->execute());
    //  }
     public function loadkeys(){
         $channelId = RequestHandler::get("channelId");
         $socialId = RequestHandler::get("socialId");
         $csk = new Channel_social_keys();
         //ResponseHandler::respond($csk->select(["channel_id ="=>$channelId, " and social_id ="=>$socialId]));
         ResponseHandler::respond($csk->select()
             ->where("channel_id", "=", $channelId)
             ->where("social_id", "=", $socialId)->execute());
     }

     public function addchannel(){
         $channels = new Channels();
         $channels->name = RequestHandler::get("channelName");
         $channels->regdate = date("Y-m-d H:i");
         if(empty($channels->select()->where("name", "=", trim($channels->name))->execute())){
             if($channels->insert()>0){
                 ResponseHandler::respond(["result"=>true, "message"=>"Channel has been successfully inserted"]);
             }else{
                 ResponseHandler::respond(["result"=>false, "message"=>"there was an error trying to insert the channel"]);
             }
         }else{
             ResponseHandler::respond(["result"=>false, "message"=>"channel name already exists"]);
         }
     }

     public function loadchannels(){
         $c = new Channels();
         $channels = $c->select()->orderBy("id","desc")->execute();

         ResponseHandler::respond($channels);
     }
 }
 
