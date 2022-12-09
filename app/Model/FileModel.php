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
        $sql = "INSERT INTO files (name,path,user_id)
        VALUES (?, ?, ?);";
        try{
            $this->query($sql,[$file->getName(),$file->getPath(),$file->getUserId()]);
            return true;

        }catch(Exception $e){
            return false;
        }
    }
}