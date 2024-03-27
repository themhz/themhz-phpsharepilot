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

    public function sendNotification()
    {

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
 
