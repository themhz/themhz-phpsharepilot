<?php
/*
 * Copyright (C) 2014 themhz
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
class Pages
{
    private $page = null;    
    private $publicPages = [];

    public function __construct($publicPages=[]) {
        $this->publicPages = $publicPages;
    }

    public function load($directLoad = false) {
        if (isset($_REQUEST['page'])) {
            $this->page = $_REQUEST['page'];
        } else {
            // Default to a default page if none is specified
            $this->page = 'default';
        }

        $directory = getcwd() . "/pages/{$this->page}";
        // Check if the directory exists
        if (is_dir($directory)) {
            // Load the directory
            $page = $directory . '/' . $this->page . '.php';
        } else {
            // Load the default directory
            $page = getcwd() . "/pages/default/default.php";
        }

       
        if ($directLoad || in_array($this->page, $this->publicPages)) {
            include $page;
        } else {
            include __DIR__ . '/../template/index.php';
        }
    }

}
