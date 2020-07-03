<?php
namespace Controllers;
use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
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
          
        $noticeId = filter_input(INPUT_POST, 'notice_id');    
        if($noticeId){
            $_SESSION['notice_id'] = $noticeId;   
        }
        if($_SESSION['notice_id']){
           $noticeId = $_SESSION['notice_id']; 
        }
        $noticeDao = new NoticeDao();
        
        /*====================================================================
          admin_notice.phpで「詳細」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "notice_detail" || $noticeId){
            try{
                $noticeDto = $noticeDao->getNoticeDetail($noticeId);
                $this->noticeDto = $noticeDto;

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