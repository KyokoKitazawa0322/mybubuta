<?php
namespace Models;

class InvalidParamException extends \Models\OriginalException{
    
    public function __construct($message){
        
        /*- クライアントの操作による誤ったアクセスに対しては、HTTPレスポンスステータスコード400をセット。 -*/
        parent::__construct($message, 400);
        $this->setUserMessage("不正な操作です。");
    }
}
?>
