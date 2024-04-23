<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\DeviceDetector;
use SharePilotV2\Components\UpdateManager;

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
       
    }

    public function post(){
        
    }    

    public function update(){        
        try {
            $updateManager = new UpdateManager('Update.zip');        
            $filePath = 'Update.zip';
            $checksum = $updateManager->generateChecksum($filePath);
            echo "Checksum: " . $checksum;
        } catch (Exception $e) {
            // Here you might log the error and display a user-friendly message
            error_log($e->getMessage()); // Log the error to the server's error log or your custom log
            echo "Failed to update: " . $e->getMessage();
        }
    }


}