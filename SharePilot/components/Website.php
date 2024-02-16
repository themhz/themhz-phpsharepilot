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
use SharePilotV2\Libs\Functions;

class Website{

    public function start(){                
        $this->startSession();
        $this->loadEnvFile();
        $this->loadErrorHandler();
        $this->setTimeZone();
        $result = $this->userAuth();
        
        $this->loadPage();
        /*$json = RequestHandler::get("format");
        if($json == 'json'){
            //if($result["userAuth"] == false){
                
                //echo json_encode($result)."\r\n";
                //return;
            //}
            $controller = new MasterController();
            $controller->start();
        }else{
            $this->loadPage();
        }*/
    }
    private function startSession(){
        session_start();
    }
    private function loadEnvFile(){
        $dotenv = Dotenv::createImmutable(dirname($_SERVER['SCRIPT_FILENAME']));
        $dotenv->load();
    }
    private function loadErrorHandler(){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    private function setTimeZone(){
        $timezone2 = new TimeZone();
        $dbTimeZone2 = $timezone2->GetTimeZoneFromDb();
        $timezone2->SetCurrentTimeZone($dbTimeZone2["timezone"]);        
    }

    private function userAuth(){
        $userAuth = new UserAuthController(Database::getInstance());
        return $userAuth->handleRequest();
    }

    private function loadPage(){
        $page =  new Pages();
        $page = $page->load();        
    }

}
