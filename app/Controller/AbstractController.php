<?php

namespace Controller;

abstract class AbstractController{

    /**
     * @param array $urlParts
     * 
     * @return null|string
     */
    public static function getAction($urlParts){
        return null;
    }
}