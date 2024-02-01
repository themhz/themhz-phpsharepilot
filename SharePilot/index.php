<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/vendor/autoload.php';
use SharePilotV2\Components\Website;

try{    
    $website = new Website();
    $website->start();
}catch(Exception $ex){
    print_r($ex);
}



