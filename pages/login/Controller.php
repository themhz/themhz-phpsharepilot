<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;

class Controller
{

    public function get(){

    }
    public function authentication()
    {
        $users = new Users();
        $data = $users->select(["email ="=>RequestHandler::get("email"),
            "and password ="=>RequestHandler::get("password")],[],false);

        if($data!=[]){
            ResponseHandler::respond($data);
        }else{
            ResponseHandler::respond("nouser");
        }
    }

    public function logout(){
        session_destroy();
        header("Location:login");

    }

 

}
