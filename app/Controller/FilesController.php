<?php
namespace Controller;
use Controller\AbstractController;
use Entity\File;
use Model\FileModel;
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
        }
    }

    function doShow($url){
        $header = new Template('Base/header');

        $header = str_replace("{{template_auth}}","/logout",$header->render());
        $header = str_replace("{{template_auth_button}}","Kijelentkezés",$header);
        $model = new FileModel;
        $files = $model->getAll();
        $table = '';
        foreach($files as $file){
            $table .= '<tr>
                <th scope="row">' . $file->getId() . '</th>
                <td>' . $file->getName() . '</td>
                <td>' . $file->getCreated()->format('Y-m-d H:i:s') . '</td>
                <td>' . $file->getUpdated()->format('Y-m-d H:i:s') . '</td>
                <td>' . ($file->getSender()?$file->getSender():'Nincs küldő') . '</td>
                </tr>';
        }
        $view = new Template('Files/List');
        $view = str_replace("{{template_table}}",$table,$view->render());
        
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
        $model = new FileModel;
        $model->insertFile($file);
        move_uploaded_file($_FILES["filename"]["tmp_name"], $target_dir.$newName.'.'.$extension);
        // Check if image file is a actual image or fake image

    }
}