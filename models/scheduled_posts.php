<?php
namespace SharePilotV2\Models;
use SharePilotV2\Libs\Database;
use SharePilotV2\Components\Model;

class Scheduled_posts extends Model{

    public function __construct()
    {
        parent::__construct('urls');
    }

    public int $id;
    public int $url_id;
    public DateTime $post_time;
    public DateTime $posted_time;
    public int $is_posted;


//    public function select($id = null)
//    {
//
//        $db = Database::getInstance();
//        $sql = "SELECT a.title as task, DATE(b.post_time) AS date, TIME(b.post_time) AS time, b.is_posted as posted FROM urls a INNER JOIN scheduled_posts b ON a.id = b.url_id";
//        if(!empty($id)){
//             $sql .= " and id=". $id;
//        }
//        $sth = $db->prepare($sql);
//        $sth->execute();
//
//        return $sth->fetchAll(\PDO::FETCH_OBJ);
//    }
}