<?php
namespace Controllers;
use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\OriginalException;
use \Config\Config;

class MyPageNoticeAction {
    
    private $noticeDto; 
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        if($cmd == "do_logout" ){
            unset($_SESSION['customer_id']);
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }
        
        $noticeDao = new NoticeDao();
        
        try{
            $noticeDto = $noticeDao->getNoticeInfoAll();
            $this->noticeDto = $noticeDto;
            
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');

        }catch(OriginalException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
            header('Content-Type: text/plain; charset=UTF-8', true, 400);
            die('エラー:'.$e->getMessage());
        }
    }
    
    /*
    *@return NoticeDto;
    */
    public function getNoticeDto(){
        return $this->noticeDto;   
    }
}
?>