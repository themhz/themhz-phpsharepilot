<?php

namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Users extends Model
{
    public function GetTable()
    {
        return "users";
    }
    public function CheckIfInsertedMailExists(){
        $result = $this->select()->fields("email")->where("email","=",$this->email)->execute();
        $exists =false;
        if(!empty($result)){
            $exists = true;
        }
        return $exists;
    }

    public function CheckIfUpdateMailExists($id){
        $result = $this->select()->fields("email")->where("email","=",$this->email)->where("id","!=",$id)->execute();
        $exists =false;
        if(!empty($result)){
            $exists = true;
        }
        return $exists;
    }
}

