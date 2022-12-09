<?php
namespace Controller;
use Controller\AbstractController;
use Entity\User;
use Model\UserModel;
use View\Template;

class SignUpController extends AbstractController{

    public static function getAction($urlParts){
        if ($urlParts[1]=='signup'){
            if($_SERVER['REQUEST_METHOD']=="GET"){
                return 'signup';
            }
            if($_SERVER['REQUEST_METHOD']=="POST"){
                return 'saveUser';
            }
        }
    }

    function doSignup($url){
        $header = new Template('Base/header');
        $header = str_replace("{{template_auth}}","/login",$header->render());
        $header = str_replace("{{template_auth_button}}","Bejelentkezés",$header);
        $view = new Template('Auth/SignUp');
        $footer = new Template('Base/footer');
        return $header.$view->render().$footer->render();

    }

    function doSaveUser(){
        $model = new UserModel;
        $newUser = new User();
        $password = $_POST["password"];
        if($password!= $_POST["password2"]){
            return "hiba";
        }
        if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{8,}$/', $password)) {
            return 'the password does not meet the requirements!';
        }
        $newUser->setEmail($_POST["email"]);
        $newUser->setUserName($_POST["username"]);
        $newUser->setPassword(password_hash($_POST["password"], PASSWORD_DEFAULT));

        $result = $model->save($newUser);
        if($result==false){
            return "Hiba";
        }
        header("Location: /login");

    }
}