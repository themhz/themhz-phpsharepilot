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

class Website {
    // Define an array of public pages
    private $publicPages = ['testservice', 'login', 'home']; // Example public pages

    public function start() {                
        try {    
            $this->loadErrorHandler();
            $this->startSession();
            $this->loadEnvFile();
            $this->setTimeZone();
          
            if ($this->isPublicPage() || $this->authenticateUser()["userAuth"]) {
                $this->routeRequest();
            } else {                
                $this->loadLogin();
            }
        } catch (Exception $ex) {            
            error_log($ex->getMessage());
        }        
    }

    private function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function loadEnvFile() {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }

    private function loadErrorHandler() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    private function setTimeZone() {
        $timezone = new TimeZone();
        $dbTimeZone = $timezone->GetTimeZoneFromDb();
        date_default_timezone_set($dbTimeZone["timezone"]);
    }

    private function authenticateUser() {
        $userAuth = new UserAuthController(Database::getInstance());
        return $userAuth->handleRequest();
    }

    private function isPublicPage() {
        $currentPage = RequestHandler::get("page"); // Adjust this according to how you determine the current page       
        return in_array($currentPage, $this->publicPages);
    }

    private function routeRequest() {
        $json = RequestHandler::get("format");
        if ($json == 'json') {
            $controller = new MasterController();
            $controller->start();
        } else {
            $this->loadPage();
        }
    }

    private function loadPage() {
        $page = new Pages($this->publicPages);
        $page->load(); // Ensure this method either echoes directly or returns the output
    }

    private function loadLogin() {        
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];  // Get the host from the server variables
        $base_url = $protocol . '://' . $host;  // Concatenate to form the base URL                
        header("Location: $base_url/login");
    }
}

