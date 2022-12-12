<?php
namespace Controller;
use Controller\AbstractController;
use Entity\File;
use Exception;
use Model\FileModel;
use View\Template;

class EditController extends AbstractController{

    public static function getAction($urlParts){
        if ($urlParts[1]=='newfile'){
            if(isset($_SESSION["username"])){
                if($_SERVER['REQUEST_METHOD']=="GET"){
                    return 'show';
                }
                if($_SERVER['REQUEST_METHOD']=="POST"){
                    return 'save';
                }
            }
            return 'notLoggedIn';
        }else if($urlParts[1]=='edit'){
            if(isset($_SESSION["username"])){
                if($_SERVER['REQUEST_METHOD']=="GET"){
                    return 'showEdit';
                }
                if($_SERVER['REQUEST_METHOD']=="POST"){
                    return 'saveEdit';
                }
            }
            return 'notLoggedIn';
        }
    }

    function doShow($url){
        $header = new Template('Base/header');

        $header = str_replace("{{template_auth}}","/logout",$header->render());
        $header = str_replace("{{template_auth_button}}","Kijelentkezés",$header);
        $view = new Template('Files/NewFile');        
        $footer = new Template('Base/footer');
        return $header.$view->render().$footer->render();
    }

    function doNotLoggedIn(){
        header("Location: /login");
    }

    function doSave()
    {
        $originalName = $_POST['name'];
        $content = $_POST['content'];
        $file = new File();
        $file->setName($originalName);
        $target_dir = PROJECT_ROOT.'/uploads/';
        $newName = md5(time() . $originalName . rand(0, 10000));
        $extension = "txt";
        $file->setPath($target_dir . $newName . '.' . $extension);
        $file->setUserId($_SESSION["user_id"]);
        $newFile = fopen($target_dir . $newName . '.' . $extension, "w");
        fwrite($newFile, $content);
        fclose($newFile);
        $model = new FileModel;
        $model->insertFile($file);
        header("Location: /files");
    }

    function doShowEdit($url){
        $id = $url[2];
        $model = new FileModel;
        $file = $model->getFileById($id);
        if(!$file){
            throw new Exception();
        }
        if($file->getUserId()!=$_SESSION['user_id']){
            throw new Exception();
        }
        $content = file_get_contents($file->getPath());

        $header = new Template('Base/header');

        $header = str_replace("{{template_auth}}","/logout",$header->render());
        $header = str_replace("{{template_auth_button}}","Kijelentkezés",$header);
        $view = new Template('Files/EditFile');      
        
        $view = str_replace("{{template_id}}",$file->getId(),$view->render());
        $view = str_replace("{{template_name}}",$file->getName(),$view);
        $view = str_replace("{{template_content}}",$content,$view);
        
        $footer = new Template('Base/footer');
        return $header.$view.$footer->render();
    }

    function doSaveEdit()
    {
        $id = $_POST['id'];
        $newName = $_POST['name'];
        $content = $_POST['content'];
        $model = new FileModel;
        $file = $model->getFileById($id);
        if(!$file){
            throw new Exception();
        }
        if($file->getUserId()!=$_SESSION['user_id']){
            throw new Exception();
        }
        
        $newFile = fopen($file->getPath(), "w");
        fwrite($newFile, $content);
        fclose($newFile);
        $file->setName($newName);
        $model = new FileModel;
        if($model->updateFile($file)){
            header("Location: /files");
        };
    }
}