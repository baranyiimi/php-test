<?php

namespace Model;

use Exception;
use PDO;
use PDOException;

abstract class AbstractModel{
    private static $conn;

    protected function getConnection(){
        if(!self::$conn){
            try {
                $conn = new PDO("mysql:host=database;dbname=symfony5", 'symfony', 'symfony');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
              }
        }
        return $conn;
    }

    protected function query(string $sql, array $params):array
    {
        $conn = $this->getConnection();
        
        $preparedStatement = $conn->prepare($sql);
        $preparedStatement->execute($params);

        if((int)$conn->errorCode()) {
            $errorInfo = $conn->errorInfo();
            throw new Exception($errorInfo[2], $conn->errorCode());
        }

        return $preparedStatement->fetchAll(PDO::FETCH_ASSOC);
    }
}