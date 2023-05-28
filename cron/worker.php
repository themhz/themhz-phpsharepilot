<?php
require_once '../config.php';

interface ISocialMediaService{
    public function post();
}

class Facebook implements ISocialMediaService{
    public function post(){
        return "posting Facebook implementation";
    }
}

class Instagram implements ISocialMediaService{
    public function post(){
        return "posting Instagram implementation";
    }
}

class Twitter implements ISocialMediaService{
    public function post(){
        return "posting Twitter implementation";
    }
}

class Twitch implements ISocialMediaService{
    public function post(){
        return "posting Twitch implementation";
    }
}

class TikTok implements ISocialMediaService{
    public function post(){
        return "posting TikTok implementation";
    }
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