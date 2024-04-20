<?php
namespace SharePilotV2\Components;

class DeviceDetector {
    private $userAgent;
    private $userIp;
    private $deviceType;
    private $os;

    public function __construct() {
        $this->userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $this->userIp = $_SERVER['REMOTE_ADDR'];
        $this->detectDeviceType();
        $this->detectOperatingSystem();
    }

    private function detectDeviceType() {
        if (preg_match('/mobile|iphone|ipod|blackberry|opera mini|iemobile|windows phone/i', $this->userAgent)) {
            $this->deviceType = 'Mobile';
        } elseif (preg_match('/tablet|ipad|android 3.0|xoom|sch-i800|playbook|tablet.*firefox/i', $this->userAgent)) {
            $this->deviceType = 'Tablet';
        } else {
            $this->deviceType = 'PC';
        }
    }

    private function detectOperatingSystem() {
        if (preg_match('/android/i', $this->userAgent)) {
            $this->os = 'Android';
        } elseif (preg_match('/linux/i', $this->userAgent)) {
            $this->os = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $this->userAgent)) {
            $this->os = 'Mac OS';
        } elseif (preg_match('/windows|win32/i', $this->userAgent)) {
            $this->os = 'Windows';
        } elseif (preg_match('/iphone|ipad|ipod/i', $this->userAgent)) {
            $this->os = 'iOS';
        } else {
            // Return the part of the user agent string that might contain relevant OS information
            // We use a non-greedy match to capture up to the first semicolon or parenthesis
            if (preg_match('/^.*?(?=\;|\()/i', $this->userAgent, $matches)) {
                $this->os = trim($matches[0]);
            } else {
                $this->os = 'Unidentified OS';
            }
        }
    }

    public function getUserIp(){
        return $this->userIp;
    }

    public function getUserAgentDetails(){
        return $this->userAgent;
    }

    public function getDeviceType() {
        return $this->deviceType;
    }

    public function getOperatingSystem() {
        return $this->os;
    }
}


