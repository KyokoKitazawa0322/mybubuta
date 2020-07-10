<?php
namespace Controllers;

use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageAction extends \Controllers\CommonMyPageAction{
    
    private $noticeDto; 
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $noticeDao = new NoticeDao($pdo);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }
        
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