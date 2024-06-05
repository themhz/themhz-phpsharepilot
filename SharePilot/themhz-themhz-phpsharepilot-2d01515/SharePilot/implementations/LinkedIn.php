<?php
namespace SharePilotV2\Implementations;
class LinkedIn implements ISocialMediaService{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function post($messages){
        $this->clientId = "";
        $this->clientSecret= "";
        $this->redirectUri= "";

        $this->authenticate();
        $this->createPost("hello LinkedIn");


    }
    public function authenticate() {
        if (isset($_GET['code'])) {
            $this->getAccessToken($_GET['code']);
        } else {
            $this->getAuthorizationCode();
        }
    }
    private function getAuthorizationCode() {
        $state = bin2hex(random_bytes(15));
        $_SESSION['state'] = $state;

        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
            'scope' => 'r_liteprofile%20w_member_social'
        ];

        header('Location: https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params));
        exit;
    }
    private function getAccessToken($authorizationCode) {
        if ($_GET['state'] !== $_SESSION['state']) {
            exit('Invalid state');
        }

        $params = [
            'grant_type' => 'authorization_code',
            'code' => $authorizationCode,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.linkedin.com/oauth/v2/accessToken");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $_SESSION['access_token'] = $data['access_token'];
    }
    public function createPost($content) {
        $access_token = $_SESSION['access_token'];

        $headers = [
            'Content-Type: application/json',
            'X-Restli-Protocol-Version: 2.0.0',
            'Authorization: Bearer ' . $access_token
        ];

        $body = [
            "author" => "urn:li:person:".$this->getProfileUrn($access_token), // TODO: replace with your profile URN
            "lifecycleState" => "PUBLISHED",
            "specificContent" => [
                "com.linkedin.ugc.ShareContent" => [
                    "shareCommentary" => [
                        "text" => $content
                    ],
                    "shareMediaCategory" => "NONE"
                ]
            ],
            "visibility" => [
                "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC"
            ]
        ];

        $options = [
            'http' => [
                'header'  => $headers,
                'method'  => 'POST',
                'content' => json_encode($body),
            ],
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents('https://api.linkedin.com/v2/ugcPosts', false, $context);

        if ($response === false) {
            echo "Failed to create post";
        } else {
            echo "Your post was created successfully";
        }
    }
    public function getProfileUrn($access_token) {
        //$access_token = $_SESSION['access_token'];

        $headers = [
            'Content-Type: application/json',
            'X-Restli-Protocol-Version: 2.0.0',
            'Authorization: Bearer ' . $access_token
        ];

        $options = [
            'http' => [
                'header'  => $headers,
                'method'  => 'GET'
            ],
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents('https://api.linkedin.com/v2/me', false, $context);

        if ($response === false) {
            echo "Failed to retrieve profile";
        } else {
            $data = json_decode($response, true);
            return $data['id']; // This is your URN
        }
    }

}
