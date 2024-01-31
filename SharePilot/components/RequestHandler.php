<?php
//This is a basic class requesthandler that is used to get the posted and geted variables. I use both post and get from the same
//method since it is faster and more usefull. No typecasting is happening here.
namespace SharePilotV2\Components;
class RequestHandler
{
    public static function get($var = null)
    {
        if ($var != null) {
            if (isset($_POST[$var])) {
                return $_POST[$var];
            } elseif (isset($_GET[$var])) {
                return $_GET[$var];
            } else {
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

        // If the requested key is not found, return null.
        return null;
    }
}
