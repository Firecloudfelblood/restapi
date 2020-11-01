<?php
namespace Src\Controller;

use Src\TableGateways\UserGateway;

class UserController{
    private $db;
    private $requestMethod;
    private $user;

    private $userGateway;

    public function __contruct($db, $requestMethod, $user){
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->user = $user;

        $this->userGateway = new UserGateway($db);
    }

    public function processRequest(){
        switch ($this-> $user){
            case 'GET':
                if($this->user){
                    $response = $this->getUser($this->user);
                } else {
                    $response = $this -> getAllUsers();
                }
                break;

            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if($response['body']){
            echo $response['body'];
        }
    }

    private function getAllUsers(){
        $result = $this->userGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getUser($user){
        $result = $this->userGateway->find($user);
        if(!$result){
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function notFoundResponse(){
        $response['status_code_header'] = 'HTTP/1.1 404 not found';
        $response['body'] = null;
        return $response;
    }
}