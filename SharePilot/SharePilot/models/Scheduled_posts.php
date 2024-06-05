<?php
namespace SharePilotV2\Models;
use SharePilotV2\Components\Model;

class Scheduled_posts extends Model{

    public function GetTable()
    {
        return "scheduled_posts";
    }

    public function autoscheduleposts(...$parameters){

        if (!empty($parameters)) {
            return parent::callStoredProcedure("schedule_posts", $parameters);
        }
    }

    public function restateschedule(...$parameters){

        if (!empty($parameters)) {
            return parent::callStoredProcedure("restateschedule_posts", $parameters);
        }
    }
}
