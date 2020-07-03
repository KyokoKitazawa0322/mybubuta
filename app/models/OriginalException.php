<?php
namespace Models;
use \Config\Config;

class OriginalException extends \Exception {
    
    /* @param string $userMessage */
    protected $userMessage;

    /**
     * @param string $message
     * @param int $code
     */
    
    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
    
    /**
     *　ログ出力、ヘッダー送出、$userMessageの画面表示
     * @param Exception $e
     */
    public function handler(\Exception $e){
        
        Config::outputLog($e);
        
        /*- デェフォルトで例外がなげられるMyPDOExceptionとMyS3Exceptionは
            HTTPレスポンスステータスコード500をセット
            (コンストラクのCodeはそのまま受け取り出力するため)-*/
        if($e instanceof \Models\MyPDOException　|| $e instanceof \Models\MyS3Exception) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die($e->getUserMessage());
        }else{
            /*- 独自に投げる例外はクラスで設定した例外コードを
            HTTPレスポンスステータスコードとして埋める-*/
            header('Content-Type: text/plain; charset=UTF-8', true, $e->getCode());
            die($e->getUserMessage());
        }
    }
    
    /**
     * ログ出力とヘッダーの送出のみ
     * @param Exception $e
     */
    public function handler_light(\Exception $e){
        
        Config::outputLog($e);
        
        if($e instanceof \Models\MyPDOException　|| $e instanceof \Models\MyS3Exception) {
            header('HTTP', true, 500);
        }else{
            header('HTTP', true, $e->getCode());
        }
    }
    
    public function setUserMessage($userMessage) {
        $this->userMessage = $userMessage;
    }
    
    public function getUserMessage() {
        return $this->userMessage;
    }
}

?>
