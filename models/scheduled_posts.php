<?php
namespace SharePilotV2\Models;
use SharePilotV2\Libs\Database;
use SharePilotV2\Components\Model;

class Scheduled_posts extends Model{

    public function __construct()
    {
        parent::__construct('scheduled_posts');
    }

    public int $id;
    public int $url_id;
    public \DateTime $post_time;
    public \DateTime $posted_time;
    public int $is_posted;

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