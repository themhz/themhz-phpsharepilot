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
        $action = $params['method'] ?? $this->defaultMethod;
        $id = $params['id'] ?? null;
    
        
        // Debug output
        //echo "Page: $page, Action: $action, ID: $id, Method: $method";
        
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
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Page Not Found</title>
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        font-family: Arial, sans-serif;
                        background-color: #f8f9fa;
                        text-align: center;
                    }
                    .message-container {
                        max-width: 400px;
                        font-size: 2rem; /* Increased font size */
                        font-weight: bold; /* Bold text */
                    }
                </style>
            </head>
            <body>
                <div class='message-container'>
                    <p>Page not found. Redirecting to the main page in <span id='countdown'>2</span> seconds...</p>
                </div>
                <script>
                    let countdown = 2; // Initial countdown value in seconds
                    let countdownElement = document.getElementById('countdown');

                    // Update countdown every second
                    let interval = setInterval(function() {
                        countdown--;
                        countdownElement.textContent = countdown; // Update the countdown text

                        // Redirect after countdown reaches 0
                        if (countdown <= 0) {
                            clearInterval(interval);
                            window.location.href = '/'; // Redirect to the main page
                        }
                    }, 1000); // 1000 milliseconds = 1 second
                </script>
            </body>
            </html>
            ";

            


            return;
        }
    
        // Check if authentication is required
        if ($isSecured && !$this->isAuthenticated()) {
            http_response_code(403);
            //echo "403 Forbidden: Authentication required.";
            include_once dirname(__DIR__) . "/endpoints/public/pages/403/index.php";
            return;
        }
    
        // Invoke the controller, passing the template path
        $this->invokeController($controllerPath, $action, $id, strtolower($method), $templatePath);
    }
            

    private function invokeController($controllerPath, $action, $id, $method, $templatePath) {
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
    
      
        // Treat root URL `/` as `/home`
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
