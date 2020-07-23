<?php
namespace Models;
use \Config\Config;

class OriginalException extends \Exception {
    
    /* @param string $userMessage */
    protected $userMessage;
    protected $logMessage;
    /**
     * @param string $message
     * @param int $code
     */
    
    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
        
        date_default_timezone_set('Asia/Tokyo');
        $datetime = date( "Y/m/d H:i:s");
        
        $message = "[ExceptionClass]=>".get_class($this)."\r\n[time]=>{$datetime}\r\n[ExceptionCode]=>{$this->code}\r\n[Message]=>{$this->message}\r\n[File/Line]=>{$this->file}/{$this->line}\r\n[Trace]=>{$this->getTraceAsString()}";
        
        $this->logMessage = $message;
    }
    
    public function setUserMessage($userMessage) {
        $this->userMessage = $userMessage;
    }
    
    public function getUserMessage() {
        return $this->userMessage;
    }
    
    /**
     *　ログ出力、ヘッダー送出、$userMessageの画面表示
     * @param Exception $e
     */
    public function handler(\Exception $e){
        
        error_log($this->logMessage);
        
        /*- デェフォルトで例外がなげられるMyPDOExceptionとMyS3Exceptionは
            HTTPレスポンスステータスコード500をセット
            (コンストラクのCodeはそのまま受け取り出力するため)-*/
        if($e instanceof \Models\MyPDOException　|| $e instanceof \Models\MyS3Exception || $e instanceof \Models\DBConnectionException) {
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
        
        error_log($this->logMessage);
        
        if($e instanceof \Models\MyPDOException　|| $e instanceof \Models\MyS3Exception) {
            header('HTTP', true, 500);
        }else{
            header('HTTP', true, $e->getCode());
        }
    }
}

?>
