<?php
require_once 'CronConfig.php';
require_once 'Database.php';

$db = Database::getInstance();

function exchangeShortLivedTokenForLongLived($appId, $appSecret, $shortLivedAccessToken) {
    $url = "https://graph.facebook.com/v19.0/oauth/access_token";

    $params = array(
        'grant_type'        => 'fb_exchange_token',
        'client_id'         => $appId,
        'client_secret'     => $appSecret,
        'fb_exchange_token' => $shortLivedAccessToken
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    
    $result = curl_exec($ch);
    
    if (!$result) {
        die("Failed to exchange token: " . curl_error($ch));
    }
    
    curl_close($ch);
    
    $response = json_decode($result, true);
    
    if (isset($response['error'])) {
        die("Error in token exchange: " . $response['error']['message']);
    }
    
    return $response['access_token'];
}

// Usage
//Go to https://developers.facebook.com/apps/ to get the app id
//Go to https://developers.facebook.com/tools/explorer/ to get your token
//Dont forget to add 
//{publish_video,pages_show_list,pages_messaging,pages_read_engagement,pages_read_user_content,pages_manage_posts,pages_manage_engagement}
//Select the correct Meta App and the correct User or Page

$appId = ''; // Replace with your app's ID
$appSecret = ''; // Replace with your app's secret
$shortLivedAccessToken = ''; // Replace with your short-lived access token

$longLivedAccessToken = exchangeShortLivedTokenForLongLived($appId, $appSecret, $shortLivedAccessToken);

echo "Long-lived Access Token: " . $longLivedAccessToken . "\n";