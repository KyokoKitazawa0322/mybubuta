<?php
namespace Controllers;
use \Models\NoticeDao;
use \Models\NoticeDto;

use \Models\CsrfValidator;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
class AdminNoticeRegisterAction{
    
    private $item;
    
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
        
        if($cmd == "admin_notice_register"){
            
            $token = filter_input(INPUT_POST, "token");
            try{
                CsrfValidator::validate($token);
            }catch(InvalidParamException $e){
                $e->handler($e);   
            }
            
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING); 
            $mainText = filter_input(INPUT_POST, 'main_text', FILTER_SANITIZE_STRING); 
                
            try{
                $noticeDao = new NoticeDao();
                $noticeDto = $noticeDao->insertNoticeInfo($title, $mainText);

            } catch(MyPDOException $e){
                $e->handler($e);
            }
        }
    }
    
    public function getItem(){
        return $this->noticeDto;   
    }
}
?>