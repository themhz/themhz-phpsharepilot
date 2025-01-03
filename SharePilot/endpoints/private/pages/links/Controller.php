<?php
use SharePilotV2\Libs\YoutubeService;
use SharePilotV2\config;
use SharePilotV2\Models\Urls;
use SharePilotV2\Models\Scheduled_posts;
use SharePilotV2\Models\Lists;
use SharePilotV2\Components\ResponseHandler;
use SharePilotV2\Components\RequestHandler;
 class Controller{
    public function get($id = null, $method = 'GET', $templatePath = null) {
        
        //echo "Welcome to the Default Action!";
        // Specify the content file
        $content = dirname(__FILE__) . '/links.php';

        // Include the master template
        if ($templatePath) {
            include $templatePath;
        } else {
            echo "Master template not found!";
        }
    }
    public function search(){
        $u = new Urls();
        $search = RequestHandler::get("search");
        //$result = $u->select(["title like "=>"%".$search."%"],[]);
        $result = $u->select()->where("title","like","%".$search."%")->execute();
        ResponseHandler::respond($result);
    }
    public function getlinks()
    {
        $channelid = RequestHandler::get("channelid");
        $listid = RequestHandler::get("listid");

        $u = new Urls();
        if($channelid==null){
            //$videos = $u->select([],["id"=>"desc"]);
            $videos = $u->query("select urls.*,channels.name as channel_name , lists.name as list_name
                                    from urls 
                                      left join channels on urls.channel_id = channels.id 
                                    left join lists on urls.list_id = lists.id
                                    order by urls.id desc;");
            }else{
                $sql = "select urls.*,
                                channels.name as channel_name, 
                                lists.name as list_name
                        from urls 
                        left join channels on urls.channel_id = channels.id 
                        left join lists on urls.list_id = lists.id
                        where urls.channel_id = $channelid ";

            if($listid!=null && $listid!="" && $listid!="0"){
                $sql .= " and urls.list_id=$listid ";
            }

            $sql .= " order by urls.id desc ";            

            $videos = $u->query($sql);
        }
        ResponseHandler::respond($videos);
    }


    public function loadlists(){
        $l = new Lists();
        $channel_id = RequestHandler::get("channel_id");
        if($channel_id!="" and $channel_id!= null){
            $lists = $l->select()->where("channel_id", "=", $channel_id)->execute();
            ResponseHandler::respond($lists);
        }else{
            ResponseHandler::respond([]);
        }
    }
    public function delete(){
        $u = new Urls();
        $id = RequestHandler::get("id");
        $data = $u->delete()->where("id","=",$id)->execute();
        ResponseHandler::respond(["result"=>$data]);
    }
    public function schedulepost(){
        $u = new Urls();
        $sp = new scheduled_posts();
        $id = RequestHandler::get("id");
        $result = $u->select()
            ->fields("urls.id",
                "urls.list_id",
                "urls.url",
                "urls.title",
                "urls.source",
                "urls.type",
                "urls.thumbnailurl","channels.name as channel_name",
                "channels.id as channel_id"
            )
            ->join("inner","channels", "channels.id = urls.channel_id")
            ->where("urls.id","=",$id)
            ->execute();

        $sp->url_id = $result[0]["id"];
        $sp->list_id = $result[0]["list_id"];
        $sp->channel_id = $result[0]["channel_id"];
        $sp->regdate = date("Y/m/d H:i:s");

        $result = $sp->insert();
        if ($result > 0) {
            ResponseHandler::respond(["result"=>true, "message"=>"Insertion was successful!"]);
        } else {
            ResponseHandler::respond(["result"=>false, "message"=>"Insertion failed!!"]);
        }
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
                @$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'.$html);
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
        $u->description = RequestHandler::get("description");
        $u->channel_id = RequestHandler::get("channel_id");
        $u->list_id = RequestHandler::get("list_id");
        $u->source = 1;
        $u->type = 1;
        $u->thumbnailUrl = RequestHandler::get("image");
        $u->regdate = date("Y/m/d H:i:s");
        $id = $u->insert();
        ResponseHandler::respond(["message"=>"Url Was inserted with id:{$id}"]);
    }

    public function update(){
        $u = new Urls();

        $u->title = RequestHandler::get("title");
        $u->description = RequestHandler::get("description");
        $u->url = RequestHandler::get("url");
        $u->thumbnailUrl = RequestHandler::get("thumbnailUrl");

        if(RequestHandler::get("channel_id") != ""){
            $u->channel_id = RequestHandler::get("channel_id");    
        }                        

        if(RequestHandler::get("list_id") != ""){
            $u->list_id = RequestHandler::get("list_id");    
        }                        
        $result = $u->update()->where("id","=",RequestHandler::get("id"))->execute();
        ResponseHandler::respond($result);
    }

    public function addnewlist(){
        if(RequestHandler::get("name")!=null && RequestHandler::get("channel_id") !=null){
            $lists = new Lists();
            $lists->name = RequestHandler::get("name");
            $lists->channel_id = RequestHandler::get("channel_id");
            $result = $lists->insert();

            ResponseHandler::respond(["result"=>$result]);
        }else{
            ResponseHandler::respond(["result"=>false]);
        }

    }
 }
 
