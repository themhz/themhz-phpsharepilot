<?php
namespace SharePilotV2\Models;
use SharePilotV2\Libs\Dbhandler;

class Urls{

    public function select($id = null)
    {
        
        $db = Dbhandler::getInstance();
        $sql = "select * from urls";
        if(!empty($id)){
             $sql .= " and id=". $id;
        }
        $sth = $db->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }

    public function add($video_id, $title, $video_url, $thumbnail_url, $published_at){
        $db = Dbhandler::getInstance();

        $sql = "SELECT * FROM urls WHERE url = ?";
        $check_stmt = $db->dbh->prepare($sql);
        $check_stmt->bind_param("s", $video_url);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows === 0) {
            // Insert the video into the database
            $sql = "INSERT INTO urls (url, title, dateInserted, source, type, thumbnailUrl) VALUES (?, ?, ?, ?, ?, ?)";

            $dateInserted = date('Y-m-d H:i');
            $source = 1;
            $type = 1;
            $stmt = $db->dbh->prepare($sql);
            $stmt->bind_param("sssiis", $video_url, $title, $dateInserted, $source, $type, $thumbnail_url);

            if ($stmt->execute()) {
                return "New video added successfully";
            } else {
                return "Error: " . $sql . "<br>" . $db->dbh->error;
            }

            $stmt->close();
        } else {
            return "Video is already in the database.";
        }

        $check_stmt->close();
        $db->dbh->close();
    }
}