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

class MyPageNoticeDetailAction extends \Controllers\CommonMyPageAction{
    
    private $noticeDto; 
        
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        $noticeId = filter_input(INPUT_POST, 'notice_id');

        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $noticeDao = new NoticeDao($pdo);
            $noticeDto = $noticeDao->getNoticeDetail($noticeId);
            $this->noticeDto = $noticeDto;
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
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