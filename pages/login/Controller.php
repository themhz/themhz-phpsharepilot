<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
class Controller
{

    public $baseurl;
    public function __construct()
    {
        $this->baseurl = $_ENV['BASE_URL'];
    }

    public function get(){

    }

    public function post(){
        header("Location: $this->baseurl/default");
    }
    public function authentication()
    {
        header("Location: $this->baseurl/default");
    }
    public function logout(){

        $this->deleteToken();
        $this->deleteSession();
    }

    private function deleteSession(){
        unset($_SESSION["user"]);
        session_destroy();
        header("Location: $this->baseurl/default");
    }
    private function deleteToken(){
        // Set the expiration time to a past date
        $expiration_time = time() - 3600; // Set it to an hour ago or any desired time

        // Set the cookie with the same name but an expired value
        setcookie('token', '', $expiration_time, '/');

        // Unset the cookie from the $_COOKIE array as well
        unset($_COOKIE['token']);

        // Send an HTTP response header to delete the cookie
        header('Set-Cookie: token=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/;');
    }
 
}
