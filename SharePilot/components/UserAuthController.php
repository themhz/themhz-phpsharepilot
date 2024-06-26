<?php

namespace SharePilotV2\Components;

class UserAuthController {
    private $auth;

    public function __construct($db) {
        $this->auth = new UserAuth($db);
    }

    public function handleRequest() {       
        // checking if a token cookie is set         
        if(RequestHandler::get("token")!==null){
           
                //echo RequestHandler::get("token");
                //die();
            // if the user has a token, authenticate the token
            $user = $this->auth->authenticateToken();
            // if the token is invalid or expired, ask them to log in again
            if (!$user) {
                return $this->checkUsernamePass();
            } else {

                $_SESSION["user"] = $user;
                return ["userAuth"=> true, "message" =>"Welcome back, {$user['email']}."];
            }
        } else {            
            // if no token cookie is set, check username or password
            return $this->checkUsernamePass();                     
        }        
    }

    public function checkUsernamePass(){
            $email = RequestHandler::get("email");
            $password = RequestHandler::get("password");

            if ($this->auth->login($email, $password)) {
                return ["userAuth"=> true, "message" =>"login successful."];
            } else {
                return ["userAuth"=> false, "message" =>"Not authenticated."];
            }                   
    }
}

