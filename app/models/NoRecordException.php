<?php
namespace Models;

class NoRecordException extends \Models\OriginalException{
    
    public function __construct($message){
        
        parent::__construct($message, 500);
        $this->setUserMessage("データベースに異常が発生しました。");
    }
}
?>
