<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class Reddit implements ISocialMediaService{
    public function post(){


        $client = new Client();
        $clientId = CronConfig::$config["reddit_clientId"];;
        $clientSecret = CronConfig::$config["reddit_clientSecret"];;
        $userAgent = CronConfig::$config["reddit_userAgent"];;
        $username = CronConfig::$config["reddit_username"];;
        $password = CronConfig::$config["reddit_password"];;

        try {
            // Retrieve the access token
            $response = $client->request('POST', 'https://www.reddit.com/api/v1/access_token', [
                'auth' => [$clientId, $clientSecret],
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password,
                ],
                'headers' => [
                    'User-Agent' => $userAgent,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $accessToken = $data['access_token'];

            // Use the access token to make an API request
            $response = $client->request('POST', 'https://oauth.reddit.com/api/submit', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'User-Agent' => $userAgent,
                ],
                'form_params' => [
                    'sr' => 'theotokatosboxing',
                    'kind' => 'link' ,// or 'self', 'image', 'video', 'gallery'
                    'title' => 'History of boxing',
                    'url' => 'https://www.youtube.com/watch?v=uV-C054FUgc',
                ],
            ]);

            return 'Post submitted successfully';

        } catch (RequestException $e) {
            return 'Request failed: ' . $e->getMessage();
        }


    }
}