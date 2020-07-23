<?php
namespace Models;

class DBParamException extends \Models\OriginalException{
    
    public function __construct($message){
        
        parent::__construct($message, 400);
        $this->setUserMessage("不正な操作です。");
    }
}
?>
