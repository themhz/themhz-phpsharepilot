<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Services;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Models\Service_categories;
use SharePilotV2\Models\Channel_social_keys;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
 class Controller{
    public function get()
    {
        $c = new Services();
        $data = $c->select()->execute();
        ResponseHandler::respond($data);
    }
     public function delete()
     {
         $c = new Services();
         $result = $c->delete()->where("id","=",RequestHandler::get("id"))->execute();
         ResponseHandler::respond(["result"=>$result]);
     }
     public function put(){
         $service_id = RequestHandler::get("id");
         $services = new Services();
         $services->name = RequestHandler::get("name");
         $services->update()->where("id","=",$service_id)->execute();

         ResponseHandler::respond(["status"=>"success"]);
     }
     public function getservices(){
//        $s = new Services();
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

     public function addservice(){

        //ppostrint_r(RequestHandler::get("socialName")."adsda");
        //die();
         $service = new Services();
         $service->name = RequestHandler::get("serviceName");
         $service->regdate = date("Y-m-d H:i");
         $service->active = 1;
         if(empty($service->select()->where("name", "=", trim($service->name))->execute())){
             if($service->insert()>0){
                 ResponseHandler::respond(["result"=>true, "message"=>"Service has been successfully inserted"]);
             }else{
                 ResponseHandler::respond(["result"=>false, "message"=>"there was an error trying to insert the channel"]);
             }
         }else{
             ResponseHandler::respond(["result"=>false, "message"=>"service name already exists"]);
         }
     }

     public function loadchannels(){
//         $c = new Channels();
//         $channels = $c->select()->orderBy("id","desc")->execute();
//
//         ResponseHandler::respond($channels);
     }


     public function createServiceCategory(){
        $sc = new Service_categories();
        $sc->name = RequestHandler::get("name");     
        $sc->insert();  
        ResponseHandler::respond(["result"=>true, "message"=>"Service category has been successfully inserted"]); 
     }

     public function selectservicecategory(){
        $sc = new Service_categories();

        $data = $sc->select()->execute()[0];
        ResponseHandler::respond($data);
     }
 }
 
