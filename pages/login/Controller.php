<?php
use SharePilotV2\Models\Users;
use SharePilotV2\Components\ResponseHandler;

class Controller
{

    public function get(){

    }
    public function authentication()
    {
        $users = new Users();
        $data = $users->check();

        if($data!=""){
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
