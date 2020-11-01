<?php
require 'bootstrap.php';

    $statement = <<< EOS
        CREATE TABLE IF NOT EXISTS api_users(
        id INT NOT NULL AUTO_INCREMENT ,
        user VARCHAR(100) NOT NULL,
        pass VARCHAR(100) NOT NULL,
        PRIMARY KEY (id)
        ) ENGINE = INNODB;
        
        INSERT INTO api_users(id, user, pass)
        VALUES 
        (1,'ironman', 'stark123'),
        (2, 'arrow','4evergreen')
        (3, 'spidy','maryjane')
        (4, 'batman','bwayne')
        (5, 'superman','kalel')
EOS;

try{
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e){
    exit($e->getMessage());
}