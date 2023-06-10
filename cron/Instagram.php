<?php
require 'vendor/autoload.php';


class Instagram implements ISocialMediaService{

    public function post(){
        $access_token = 'YOUR_ACCESS_TOKEN';
        $url = "https://graph.instagram.com/me/media?fields=id,caption&access_token=EAAR4ZBGA4LtgBABWMRJbdvPL3YEmjaBvdqZBAZChY79BydKbhbyvFl0ErD9dfTp9pjyzpzopLWdCREJZBJQw2vlvCk5et7dGZBAL25nNKPJKYXWpiIzkQxz0d9BH7SCKCM6JruWs13vkZAUfUMPOooZBKnvqkGypnkhd4N7YtcLSiN02uOi5qQT87srqPd57n4qnK8OqUG1khDAZApePANtu";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        print_r($response);

//        foreach ($posts as $post) {
//            echo "ID: {$post['id']}\n";
//            echo "Caption: {$post['caption']}\n";
//        }


    }


}