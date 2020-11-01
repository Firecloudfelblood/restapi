<?php
namespace Src\TableGateways;

class UserGateway{
    private $db = null;

    public function __construct($db){
        $this->db = $db;
    }

    public function findAll(){
        $query = "
        SELECT id, user, pass
        from api_users;
        ";

        try{
            $statement = $this->db->query($query);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch(\PDOException $e){
            exit($e->getMessage());
        }
    }

    function find($user){
        $query = " Select user, password from api_users where user = ?";
        try{
            $statement = $this->db->prepare($query);
            $statement->execute(array($user));

            $result =  $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e){
            exit($e->getMessage());
        }
    }

}