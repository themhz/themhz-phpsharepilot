<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
use SharePilotV2\Components\Website;
use SharePilotV2\Libs\Functions;
use SharePilotV2\Pages\Pages;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


/*$website = new Website();
$website->start();*/

