<?php

namespace Model;

use DateTime;
use Entity\File;
use Exception;

class FileModel extends AbstractModel{

    public function getAll(){
        $sql = "SELECT * FROM files where user_id = ?";

        $result = $this->query($sql,[$_SESSION["user_id"]]);

        $arr = [];
        foreach($result as $row){
            $file = new File;
            $file->setId($row["id"]);
            $file->setName($row["name"]);
            $file->setPath($row["path"]);
            $file->setCreated(new DateTime($row["created"]));
            $file->setUpdated(new DateTime($row["updated"]));
            $file->setSender($row["sender"]);
            $arr[] = $file;
        }
        return $arr;
    }

    public function insertFile(File $file){

        $sql = "INSERT INTO files (name,path,user_id,sender)
        VALUES (?, ?, ?, ?);";
        $array = [$file->getName(), $file->getPath(), $file->getUserId(), $file->getSender()];

        try{
            $this->query($sql,$array);
            return true;

        }catch(Exception $e){
            return false;
        }
    }

    public function getFileById(int $id){
        $sql = "SELECT * FROM files where id=?";

        $result = $this->query($sql,[$id]);
        if($result[0]){
            $file = new File;
            $file->setId($result[0]["id"]);
            $file->setName($result[0]["name"]);
            $file->setPath($result[0]["path"]);
            $file->setUserId($result[0]["user_id"]);
            return $file;
        }
    }

    public function updateFile(File $file){
        $sql = "UPDATE files SET name = ?, updated= ? WHERE id = ?;";
        try{
            $this->query($sql,[$file->getName(),date('Y-m-d H:i:s'),$file->getId()]);
            return true;

        }catch(Exception $e){
            return false;
        }
    }

    public function delete(int $id){
        $sql = "DELETE FROM files WHERE id = ?;";
        try{
            $this->query($sql,[$id]);
            return true;

        }catch(Exception $e){
            return false;
        }
    }

    public function getAllWithLimitAndSort($start){
        $sql = "SELECT * FROM files where user_id = ? limit ? ,20";

        $result = $this->query($sql,[$_SESSION["user_id"],$start]);

        $arr = [];
        foreach($result as $row){
            $file = new File;
            $file->setId($row["id"]);
            $file->setName($row["name"]);
            $file->setPath($row["path"]);
            $file->setCreated(new DateTime($row["created"]));
            $file->setUpdated(new DateTime($row["updated"]));
            $file->setSender($row["sender"]);
            $arr[] = $file;
        }
        return $arr;
    }

}