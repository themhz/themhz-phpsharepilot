<?php
namespace SharePilotV2\Components;
use SharePilotV2\Implementations;
use SharePilotV2\Implementations\Facebook;

class PostingService
{
    private $sm = [];

    public function start(){
        
        //I have saved the social media channels in the database. So I do a select to get them. the name social is associated with the filename in the current directory in order to execute the code
        //that will be used to post the message in the social media. Each channel has it unique keys. So If you need to add a new social media channel then add it customly but also define it in the database.
        //You define a new social from the admin
        $db = Database::getInstance();
        $sql = "select distinct b.name channel, c.name social , channel_id, social_id
                from channel_social_keys a
                inner join channels b on a.channel_id = b.id
                inner join socials c on a.social_id = c.id
             ;";
        $sth = $db->prepare($sql);
        $sth->execute();
        $results = $sth->fetchAll(\PDO::FETCH_OBJ);

        //print_r($results);
        //die();
        
        //So for each social media and channel I add it to the posting service
        foreach ($results as $result){
        
            $channel_id = $result->channel_id;
            $social_id = $result->social_id;    
        
            $sql = "select name, value from channel_social_keys where channel_id=$channel_id and social_id=$social_id";
        
            $sth = $db->prepare($sql);
            $sth->execute();
            $keyvalue = $sth->fetchAll(\PDO::FETCH_OBJ);
                  
            $assocArray = array();
        
            foreach($keyvalue as $obj){
                $assocArray[$obj->name] = $obj->value;
            }
        
            $class =  'SharePilotV2\\Implementations\\' .ucfirst(strtolower($result->social));        
            $this->add(new $class($assocArray));
                            
        }

        $this->post();
    }

    public function add(ISocialMediaService $sm)
    {
        $this->sm[] = $sm;
    }

    private function getMessages(){
        $db = Database::getInstance();

        $sql = "SELECT b.id, a.title, a.url, DATE(b.post_time) AS post_date, TIME(b.post_time) AS post_time, b.is_posted
                    FROM urls a
                    INNER JOIN scheduled_posts b ON a.id = b.url_id
                    WHERE b.is_posted = 0 or b.is_posted is null
                    AND b.post_time <= NOW()";

        // Execute the query
        $sth = $db->prepare($sql);
        $sth->execute();
        $results =  $sth->fetchAll(\PDO::FETCH_OBJ);

        return $results;
    }

    public function post()
    {       
        $messages = $this->getMessages();
        //foreach social media
        
        for ($i = 0; $i < count($this->sm); $i++) {
            print_r($this->sm[$i]->post($messages)) . "\n\r";
        }

        //$this->updatePosted($messages);
    }

    private function updatePosted($messages){
        // Loop through the results and output them

        $db = Database::getInstance();
            foreach($messages as $message) {            
                $update_sql = "UPDATE scheduled_posts SET is_posted = 1, posted_time = NOW() WHERE id = :id";
                $update_stmt = $db->prepare($update_sql);
                $update_stmt->bindParam(':id', $message->id);
                $update_stmt->execute();
            }
    }
}

