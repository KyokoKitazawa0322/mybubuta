<?php
namespace Models;

class MyPDOException extends \Models\OriginalException{
    
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code){
        parent::__construct($message, $code);

        /*- ログ出力：PDOExceptionからなげられるMessage/codeを使用
            ユーザー表示画面：下記の文章使用 -*/
        $this->setUserMessage("データベースの処理に失敗しました");
    }    
}
?>
