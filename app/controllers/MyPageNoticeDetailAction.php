<?php
namespace Controllers;

use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;

class MyPageNoticeDetailAction extends \Controllers\CommonMyPageAction{
    
    private $noticeDto; 
        
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        $noticeId = filter_input(INPUT_POST, 'notice_id');

        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        $noticeDao = new NoticeDao();

        try{
            $noticeDto = $noticeDao->getNoticeDetail($noticeId);
            $this->noticeDto = $noticeDto;
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
    }
    
    /*
    *@return NoticeDto[];
    */
    public function getNoticeDto(){
        return $this->noticeDto;   
    }
}
?>