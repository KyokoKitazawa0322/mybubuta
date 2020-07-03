<?php
namespace Controllers;
use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;

class MyPageNoticeAction extends \Controllers\CommonMyPageAction{
    
    private $noticeDto; 
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        $noticeDao = new NoticeDao();
        
        try{
            $noticeDto = $noticeDao->getNoticeInfoAll();
            $this->noticeDto = $noticeDto;
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(NoRecordException $e){
            $e->handler($e);
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