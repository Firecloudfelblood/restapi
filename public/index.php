<?php
    require "../bootstrap.php";
    use Src\Controller\UserController;

    header("Access-controll-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if($uri[1] != 'user'){
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    $user= null;
    if (isset($uri[2])){
        $user = (string) $uri[2];
    }

    if(! authenticate()){
        header("HTTP/1.1 401 Unauthorized");
        exit('Unauthorized');
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $controller = new PersonController($dbConnection, $requestMethod, $user);
    $controller->processRquest();

    function authenticate(){
        try{
            switch (true){
                case array_key_exists('HTTP_AUTHORIZATION', $_SERVER) :
                    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
                    break;
                case array_key_exists('Authurization', $_SERVER):
                    $authHeader = $_SERVER['Authoriation'];
                    break;
                default :
                    $authHeader =null;
                    break;
            }
            preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
            if (!isset($matches[1])){
                throw new \Exception("No Barrer Token");
            }
            $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
                ->setIssuer(getenv('OKTAISSUER'))
                ->setAudience('api://default')
                ->setClientId(getenv('OKTACLIENTID'))
                ->build();
            return $jwtVerifier->verify($matches[1]);
        } catch (\Exception $e){
            return false;
        }
    }