<?php
// This is a basic class requesthandler that is used to get the posted, geted, and command-line arguments.
// I use both post, get, and command-line arguments from the same method since it is faster and more useful.
// No typecasting is happening here.
namespace SharePilotV2\Components;

class RequestHandler
{
    public static function get($var = null)
    {
        // Check if the script is running from the command line        
        if (php_sapi_name() == 'cli') {
            global $argv;
            // Parse command-line arguments into an associative array
            $cmdArgs = [];
            foreach ($argv as $arg) {
                if (strpos($arg, '=') !== false) {
                    list($key, $value) = explode('=', $arg, 2);
                    $cmdArgs[$key] = $value;
                }
            }

            // Return the specific command-line argument value if requested
            if ($var !== null && isset($cmdArgs[$var])) {
                return $cmdArgs[$var];
            }
            // Return all command-line arguments if no specific one is requested
            elseif ($var === null) {
                return $cmdArgs;
            }
        } else {
            // Original logic for handling web requests
            if ($var !== null) {
                if (isset($_POST[$var])) {
                    return $_POST[$var];
                } elseif (isset($_GET[$var])) {
                    return $_GET[$var];                    
                } elseif(isset($_COOKIE[$var])){
                    return $_COOKIE[$var];          
                }else {                                        
                    // Check in JSON data.
                    $jsonData = json_decode(file_get_contents('php://input'), true);
                    if (isset($jsonData[$var])) {
                        return $jsonData[$var];
                    }
                }
            } else {
             
                // If no specific variable is requested, return all input data.
                $jsonData = json_decode(file_get_contents('php://input'), true);
                
                if (!empty($jsonData)) {
                
                    return $jsonData;
                }
                
                return array_merge($_POST, $_GET);
            }
        }

        // If the requested key is not found, return null.
        return null;
    }
}
