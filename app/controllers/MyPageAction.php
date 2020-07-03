<?php
namespace Controllers;

use \Models\NoticeDao;
use \Models\NoticeDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

class MyPageAction extends \Controllers\CommonMyPageAction{
    
    private $noticeDto; 
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        
        $noticeDao = new NoticeDao();
        
        try{
            $noticeDto = $noticeDao->getLatestNoticeInfo();
            $this->noticeDto = $noticeDto;
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(NoRecordException $e){
            $e->handler($e);
        }
    }
    
    /*---------------------------------------*/
    /*
    *@return NoticeDto;
    */
    public function getNoticeDto(){
        return $this->noticeDto;   
    }
}
?>