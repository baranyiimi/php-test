<?php
use Controller\ErrorController;

session_start();
spl_autoload_register(function($className) {
  $file = __DIR__.'/'.strtr($className, ['\\'=>DIRECTORY_SEPARATOR]).'.php';
  include $file;
});


define('PROJECT_ROOT',__DIR__);
$url = $_SERVER['REQUEST_URI'];
$parts = parse_url($url);
$query = [];
if(isset($parts['query'])){
  parse_str($parts['query'], $query);
}
$url = strtok($url, '?');
$url = explode('/',$url);


$controllers = [
  'Controller\\LoginController',
  'Controller\\SignUpController',
  'Controller\\FilesController',
  'Controller\\EditController'
];

foreach ($controllers as $controller){
  $action = $controller::getAction($url);

  if($action !== null){
    $controller = new $controller();
    $functionName = 'do' . ucfirst($action);
    echo $controller->$functionName($url,$query);

    break;
  }
}

if (!isset($functionName)){
  $controller = new ErrorController();
  echo $controller->doNotFound($url);
}