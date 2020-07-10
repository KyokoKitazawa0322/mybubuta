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
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
          
        if($_SESSION['notice_id']){
           $noticeId = $_SESSION['notice_id']; 
        }
        $noticeId = filter_input(INPUT_POST, 'notice_id');   
        
        /*====================================================================
          admin_notice.phpで「詳細」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "notice_detail"){ 
            if($noticeId){
                $_SESSION['notice_id'] = $noticeId;   
            }
        }
            
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