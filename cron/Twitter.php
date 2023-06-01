<?php
require 'vendor/autoload.php';
require 'CronConfig.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use SharePilotV2\CronConfig;

//Important urls
//https://twittercommunity.com/new
//https://developer.twitter.com/en/portal/dashboard
//https://developer.twitter.com/en/portal/projects/1663182518089134082/apps/27221400/keys
//https://www.postman.com/twitter/workspace/twitter-s-public-workspace/collection/9956214-784efcda-ed4c-4491-a4c0-a26470a67400?ctx=documentation
//https://www.postman.com/explore
//https://developer.twitter.com/en/docs/tutorials/postman-getting-started
//https://twittercommunity.com/t/453-you-currently-have-access-to-twitter-api-v2-endpoints-and-limited-v1-1-endpoints-only/193292/10

class Twitter implements ISocialMediaService{
    public function post(){

        //$this->uploadtweet();
        $this->tweet("Hello World");
        //return "posting Twitter implementation";

    }


    public function uploadtweet(){
        $consumer_key = 'cpBXk3DhyCANvdQtjpvxmLB3C';
        $consumer_secret = 'Ar57VS71PxMY6xwiEMdg5dpV7cyb6rX74LjckK8KVYHXgF4ZdY';
        $access_token = '78294985-68h5YCG7HNs1L9tDwdq4Vv9dkpzU6vzTht02MjSgb';
        $access_token_secret = 'lZ1o4ik0OuPmEw9GC1wUPZJXFZ8grw5UyIMoutKN5w4WL';

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

        // Path to the image file
        $image_path = 'C:\Users\themhz\Pictures\melita.jpg';

        // Upload image to Twitter
        $media = $connection->upload('media/upload', ['media' => $image_path]);

        // Tweet text
        $status = 'some random text.';

        // Post tweet with image
        $post_tweets = $connection->post("statuses/update", [
            'status' => $status,
            'media_ids' => $media->media_id
        ]);

        print_r($post_tweets);

//        if ($connection->getLastHttpCode() == 200) {
//            echo "Tweet with image posted successfully";
//        } else {
//            echo "Error posting tweet";
//        }
    }
    public function tweet($message) {
        // You will get these keys from Twitter's developer portal after creating your application
        $consumer_key = CronConfig::$config["consumer_key"];
        $consumer_secret = CronConfig::$config["consumer_secret"];
        $access_token = CronConfig::$config["access_token"];
        $access_token_secret = CronConfig::$config["access_token_secret"];

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

        $status = 'Hello, Twitter!';
        $connection->setApiVersion('2');
        $post_tweets = $connection->post("tweets", ["text" => $message], true);


        print_r($post_tweets);
//        if ($connection->getLastHttpCode() == 200) {
//            echo "Tweet posted successfully";
//        } else {
//            //echo "Error posting tweet";
//        }

    }
}