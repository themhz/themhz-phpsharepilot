<?php
namespace SharePilotV2\Models;
use SharePilotV2\Libs\Dbhandler;

class Scheduled_posts{

    public function select($id = null)
    {
        
        $db = Dbhandler::getInstance();
        $sql = "SELECT a.title as task, DATE(b.post_time) AS date, TIME(b.post_time) AS time, b.is_posted as posted FROM urls a INNER JOIN scheduled_posts b ON a.id = b.url_id";
        if(!empty($id)){
             $sql .= " and id=". $id;
        }
        $sth = $db->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }
}