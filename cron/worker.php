<?php
require_once 'CronConfig.php';
require_once 'Facebook.php';
require_once 'Instagram.php';
require_once 'Reddit.php';
require_once 'Twitch.php';
require_once 'Twitter.php';

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

$ps = new PostingService();
//$ps->add(new Facebook()); ΟΚ
//$ps->add(new Instagram());
//$ps->add(new Twitter()); ΟΚ
$ps->add(new Reddit());
$ps->post();