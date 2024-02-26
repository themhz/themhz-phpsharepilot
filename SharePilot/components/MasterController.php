<?php
namespace SharePilotV2\Components;

class MasterController{

    public function Start(){
        $page='';
        $directory='';

        if(isset($_REQUEST["page"])){
            $page = $_REQUEST['page'];
            $directory = getcwd()."/pages/$page";
        }
        //Include the controller
        //Check If the directory exists
        if(is_dir($directory)){            
            //Load the directory
            include $directory.'/Controller.php';
        }else{
            //Load the default directory
            include getcwd()."/pages/default/Controller.php";
        }

        //Then call the method of the included controller
        if(isset($_REQUEST["method"])){
            $method = stripslashes($_REQUEST["method"]);
            $obj= new \Controller();
            return call_user_func_array(array($obj, $method),array());
        }else{            
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            $obj= new \Controller;
            return call_user_func_array(array($obj, $method),array());
        }
    }
}
