<?php
namespace SharePilotV2\Models;

class Scheduled_posts extends Model{

    public function GetTable()
    {
        return "scheduled_posts";
    }

    public function autoscheduleposts(...$parameters){

        if (!empty($parameters)) {
            return parent::callStoredProcedure("CALL schedule_posts(" . $this->placeholders(count($parameters)) . ")", $parameters);
        }
    }

    public function restateschedule(...$parameters){

        if (!empty($parameters)) {
            return parent::callStoredProcedure("CALL restateschedule_posts(" . $this->placeholders(count($parameters)) . ")", $parameters);
        }
    }
}