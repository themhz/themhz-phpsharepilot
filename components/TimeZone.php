<?php

namespace SharePilotV2\Components;

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

    public function getPhpIniTimeZone(){
        return ini_get('date.timezone') ?: 'php ini is not set';
    }

}