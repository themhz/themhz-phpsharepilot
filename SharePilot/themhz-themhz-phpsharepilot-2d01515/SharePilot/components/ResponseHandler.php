<?php
/* 
 * Copyright (C) 2016 themhz
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
class ResponseHandler
{

    public static function respond($data)
    {
        // headers for not caching the results
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        // headers to tell that result is JSON
        header('Content-type: application/json');

         // Output the JSON-encoded data
        // In CLI mode, append a newline character for better readability
        echo json_encode($data) . (php_sapi_name() == 'cli' ? "\n" : '');
        
    }
}
