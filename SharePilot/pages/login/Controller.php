<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\UserAuth;
use SharePilotV2\Components\Database;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;

class Controller
{

    public $baseurl;
    private $auth;

    public function __construct()
    {
        $this->baseurl = $_ENV['BASE_URL'];
        $this->auth = new UserAuth(Database::getInstance());
    }

    public function get(){

    }

    public function post(){
        header("Location: $this->baseurl/default");
    }
    public function authentication()
    {     
        // handle login
        if (RequestHandler::get("page")=="login") {
            $email = RequestHandler::get('email');
            $password = RequestHandler::get('password');
            $result = [];
            if ($this->auth->login($email, $password)) {
                $result = ["userAuth"=> "true", "message" =>"Login successful."];
            } else {                    
                $result = ["userAuth"=> "false", "message" =>"Invalid email or password."];
            }
        }
                
        header("Location: $this->baseurl/default?result=".$result["userAuth"]."&message=".$result["message"]);
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
