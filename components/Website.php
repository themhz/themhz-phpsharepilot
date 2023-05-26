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

class Website{
    public function start(){
        session_start();
        require_once __DIR__ . '/../config.php';

        date_default_timezone_set('UTC');

        $raw = isset($_REQUEST['format']) ? $_REQUEST['format'] : '';


        if($raw != 'raw'){
            $page =  new Pages();
            //Load template
            include __DIR__ . '/../template/index.php';
        }else{
            $controller = new MasterController();
            $controller->start();
        }
    }
}