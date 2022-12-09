<?php

namespace Model;

use Entity\User;
use Exception;

class UserModel extends AbstractModel{

    public function getAll(){
        $sql = "SELECT * FROM users";

        $result = $this->query($sql,[]);
        var_dump($result);
    }

    public function save(User $user){

        $sql = "INSERT INTO users (username,email,password)
        VALUES (?, ?, ?);";
        try{
            $this->query($sql,[$user->getUserName(),$user->getEmail(),$user->getPassword()]);
            return true;

        }catch(Exception $e){
            return false;
        }
    }

    public function getUserByUserName(string $username){
        $sql = "SELECT * FROM users where username=?";

        $result = $this->query($sql,[$username]);
        if($result[0]){
            $user = new User;
            $user->setId($result[0]["id"]);
            $user->setUserName($result[0]["username"]);
            $user->setEmail($result[0]["email"]);
            $user->setPassword($result[0]["password"]);
            return $user;
        }
    }
}