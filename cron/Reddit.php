<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class Reddit implements ISocialMediaService{
    public $Keys;
    public $Message;
    public $Link;

    public function __construct($keys, $message="",$link="")
    {
        $this->Keys = $keys;
        $this->Message = $message;
        $this->Link = $link;
    }


    public function post(){



        $client = new Client();
        $clientId = $this->Keys["reddit_clientId"];
        $clientSecret = $this->Keys["reddit_clientSecret"];
        $userAgent = $this->Keys["reddit_userAgent"];
        $username = $this->Keys["reddit_username"];
        $password = $this->Keys["reddit_password"];


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
                    'sr' => $this->Keys["subreddit"],
                    'kind' => 'link' ,// or 'self', 'image', 'video', 'gallery'
                    'title' => $this->Message,
                    'url' => $this->Link,
                ],
            ]);

            return 'Post submitted successfully';

        } catch (RequestException $e) {
            return 'Request failed: ' . $e->getMessage();
        }


    }
}

/*

Reddit does provide an API that allows for various forms of interaction, including posting messages. This is done through their JSON API, which is part of Reddit's overall OAuth2 platform. Please note that you need to follow Reddit's API terms of use when using the API. You can find the documentation for the API at: https://www.reddit.com/dev/api/.

To create an application for API usage, follow these steps:

Log into Reddit.
Go to https://www.reddit.com/prefs/apps
Scroll down to the "Developed Applications" section and click "Create App" or "Create Another App".
Fill out the form. The "name" is the name of your application. The "App type" should be "script" for personal use. The "description" and "about url" can be left blank. The "redirect uri" should be "http://localhost:8000" (without the quotes).
Click "Create app" at the bottom when you're done.
Now you should have a client ID and client secret for your application. These are needed to authenticate with the Reddit API.

With your Reddit API credentials, you can use Python's PRAW (Python Reddit API Wrapper) library to post automated messages from your blog to Reddit. Here is a basic example of how you could post a link:


Firstly, you need to add Guzzle as a dependency. You can do this by running the following command:

bash
Copy code
composer require guzzlehttp/guzzle
Then, you can use the following PHP code:

php
Copy code
<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client();
$clientId = 'my_client_id';
$clientSecret = 'my_client_secret';
$userAgent = 'my_user_agent';
$username = 'my_username';
$password = 'my_password';

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
        'sr' => 'subreddit_name',
        'kind' => 'link',
        'title' => 'Title of Your Blog Post',
        'url' => 'http://www.yourblog.com/your-blog-post',
    ],
]);

echo 'Post submitted successfully';

} catch (RequestException $e) {
echo 'Request failed: ' . $e->getMessage();
}
In this script, replace 'my_client_id', 'my_client_secret', 'my_user_agent', 'my_username', 'my_password', 'subreddit_name', 'Title of Your Blog Post', and 'http://www.yourblog.com/your-blog-post' with your actual values.

Remember that using the Reddit API requires respecting their rules and guidelines. Failure to comply may result in your application or account being banned. Also, keep in mind that the code here is a simple example and doesn't include some best practices for production code, such as secure credential storage and detailed error handling. Please check the latest Reddit API documentation to ensure the information provided here is still current.

The `client_id` and `client_secret` are credentials that you obtain from Reddit when you register an application. They are used to authenticate your application when it interacts with the Reddit API.

Here's how you can create a Reddit app and obtain your `client_id` and `client_secret`:

1. Log in to your Reddit account.

2. Navigate to the App Preferences page: https://www.reddit.com/prefs/apps.

3. Scroll down to the "Developed Applications" section and click "Create App" or "Create Another App".

4. In the form that appears, fill in the following fields:

   - `name`: Enter a name for your application.
   - `App type`: Choose "script".
   - `description`: (Optional) Describe your application.
   - `about url`: (Optional) If you have a webpage for your application, enter its URL here.
   - `postback url`: Enter "http://localhost:8000" (without the quotes).
   - `permissions`: Choose the appropriate permissions for your application.

5. Click "Create app" at the bottom when you're done.

After you've created your app, Reddit will provide you with a `client_id` and `client_secret`. The `client_id` is found right under the web app, and the `client_secret` is labeled as 'secret'. Be sure to keep these credentials confidential, as they allow access to the Reddit API on behalf of your application.

Remember to follow Reddit's API terms of service when using these credentials to interact with the API. Also, always check the most recent Reddit API documentation, as the process might have changed after my last update in September 2021.
*/
