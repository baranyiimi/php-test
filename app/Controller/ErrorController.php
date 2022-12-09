<?php

namespace Controller;
use Controller\AbstractController;
use View\Template;

class ErrorController extends AbstractController{

    public function doNotFound($url){
        $view = new Template('Error/404');
        return $view->render();
    }
}