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


   
   public function get($id = null, $method = 'GET', $templatePath = null) {
        
      //echo "Welcome to the Default Action!";
      // Specify the content file
      $content = dirname(__FILE__) . '/pushnotifications.php';

      // Include the master template
      if ($templatePath) {
          include $templatePath;
      } else {
          echo "Master template not found!";
      }
  }
   public function getKeys(){
      echo "ok";
   }
   public function sendnotification(){      
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
            "token": "cBY6-MkxrSlq_Qy9nqYv-w:APA91bG9SXirz6PqHNFSYO1lF8lEe3HRtdqRADCfA1K6cjZUX82pJlYEwIazQC3fjUasetLHxidlbsEZeEkTE1XIQvA48JQKNEgByBPM08PmnjWZIbMBN143p7hM3t3mR6-BE7hd5xSw",                      
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
    

   public function sendMessageToTopic() {
      $topic = "news1";
  
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
            "topic": "news1",
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