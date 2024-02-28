<?php

namespace SharePilotV2\Components;
use SharePilotV2\Models\Users;

class UserAuth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($user) {
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        $result = $user->insert();
        return $result;
    }

    public function login($email, $password) {

        $users = new Users();
        $user = $users->select()->where("email","=",$email)->execute();
        $user = empty($user)?false:$user[0];

        
        if ($user && password_verify($password, $user['password'])) {        
            // password is correct
            // generate a new session token
            $token = bin2hex(openssl_random_pseudo_bytes(16));            
            $users->token = $token;
            $result = $users->update()->where("id","=",$user['id'])->execute();

            if($result){
                $_SESSION["user"] = $user;               
                // set the token cookie if remember me is checked for 1 hour                
                
                if(RequestHandler::get("remember")==true){
                    setcookie('token', $token, time() + 3600,'/'); // 1 hour expiration                                        
                }
                                
                return true;
            }
        }
        return false;
    }

    public function authenticate() {

        if (!isset($_COOKIE['token'])) {
            return null;
        }

        $users = new Users();
        $users->token = $_COOKIE['token'];

        return $users->select()->where("token","=",$_COOKIE['token'])->execute()[0];
    }
}

