<?php

use SharePilotV2\Components\Database;
use SharePilotV2\Models\Users;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
use SharePilotV2\Components\UserAuth;

class Controller
{

    public function post(){

    }


    public function get($id = null, $method = 'GET', $templatePath = null) {
        
        //echo "Welcome to the Default Action!";
        // Specify the content file
        $content = dirname(__FILE__) . '/settings.php';

        // Include the master template
        if ($templatePath) {
            include $templatePath;
        } else {
            echo "Master template not found!";
        }
    }

    public function updateuser(){
        $user = new Users();
        $user->name = RequestHandler::get("name");
        $user->lastname = RequestHandler::get("lastname");
        $user->email = RequestHandler::get("email");
        $user->role = 1;

        $id = RequestHandler::get("id");
        if(!$user->CheckIfUpdateMailExists($id)){
            if(RequestHandler::get("password")!=""){
                $user->password = password_hash(RequestHandler::get("password"), PASSWORD_DEFAULT);
            }
            $user->mobilephone = RequestHandler::get("mobilephone");

            ResponseHandler::respond(["result"=>true,"message"=>$user->update()->where("id","=",$id)->execute()]);
        }else{
            ResponseHandler::respond(["result"=>false, "message"=>"User already exists with that email"]);
        }

    }

    public function registeruser(){
        $user = new Users();
        $user->name = RequestHandler::get("name");
        $user->lastname = RequestHandler::get("lastname");
        $user->email = RequestHandler::get("email");
        $user->role = 1;
        $user->password = RequestHandler::get("password");
        $user->mobilephone = RequestHandler::get("mobilephone");

        if(!$user->CheckIfInsertedMailExists()){
            $UserAUth = new UserAUth(Database::getInstance());
            $result = $UserAUth->register($user);
            ResponseHandler::respond(["result"=>true,"message"=>$result]);
        }else{
            ResponseHandler::respond(["result"=>false,"message"=>"User already exists with that email"]);
        }

    }

    public function getusers(){
        $users = new Users();
        ResponseHandler::respond($users->select()->fields("id","name","lastname","email","mobilephone")->execute());
    }

    public function deleteuser(){
        $user = new Users();
        $result = $user->delete()->where("id","=",RequestHandler::get("id"))->execute();
        ResponseHandler::respond($result);
    }

    public function getuserbyid(){
        $users = new Users();
        ResponseHandler::respond($users->select()->fields("id","name","lastname","email","mobilephone")->where("id","=", RequestHandler::get("id"))->execute()[0]);
    }

    public function savetimezone(){
        $settings = new \SharePilotV2\Models\Settings();
        $settings->timezone = RequestHandler::get('timezone');
        ResponseHandler::respond($settings->update()->execute());
    }

}
