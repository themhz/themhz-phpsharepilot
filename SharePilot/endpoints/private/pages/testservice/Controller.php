<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
use SharePilotV2\Models\Subscribers;
use SharePilotV2\Models\Subscriptions;
use SharePilotV2\Models\Subscription_tokens;
use SharePilotV2\Models\Topics;
use SharePilotV2\Models\Device_types;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class Controller
{

    public $baseurl;
    private $auth;

    public function __construct()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];  // Get the host from the server variables
        $base_url = $protocol . '://' . $host;  // Concatenate to form the base URL

        $this->baseurl = $base_url;
        
    }

    public function get(){
        echo "hello world";
    }

    public function post(){
        
    }    

    public function savetoken(){
                
        $token  = RequestHandler::get("ftoken");
        $email  = RequestHandler::get("email");
        
        $detector = new DeviceDetector();
        
        $s = new Subscribers();
        $subscriber = $s->select()
        ->fields("id,email")
        ->where("email","=", $email)
        ->execute();

        
        if(empty($subscriber)) {                    
        
            $s->email = $email;
            $s->userAgent_details = $detector->getUserAgentDetails() . '/' . $detector->getUserIp();
            $s->regdate = date("Y/m/d H:i:s");
            $subscriber_id = $s->insert();

            $s = new Subscriptions();
            $s->subscriber_id = $subscriber_id;
            $s->subscriptionType_id = "3";
            $s->isActive = 1;
            $s->subscribedOn = date("Y/m/d H:i:s");
            $subscription_id = $s->insert();

            $s = new Subscription_tokens();
            $s->subscription_id = $subscription_id;
            $s->token = $token;
            $s->device_type_id = $this->getDeviceTypeId($detector->getDeviceType())[0]["id"];
            $subscription_token_id = $s->insert();

            ResponseHandler::respond(["result"=>true, "message"=>"successfully subscribed"]);            
        } else {            
            ResponseHandler::respond(["result"=>false, "message"=>"user has already been subscribed"]);
        }        
    }
   

    public function getDeviceTypeId($deviceStringType){        
        $s = new Device_types();
        return $s->select()
            ->fields("id",
                "type_name",                
            )            
            ->where("type_name","=",$deviceStringType)
            ->execute();
    }

    
    public function subscribeToTopic() {

        $token  = RequestHandler::get("ftoken");
        $email  = RequestHandler::get("email");
        $topic  = RequestHandler::get("topic");

        //ResponseHandler::respond(["result"=>true, "message"=>"$token $email $topic"]);   
                
        $tokens = [
            $token,
        ];

        $serverKey = 'AAAA0nxbjTA:APA91bFow-CJwZz6lThBYGg41MeyaAOzC77i9jSc8JLunKJUUScYdhb54RV-7jtCrii6nSrNIijLRhkYFbd3OSUmO2evNBvwGkAP94CM412JmLUFrzVzWnK89QkicMaBbdcEnuNLQIC5';
        $url = 'https://iid.googleapis.com/iid/v1:batchAdd';

        $fields = [
            'to' => '/topics/' . $topic,
            'registration_tokens' => $tokens
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        //echo $result;
        $this->saveSubscribeToTopic();
    }

    public function saveSubscribeToTopic(){

        
        $token  = RequestHandler::get("ftoken");
        $email  = RequestHandler::get("email");
        $topic_id  = RequestHandler::get("topic");
        $subscriber_inserted = false;
        $subscription_inserted = false;
        $subscription_token_inserted = false;
        
        $detector = new DeviceDetector();
        $subscriber_id = "";
        $subscription_id = "";

        $s = new Subscribers();
        $subscriber = $s->select()
        ->fields("subscribers.id,subscribers.email")
        ->where("email","=", $email)        
        ->execute();
       
        //Scheck if user is in Subscribers
        if(empty($subscriber)) {                    
        
            $s->email = $email;
            $s->userAgent_details = $detector->getUserAgentDetails() . '/' . $detector->getUserIp();
            $s->regdate = date("Y/m/d H:i:s");
            $subscriber_id = $s->insert();
            $subscriber_inserted = true;
        }else{            
            $subscriber_id = $subscriber[0]["id"];
        }
        
        //echo $subscriber_id;
        //die();
        //Check if user has subscriptions on that topic and that type
        $s = new Subscriptions();
        $subscription = $s->select()
        ->fields("id,subscriptionType_id,isActive")
        ->where("subscriber_id","=", $subscriber_id)        
        ->where("subscriptionType_id","=", 3)        
        ->where("topic_id","=", $topic_id)        
        ->execute();
      

        if(empty($subscription)){
            $s->subscriber_id = $subscriber_id;
            $s->subscriptionType_id = "3";
            $s->topic_id = $topic_id;
            $s->isActive = 1;            
            $s->subscribedOn = date("Y/m/d H:i:s");
            $subscription_id = $s->insert();    
            $subscription_inserted = true;            
        }else{
            $subscription_id = $subscription[0]["id"];            
        }
        
        $s = new Subscription_tokens();
        $Subscription_token = $s->select()
        ->fields("*")
        ->where("subscription_id","=", $subscription_id)
        ->where("device_type_id","=", $this->getDeviceTypeId($detector->getDeviceType())[0]["id"])
        ->execute();

        if(empty($Subscription_token)){
            $s->subscription_id = $subscription_id;
            $s->token = $token;
            $s->device_type_id = $this->getDeviceTypeId($detector->getDeviceType())[0]["id"];
            $subscription_token_id = $s->insert();
            $subscription_token_inserted = true;                        
        }
        
       
        if($subscriber_inserted == true || $subscription_inserted == true || $subscription_token_inserted == true){            
            
            ResponseHandler::respond(["result"=>true, "message"=>"successfully subscribed"]);      
         } else {            
            ResponseHandler::respond(["result"=>false, "message"=>"user has already been subscribed"]);
         }      

    }

    public function gettopics(){
        $s = new Topics();
        $result = $s->select()->execute();
        ResponseHandler::respond(["result"=>true, "message"=>$result]);  
    }

    
    
    
 
}
