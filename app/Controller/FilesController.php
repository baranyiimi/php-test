<?php
namespace Controller;
use Controller\AbstractController;
use Entity\File;
use Model\FileModel;
use Model\UserModel;
use View\Template;

class FilesController extends AbstractController{

    public static function getAction($urlParts){
        if ($urlParts[1]=='files'){
            if(isset($_SESSION["username"])){
                if($_SERVER['REQUEST_METHOD']=="GET"){
                    return 'show';
                }
                if($_SERVER['REQUEST_METHOD']=="POST"){
                    return 'upload';
                }
            }
            return 'notLoggedIn';
        }else if($urlParts[1]=='send'){
            if(isset($_SESSION["username"])){
                if($_SERVER['REQUEST_METHOD']=="GET"){
                    return 'showSend';
                }
                if($_SERVER['REQUEST_METHOD']=="POST"){
                    return 'sendFile';
                }
            }
            return 'notLoggedIn';
        }else if($urlParts[1]=='delete'){
            if(isset($_SESSION["username"])){
                if($_SERVER['REQUEST_METHOD']=="GET"){
                    return 'delete';
                }
            }
            return 'notLoggedIn';
        }
    }

    function doShow($url,$query){
        $header = new Template('Base/header');

        $header = str_replace("{{template_auth}}","/logout",$header->render());
        $header = str_replace("{{template_auth_button}}","Kijelentkezés",$header);
        $model = new FileModel;
        $page = 1;
        $beforButton = "/files";
        $nextButton = "/files?page=2";
        $start = 0;
        if(isset($query["page"])){
            $page = $query["page"];
            if($page > 1){
                $beforButton = "/files?page=".$page-1;
                $nextButton = "/files?page=".$page+1;
                $start = $page * 20;
            }
        }
        $files = $model->getAllWithLimitAndSort($start);
        $userModel = new UserModel;
        $table = '';
        foreach($files as $file){
            $senderName = 'Nincs küldő';
            if($file->getSender()){
                $senderName = $userModel->getUserById($file->getSender())->getUserName();
            }
            $table .= '<tr>
                <th scope="row">' . $file->getId() . '</th>
                <td>' . $file->getName() . '</td>
                <td>' . $file->getCreated()->format('Y-m-d H:i:s') . '</td>
                <td>' . $file->getUpdated()->format('Y-m-d H:i:s') . '</td>
                <td>' . $senderName . '</td>
                <td><a href="/edit/'.$file->getId().'" class="btn btn-primary">Módosítás</a></td>
                <td><a href="/send/'.$file->getId().'" class="btn btn-primary">Küldés</a></td>
                <td><a href="/delete/'.$file->getId().'" class="btn btn-primary">Törlés</a></td>
                </tr>';
        }
        $view = new Template('Files/List');
        $view = str_replace("{{template_table}}",$table,$view->render());
        $view = str_replace("{{template_before_button}}",$beforButton,$view);
        $view = str_replace("{{template_after_button}}",$nextButton,$view);
        
        $footer = new Template('Base/footer');
        return $header.$view.$footer->render();
    }

    function doNotLoggedIn(){
        header("Location: /login");
    }

    function doUpload(){

        if(!is_dir(PROJECT_ROOT.'/uploads')){
            mkdir(PROJECT_ROOT.'/uploads', 0777, true);
        }
        $target_dir = PROJECT_ROOT.'/uploads/';
        $originalName = basename($_FILES["filename"]["name"]);
        $extension = strtolower(pathinfo($originalName,PATHINFO_EXTENSION));
        $newName = md5(time() . $originalName . rand(0, 10000));
        if(!$extension == "txt"){
            return "hiba";
        }

        if ($_FILES["filename"]["size"] > 500000) {
            return "Sorry, your file is too large.";
        }
        $file = new File();
        $file->setName($originalName);
        $file->setPath($target_dir . $newName . '.' . $extension);
        $file->setUserId($_SESSION["user_id"]);
        $file->setSender(null);
        $model = new FileModel;
        $model->insertFile($file);
        move_uploaded_file($_FILES["filename"]["tmp_name"], $target_dir.$newName.'.'.$extension);
        header("Location: /files");
    }

    public function doShowSend($url){
        $fileId = $url[2];
        $header = new Template('Base/header');
        $header = str_replace("{{template_auth}}","/logout",$header->render());
        $header = str_replace("{{template_auth_button}}","Kijelentkezés",$header);

        $model = new UserModel;

        $users = $model->getOtherUsers($_SESSION["user_id"]);
        $options='';
        foreach($users as $user){
            $options.='<option value="'.$user->getId().'">'.$user->getUserName().'</option>';
        }

        $view = new Template('Files/SendFile');
        $view = str_replace("{{template_users}}",$options,$view->render());
        $view = str_replace("{{template_id}}",$fileId,$view);

        $footer = new Template('Base/footer');
        return $header.$view.$footer->render();
    }

    public function doSendFile()
    {
        $fileId = $_POST['file_id'];
        $userId = $_POST['user_id'];
        $fileModel = new FileModel;
        $userModel = new UserModel;
        $file = $fileModel->getFileById($fileId);
        $target_dir = PROJECT_ROOT.'/uploads/';
        $originalName = $file->getName();
        $newName = md5(time() . $originalName . rand(0, 10000));
        
        copy($file->getPath(),$target_dir . $newName . '.txt');

        $newFile = new File;
        $newFile->setName($originalName);
        $newFile->setPath($target_dir . $newName . '.txt');
        $newFile->setSender($_SESSION['user_id']);
        $newFile->setUserId($userId);
        $fileModel->insertFile($newFile);
        header("Location: /files");

    }

    public function doDelete($url){
        $fileId = $url[2];
        $model = new FileModel;
        $file = $model->getFileById($fileId);
        $filePath = $file->getPath();
        unlink($filePath);
        $model->delete($fileId);
        header("Location: /files");
    }
}