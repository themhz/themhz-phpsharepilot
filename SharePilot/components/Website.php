<?php
/*
 * Copyright (C) 2017 themhz
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SharePilotV2\Components;

use Dotenv\Dotenv;

class Website
{
    // Define an array of public pages
    //private $publicPages = ['testservice', 'login', 'home', 'help', 'privacy']; // Example public pages
    public function start()
    {
        try {
            $this->loadErrorHandler();
            $this->startSession();
            $this->loadEnvFile();
            $this->setTimeZone();
            $this->route();   
          
            
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
        }
    }
    private function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    private function loadEnvFile()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }
    private function loadErrorHandler()
    {
        error_reporting(E_ALL);
    }
    private function setTimeZone()
    {
        $timezone = new TimeZone();
        $dbTimeZone = $timezone->GetTimeZoneFromDb();
        date_default_timezone_set($dbTimeZone['timezone']);
    }
    
    
    private function route() {
        $router = new Router();
    
        // Run the router without manually defining routes
        $router->run();
    }    

}
