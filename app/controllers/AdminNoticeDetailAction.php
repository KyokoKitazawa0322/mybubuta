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
    
class AdminNoticeDetailAction{
    
    private $noticeDto;
        
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        $cmd = Config::getPOST("cmd");
        $getCmd = Config::getGET('cmd');
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }

        $noticeId = Config::getGET('notice_id');   
        if($noticeId){
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
                
        }else{
            header("Location:/html/admin/admin_login.php");
            exit();   
        }
    }
    
    public function getNotice(){
        return $this->noticeDto;   
    }
    
}
?>