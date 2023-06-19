<?php
require_once 'CronConfig.php';
require_once 'Facebook.php';
require_once 'Reddit.php';
require_once 'Twitter.php';
require_once 'LinkedIn.php';


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
$ps->add(new Facebook());
//$ps->add(new Twitter()); ΟΚ
//$ps->add(new Reddit()); OK
//$ps->add(new LinkedIn());
$ps->post();