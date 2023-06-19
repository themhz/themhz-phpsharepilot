<?php
require_once 'CronConfig.php';
require_once 'Facebook.php';
require_once 'Reddit.php';
require_once 'Twitter.php';
require_once 'LinkedIn.php';
require_once 'Database.php';


interface ISocialMediaService
{
    public function post($messages);
}

class PostingService
{
    private $sm = [];

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
            echo $this->sm[$i]->post($messages) . "\n\r";
        }

        //$this->updatePosted($messages);
    }

    private function updatePosted($messages){
        // Loop through the results and output them

        $db = Database::getInstance();
            foreach($messages as $message) {
                //print_r($message);
                $update_sql = "UPDATE scheduled_posts SET is_posted = 1, posted_time = NOW() WHERE id = :id";
                $update_stmt = $db->prepare($update_sql);
                $update_stmt->bindParam(':id', $message->id);
                $update_stmt->execute();
            }
    }
}

$ps = new PostingService();
$db = Database::getInstance();
$sql = "select distinct b.name channel, c.name social , channel_id, social_id
        from channel_social_keys a
        inner join channels b on a.channel_id = b.id
        inner join socials c on a.social_id = c.id
     ;";
$sth = $db->prepare($sql);
$sth->execute();
$results = $sth->fetchAll(\PDO::FETCH_OBJ);

foreach ($results as $result){

    $channel_id=$result->channel_id;
    $social_id=$result->social_id;
    //echo $result->channel." | " .ucfirst(strtolower($result->social)) ."\n\r";

    $sql = "select name, value from channel_social_keys where channel_id=$channel_id and social_id=$social_id";
    $sth = $db->prepare($sql);
    $sth->execute();
    $keyvalue = $sth->fetchAll(\PDO::FETCH_OBJ);
    $assocArray = array();

    foreach($keyvalue as $obj){
        $assocArray[$obj->name] = $obj->value;
    }

    $class = ucfirst(strtolower($result->social));
    $ps->add(new $class($assocArray));


}

$ps->post();