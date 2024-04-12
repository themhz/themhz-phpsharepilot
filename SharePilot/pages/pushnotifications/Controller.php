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
            "token": "{the token of got from the site}",
            "notification": {
            "title": "Background Message Title",
            "body": "Background message body",
            "image": "https://cdn.shopify.com/s/files/1/1061/1924/files/Sunglasses_Emoji.png?2976903553660223024"
            },
            "webpush": {
            "fcm_options": {
               "link": "https://google.com"
            }
            }
         }
      }');

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");

      $response = curl_exec($ch);

      curl_close($ch);

      echo $response;
    }
    public function sendNotification()
    {
         //To watch the video
         //https://www.youtube.com/watch?v=iz5arafmatc&t=746s
         //Where I get my apiKey
         //https://console.cloud.google.com/apis/credentials?project=sharepilot-939ee
         //https://console.firebase.google.com/u/0/project/sharepilot-939ee/settings/general
         // Your FCM Server API key
         $apiKey = 'AAAA0nxbjTA:APA91bFow-CJwZz6lThBYGg41MeyaAOzC77i9jSc8JLunKJUUScYdhb54RV-7jtCrii6nSrNIijLRhkYFbd3OSUmO2evNBvwGkAP94CM412JmLUFrzVzWnK89QkicMaBbdcEnuNLQIC5';
         // URL for FCM API
         $url = 'https://fcm.googleapis.com/fcm/send';

         // Payload data - the message and additional data you want to send
         $payload = array(
            'notification' => array(
               'title' => 'New Update Available!',
               'body' => 'Check out the latest features now.',               
            ),
            'to' => 'USER_FCM_TOKEN_HERE' // Single user FCM token
            // For multiple users, use 'registration_ids' => ['token1', 'token2', ...]
         );

         // Headers
         $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
         );

         // Initialize curl with the prepared headers and payload
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

         // Execute the request
         $result = curl_exec($ch);
         if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
         }

         // Close connection
         curl_close($ch);

         // Display FCM response
         echo $result;

       
    }
    
 }
 
