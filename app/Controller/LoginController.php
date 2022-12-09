<?php
namespace Controller;
use Controller\AbstractController;
use Model\UserModel;
use View\Template;

class LoginController extends AbstractController{

    public static function getAction($urlParts){
        if ($urlParts[1]=='login'){
            if($_SERVER['REQUEST_METHOD']=="GET"){
                return 'show';
            }
            if($_SERVER['REQUEST_METHOD']=="POST"){
                return 'login';
            }
        }
        else if($urlParts[1] == 'logout'){
            return 'logout';
        }
    }




    function doShow($url){
        $header = new Template('Base/header');
        $header = str_replace("{{template_auth}}","/signup",$header->render());
        $header = str_replace("{{template_auth_button}}","Regisztráció",$header);
        $view = new Template('Auth/Login');
        $footer = new Template('Base/footer');
        return $header.$view->render().$footer->render();
    }

    function doLogin($url){
        $model = new UserModel;
        $username = $_POST["username"];
        $password = $_POST["password"];
        $user = $model->getUserByUserName($username);
        if (password_verify($password, $user->getPassword())) {
            $_SESSION["username"] = $user->getUserName();
            $_SESSION["user_id"] = $user->getId();
            header("Location: /files");
        }
        else {
            header("Location: /login");
        }
    }

    function doLogout(){
        session_destroy();
        header("Location: /login");
    }
}