<?php

namespace SharePilotV2\Components;
use SharePilotV2\Models;

class TimeZone{

    public function SetCurrentTimeZone($timezone){
        date_default_timezone_set($timezone);
    }

    public function GetCurrentTimeZone(){
        return date_default_timezone_get();
    }

    public function GetTimezonesList(){
        return \DateTimeZone::listIdentifiers();
    }

    public function GetPhpIniTimeZone(){
        return ini_get('date.timezone') ?: 'php ini is not set';
    }

    public function GetTimeZoneFromDb(){
        $settings = new Models\Settings();
        return $settings->select()->fields("timezone")->execute()[0];
    }



}
