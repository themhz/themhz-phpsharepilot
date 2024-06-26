<?php
/*
 * Copyright (C) 2015 themhz
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
use \PDO;
use \DateTime;
use SharePilotV2\Components\RequestHandler;

class Database
{
    public $dbh;
    private static $instance;
    private function __construct()
    {
        //$dbhost = $_ENV['DB_HOST'];        

        if(RequestHandler::get("dbhost")){
            $dbhost = "localhost";
        }else{
            $dbhost = $_ENV['DB_HOST'];
        }            
        
        $basename = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        $this->dbh = new \PDO("mysql:host=$dbhost;dbname=$basename", $user, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",PDO::ATTR_PERSISTENT => true));
        //These comments must be removed
        
        //$connection = new PDO("mysql:host=db;dbname=sharepilot", "root", "526996");
        // $now = new DateTime();
        // $mins = $now->getOffset() / 60;
        // $sgn = ($mins < 0 ? -1 : 1);
        // $mins = abs($mins);
        // $hrs = floor($mins / 60);
        // $mins -= $hrs * 60;
        // $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
        // $this->dbh->exec("SET time_zone='$offset';");
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }

        return self::$instance->dbh;
    }
}
