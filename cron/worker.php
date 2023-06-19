<?php
require_once 'CronConfig.php';
require_once 'Facebook.php';
require_once 'Reddit.php';
require_once 'Twitter.php';
require_once 'LinkedIn.php';
require_once 'Database.php';


interface ISocialMediaService
{
    public function post();
}

class PostingService
{
    private $sm = [];

    public function add(ISocialMediaService $sm)
    {
        $this->sm[] = $sm;
    }

    public function post()
    {
        for ($i = 0; $i < count($this->sm); $i++) {
            echo $this->sm[$i]->post() . "\n\r";
        }
    }
}


$db = Database::getInstance();
$sql = "select distinct channel_id, social_id from channel_social_keys";
$sth = $db->prepare($sql);
$sth->execute();
$results = $sth->fetchAll(\PDO::FETCH_OBJ);

foreach ($results as $result){
    //print_r($result);
    $channel_id=$result->channel_id;
    $social_id=$result->social_id;

    $sql = "select name, value from channel_social_keys where channel_id=$channel_id and social_id=$social_id";
    $sth = $db->prepare($sql);
    $sth->execute();
    $keyvalue = $sth->fetchAll(\PDO::FETCH_OBJ);
    print_r($keyvalue);
}


//$ps = new PostingService();
//$ps->add(new Facebook());
//$ps->add(new Twitter()); ΟΚ
//$ps->add(new Reddit()); OK
//$ps->add(new LinkedIn());
//$ps->post();