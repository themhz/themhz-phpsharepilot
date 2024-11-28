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
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];  // Get the host from the server variables
        $base_url = $protocol . '://' . $host;  // Concatenate to form the base URL

        $this->baseurl = $base_url;
        $this->auth = new UserAuth(Database::getInstance());
    }

    public function get($id = null, $method = 'GET', $templatePath = null) {
        //echo "Welcome to the Default Action!";
        // Specify the content file
        $content = dirname(__FILE__) . '/default.php';

        // Include the master template
        if ($templatePath) {
            include $templatePath;
        } else {
            echo "Master template not found!";
        }
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
        ResponseHandler::respond($result);        
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
