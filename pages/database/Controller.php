<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\Config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;

 class Controller{
    public function get(){


    }

    public function getvideo()
    {
        $u = new Urls();
        $videos = $u->select([],["id"=>"desc"]);;
        ResponseHandler::respond($videos);
    }

    public function fetchurl() {

        if(isset($_POST["url"])){
            $url = $_POST["url"];

            $page_info = array();

            // Check if URL is a YouTube video
            if (strpos($url, 'youtube.com/watch?v=') !== false) {
                $html = file_get_contents($url);

                // Create a DOM parser
                $doc = new DOMDocument();

                // Use error suppression to ignore warnings generated from improperly formed HTML
                @$doc->loadHTML($html);

                // Get the title
                $page_info['title'] = $doc->getElementsByTagName('title')->item(0)->nodeValue;

                // Get the meta tags
                $metas = $doc->getElementsByTagName('meta');


                // Loop through the meta tags
                for ($i = 0; $i < $metas->length; $i++) {
                    $meta = $metas->item($i);

                    // Get the description
                    if($meta->getAttribute('name') == 'description') {
                        $page_info['description'] = $meta->getAttribute('content');
                    }

                    // Get the image url
                    if($meta->getAttribute('property') == 'og:image') {
                        $page_info['image'] = $meta->getAttribute('content');
                    }
                }
            } else {
                // Create a new cURL resource
                $ch = curl_init();
                // Set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);

                // Grab URL and pass it to the browser
                $html = curl_exec($ch);

                // Close cURL resource, and free up system resources
                $html = curl_exec($ch);

                // Create a DOM parser
                $doc = new DOMDocument();
                @$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$html);

                // Fetch meta tags
                $metas = $doc->getElementsByTagName('meta');

                // Loop through meta tags and find relevant info
                for ($i = 0; $i < $metas->length; $i++) {
                    $meta = $metas->item($i);
                    if (strtolower($meta->getAttribute('property')) == 'og:title') {
                        $page_info['title'] = $meta->getAttribute('content');
                    }
                    if (strtolower($meta->getAttribute('property')) == 'og:description') {
                        $page_info['description'] = $meta->getAttribute('content');
                    }
                    if (strtolower($meta->getAttribute('property')) == 'og:image') {
                        $page_info['image'] = $meta->getAttribute('content');
                    }
                }

                // If some data wasn't found in meta tags, try finding it elsewhere
                if (!isset($page_info['title'])) {
                    $titles = $doc->getElementsByTagName('title');
                    if ($titles->length > 0) {
                        $page_info['title'] = $titles->item(0)->nodeValue;
                    }
                }

                if (!isset($page_info['description'])) {
                    $page_info['description'] = "No description found.";
                }

                if (!isset($page_info['image'])) {
                    $page_info['image'] = "No image found.";
                }
            }
            ResponseHandler::respond($page_info);


        }else{
            $page_info['error'] = "problem with website";
            ResponseHandler::respond($page_info);
        }

    }


    public function addurl(){
        $u = new Urls();

        $u->url = RequestHandler::get("url");
        $u->title = RequestHandler::get("title");
        $u->source = 1;
        $u->type = 1;
        $u->thumbnailUrl = RequestHandler::get("image");

        $id = $u->insert();
        ResponseHandler::respond(["message"=>"Url Was inserted with id:{$id}"]);

    }
 }





 