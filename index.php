<?php
require_once __DIR__ . '/vendor/autoload.php';
use SharePilotV2\Components\Website;
use SharePilotV2\Libs\Functions;
use SharePilotV2\Pages\Pages;
use SharePilotV2\Components\MasterController;
use SharePilotV2\Config;
$website = new Website();
$website->start();
