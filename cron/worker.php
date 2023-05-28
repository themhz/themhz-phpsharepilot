<?php
require_once '../config.php';
require_once 'Facebook.php';
require_once 'Instagram.php';
require_once 'TikTok.php';
require_once 'Twitch.php';
require_once 'Twitter.php';

interface ISocialMediaService{
    public function post();
}

class PostingService{
    private $sm = [];

    public function add(ISocialMediaService $sm){
        $this->sm[] = $sm;
    }

    public function post(){
        for($i=0;$i<count($this->sm);$i++){
            echo $this->sm[$i]->post()."\n\r";
        }
    }
}

$ps = new PostingService();
$ps->add(new Facebook());
$ps->add(new Instagram());
$ps->add(new Twitter());
$ps->add(new Twitch());
$ps->add(new TikTok());
$ps->post();