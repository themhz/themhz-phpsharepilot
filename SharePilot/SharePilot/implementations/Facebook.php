<?php
//https://developers.facebook.com/docs/pages-api/posts/
//This tutorial refers to facebook api in 14/02/2024
//1. Go and create an access token on this facebook link https://developers.facebook.com/tools/explorer/
//2. It will ask you to Register as a Facebook Developer to get started. 
//3. You will be redirected to this page https://developers.facebook.com/
//4. Click on the Get Started
//5. You will be redirected to this page https://developers.facebook.com/async/registration/dialog/?src=default
//6. Click Continue
//7. You will be asked to add your mobile number
//8. When you add your number you will be asked to verify your phone number by recieving a code via sms
//9. Then confirm your primary email
//10.You will be asked to improve your experience by telling them which of the following roles best describe you, and click developer.
//11.Then you will be added to the create app page https://developers.facebook.com/apps/ 
//12.From the menu on your left make sure All Apps is selected and then click create App
//13.You will be propmted with a popup informing you of the new way to create apps with meta. Click create App
//14.Select other and click next
//15.Select business app type anc click next
//16.Add your appName contact email and a business account optionally and click submit.
//17.You will be prompted with a password confirmation, do that and continue.
//18.Now we need to add products to our app
//19.Now click on basic and fill in the values like display name contact email etc.
//20.Now go to https://developers.facebook.com/tools/explorer/
//21.Add Permissions (publish_video, pages_show_list,pages_messaging, pages_read_engagement, pages_read_user_content,pages_manage_posts, pages_manage_engagement  )
//22.Click generate token. Token is generated
//23.Change user or page to your page. A popup may appear, click reconnect
//24.Then change user or page again to your page.
//25.Copy your token and go to https://developers.facebook.com/tools/debug/accesstoken/ and paste it to Access Token tab and click Debug
//26.Copy your pageId (Not the App ID)
//27.Go to SharePilot, and click Socials and make sure you have facebook record in
//28.Then click channels and add a channel. Click on the channel and add 2 keys. One key is pageId and the second is accessToken. 
//29.Set the values for these records
//30.You are ready to post, go to Lists and create one and select your channel. So this list belongs to that particular channel
//31.Now go to links and click add url. Get a url from youtube and click check
//32.Click Save
//33.Click on the link and add it to the link and the channel
//34.Now click update. We are ready to schedule. Click schedule link. 
//35.Go to Schedule links. You should be able to see this link there.
//36.Click update schedule, so you can schedule your link to be posted at a particular time.
//37.Then click update schedule after you set the time. Your post should be posted in your page.
//38.If its not then just run worker.php in the cron folder from command line, like php worker.php


//Create token
//https://developers.facebook.com/tools/explorer/

//Debug token
//https://developers.facebook.com/tools/debug/accesstoken/

//Guides
//https://developers.facebook.com/docs/graph-api/guides/debugging/
namespace SharePilotV2\Implementations;
use SharePilotV2\Components\ISocialMediaService;

class Facebook implements ISocialMediaService{

    public $Keys;
    public $Message;
    public $Link;

    public function __construct($keys, $message="",$link="")
    {
        $this->Keys = $keys;
        $this->Message = $message;
        $this->Link = $link;
    }


    public function post($messages){
        $result = false;
        try {
            foreach ($messages as $message){
                $result = $this->postToFacebookPageAsync($message->title, $message->url);
            }
            return $result;

        } catch (Exception $e) {

            return array("result"=>$result, "message"=>"Error connecting to database: " . $e->getMessage());
        }        
    }

    public function postToFacebookPageAsync($message, $link)
    {
        $accessToken = $this->Keys["accessToken"];
        $pageId = $this->Keys["pageId"];
    
        $requestUrl = "https://graph.facebook.com/v19.0/{$pageId}/feed?access_token={$accessToken}";

        $content = http_build_query([
            'message' => $message,
            'link' => $link
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception("Request failed: " . curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            throw new \Exception("Request failed with status code: {$httpCode} and response: {$response}");
        }

        // Assuming success if we reach this point
        return array("result"=>true, "message"=>$response);
    }

}



