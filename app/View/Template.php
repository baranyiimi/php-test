<?php

namespace View;

use Exception;

class Template {

    private string $file;

    private array $data=[];

    public function __construct($file) {
        $this->file = $file;
    }
    
    public function render(){
        $fullFile = PROJECT_ROOT .'/Templates/'.$this->file.'.html';
        if(!file_exists($fullFile)){
            throw new Exception('Template error');
        }

        $content = file_get_contents($fullFile);
        
        return $content;
    }
}