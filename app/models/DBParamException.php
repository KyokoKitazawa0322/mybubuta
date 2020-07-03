<?php
namespace Models;

class DBParamException extends \Models\OriginalException{
    
    public function __construct($message){
        
        parent::__construct($message, 400);
        $this->setUserMessage("不正な値が検出されました");
    }
}
?>
