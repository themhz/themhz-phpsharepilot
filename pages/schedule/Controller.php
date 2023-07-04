<?phpuse SharePilotV2\Libs\YoutubeService;use SharePilotV2\config;use SharePilotV2\Models\Urls;use SharePilotV2\Models\Scheduled_posts;use SharePilotV2\Components\ResponseHandler;use SharePilotV2\Components\RequestHandler; class Controller{    public function get(){    }     public function delete(){         $u = new Scheduled_posts();         $data = $u->delete()->where("id","=",$_POST["id"]);         ResponseHandler::respond($data);     }     public function getscheduledlinks()     {         $u = new Urls();         $sql = "SELECT u.*, sp.id as 'scheduled_id', sp.post_time, sp.is_posted                                          FROM urls u                                          INNER JOIN scheduled_posts sp ON u.id = sp.url_id                                                    ";         $channel_id = RequestHandler::get("channelid");         if(isset($channel_id) && $channel_id!= ""){             $sql .=" where u.channel_id = $channel_id";         }         $sql .=" order by id desc ";         $videos = $u->query($sql);         ResponseHandler::respond($videos);     }     public function updateschedulepost(){         $u = new Scheduled_posts();         $post_time_string = RequestHandler::get("post_time");         $u->post_time = new \DateTime($post_time_string);         $data = $u->update()->where("id","=",RequestHandler::get("id"))->execute();         ResponseHandler::respond($data);     }    public function fetchTasks()    {        $u = new Scheduled_posts();        $videos = $u->query("SELECT a.title as task, DATE(b.post_time) AS date, TIME(b.post_time) AS time, b.is_posted as posted FROM urls a INNER JOIN scheduled_posts b ON a.id = b.url_id");        ResponseHandler::respond($videos);    }     public function autoscheduleposts(){         $u = new Scheduled_posts();         $start_datetime = RequestHandler::get("start_datetime");         $hourInterval = RequestHandler::get("hourInterval");         $channelId = RequestHandler::get("channelId");         $avoid_start_hour  = RequestHandler::get("avoid_start_hour");         $avoid_end_hour = RequestHandler::get("avoid_end_hour");         ResponseHandler::respond($u->autoscheduleposts($start_datetime, $hourInterval, $channelId, $avoid_start_hour, $avoid_end_hour));     }     public function deleteautoscheduleposts(){         $u = new Scheduled_posts();         if(RequestHandler::get("channelid")!==null && RequestHandler::get("channelid")!=""){             ResponseHandler::respond($u->query("delete from scheduled_posts where url_id in (select id from urls where channel_id=".RequestHandler::get("channelid").");"));         }else{             ResponseHandler::respond($u->delete()->execute());         }     }     public function clearautoscheduleposts(){         $u = new Scheduled_posts();         if(RequestHandler::get("channelid")!==null && RequestHandler::get("channelid")!=""){             ResponseHandler::respond($u->query("UPDATE scheduled_posts SET post_time = NULL where url_id in (select id from urls where channel_id=".RequestHandler::get("channelid").");"));         }else{             ResponseHandler::respond($u->query("UPDATE scheduled_posts SET post_time = NULL;"));         }     }     public function restateschedule(){         $u = new Scheduled_posts();         $start_datetime = RequestHandler::get("start_datetime");         $hourInterval = RequestHandler::get("hourInterval");         $channelId = RequestHandler::get("channelId");         $avoid_start_hour  = RequestHandler::get("avoid_start_hour");         $avoid_end_hour = RequestHandler::get("avoid_end_hour");         ResponseHandler::respond($u->restateschedule($start_datetime, $hourInterval, $channelId, $avoid_start_hour, $avoid_end_hour));     } } 