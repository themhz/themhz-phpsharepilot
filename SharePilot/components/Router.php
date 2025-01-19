<?php

namespace SharePilotV2\Components;

class Router {
    private $routes = [];
    private $securedRoutes = [];
    private $defaultHomePage;    
    private $defaultMethod;

    public function __construct() {
        $this->defaultHomePage = $_ENV['DEFAULT_PAGE'];
        $this->defaultMethod = $_ENV['DEFAULT_METHOD'];
    }

    public function addRoute($path, $controllerPath = null, $secure = false) {
        $this->routes[$path] = $controllerPath;
        if ($secure) {
            $this->securedRoutes[] = $path;
        }
    }

    public function run() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
    
        
        // Extract parameters
        $params = $this->extractParams($requestUri);
        
    
        $page = $params['page'] ?? $this->defaultHomePage;

        if(!empty($params["method"])){
            $action = $params["method"];
        }elseif(!empty($method)){
            $action = $method;
        }else{
            $action = $this->defaultHomePage;
        }
        //$action = $params["method"] ?? $this->defaultMethod;
        $id = $params['id'] ?? null;
                
        //echo $method;
        //print_r($action);
        //die();
        $this->handlePage($page, $action, $id, $method);
    }
        
    

    private function handlePage($page, $action, $id, $method) {                   
            
        // Define paths for public and private pages
        $publicPathController = dirname(__DIR__) . "/endpoints/public/pages/$page/Controller.php";
        $publicPathPage = dirname(__DIR__) . "/endpoints/public/pages/$page/index.php";

        $privatePathController = dirname(__DIR__) . "/endpoints/private/pages/$page/Controller.php";        

        $controllerPath = null;
        
        // Check if the public controller exists
        if (file_exists($publicPathController)) {
            $controllerPath = $publicPathController;
            $templatePath = dirname(__DIR__) . "/endpoints/public/template/index.php";
            $isSecured = false;
            //Else check if a simple page exists. Notice that the controller is not present

        }elseif (file_exists($publicPathPage)) {
            //$templatePath = dirname(__DIR__) . "/endpoints/public/template/index.php";
            $templatePath = $publicPathPage;
            $isSecured = false;

            //Else check if the private controller exists.
        }elseif (file_exists($privatePathController)) {
            $controllerPath = $privatePathController;
            $templatePath = dirname(__DIR__) . "/endpoints/private/template/index.php";
            $isSecured = true;
        }else {
            // If no controller exists, return 404
            http_response_code(404);
            include_once dirname(__DIR__) . "/endpoints/public/pages/404/index.php";            

            


            return;
        }
    
        // Check if authentication is required
        if ($isSecured && !$this->isAuthenticated()) {
            http_response_code(403);
            //echo "403 Forbidden: Authentication required.";
            //include_once dirname(__DIR__) . "/endpoints/public/pages/403/index.php";
            include_once dirname(__DIR__) . "/endpoints/public/pages/login/login.php";
            return;
        }
    
        // Invoke the controller, passing the template path
        $this->invokeController($controllerPath, $action, $id, strtolower($method), $templatePath);
    }
            

    private function invokeController($controllerPath, $action, $id, $method, $templatePath) {
        // echo $action;
        // die();
        if($controllerPath==null){

            include_once $templatePath;
            return;
        }
        if (file_exists($controllerPath)) {
            include_once $controllerPath;
    
            if (class_exists('Controller')) {
                $controller = new \Controller();
                   
                if(empty($action)){
                    $controller->$method($id, $method, $templatePath);
                }else if (method_exists($controller, $action)) {
                    // Pass the template path to the controller
                    $controller->$action($id, $method, $templatePath);
                } else {
                    http_response_code(404);
                    echo "404 Action Not Found";
                }
            } else {
                http_response_code(500);
                echo "500 Internal Server Error: Controller class not found.";
            }
        } else {
            http_response_code(404);
            echo "404 Controller Not Found";
        }
    }
    
    

    private function extractParams($requestUri) {
        $parts = explode('/', trim($requestUri, '/'));
    
      
        // Treat root URL / as /home
        if (empty($parts[0])) {
            $parts[0] = $this->defaultHomePage;
        }
        
        return [
            'page' => $parts[0] ?? null,
            'method' => $parts[1] ?? null,
            'id' => $parts[2] ?? null,
        ];
    }
    

    private function isAuthenticated() {
        // Replace with your authentication logic
        return isset($_SESSION['user']);
    }
}