<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
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
        //echo $token;

        // Usage
        $detector = new DeviceDetector();
        echo "You are using a " . $detector->getDeviceType() . " with " . $detector->getOperatingSystem() . " OS.";
    }
 
}
