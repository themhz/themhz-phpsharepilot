<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
use SharePilotV2\Components\Website;
use SharePilotV2\Libs\Functions;
use SharePilotV2\Pages\Pages;
use SharePilotV2\Components\MasterController;
use SharePilotV2\config;
$website = new Website();
$website->start();
