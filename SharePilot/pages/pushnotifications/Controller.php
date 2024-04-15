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
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
 class Controller{


   public function getKeys(){
      echo "ok";
   }
   public function send(){      
      $credential = new ServiceAccountCredentials(
         "https://www.googleapis.com/auth/firebase.messaging",
         json_decode(file_get_contents("pvKey.json"), true)
      );

      $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

      $ch = curl_init("https://fcm.googleapis.com/v1/projects/sharepilot-939ee/messages:send");

      curl_setopt($ch, CURLOPT_HTTPHEADER, [
         'Content-Type: application/json',
         'Authorization: Bearer '.$token['access_token']
      ]);

      curl_setopt($ch, CURLOPT_POSTFIELDS, '{
         "message": {
            "token": "fcM4YeJOR5wVmR557eAv78:APA91bGhyS_nPS5EFWh3e-zOg7glCPOupAm68UdrU-2QqY2qKLkvTHtJ1W_g1-2gM4BM1yzXx2R3funKsQwOYASHfsHRZ2qpnVAb7mZMgQmQC0CCrBio6vHGwz9x47lLXpmiXc3XHjzn",                      
            "notification": {
            "title": "Tzoutzourini",
            "body": "Pitsikoni se agapw",
            "image": "https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024"
            },
            "webpush": {
            "fcm_options": {
               "link": "http://localhost/cron"
            }
            }
         }
      }');

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");

      $response = curl_exec($ch);

      curl_close($ch);

      echo $response;
   }
    
    
 }