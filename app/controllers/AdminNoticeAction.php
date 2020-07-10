<?php
namespace Controllers;
use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\Model;

use \Models\CsrfValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class AdminNoticeAction{
    
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
        $content = filter_input(INPUT_POST, 'content');
        
       try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $noticeDao = new NoticeDao($pdo);
        
       }catch(DBConnectionException $e){
            $e->handler($e);   
        }
        
        switch($cmd){
            case "delete":
            
                $token = filter_input(INPUT_POST, "token");
                try{
                    CsrfValidator::validate($token);
                }catch(InvalidParamException $e){
                    $e->handler($e);   

                }
                try{
                    $noticeDao->deleteNoticeInfo($noticeId);
                } catch(MyPDOException $e){
                    $e->handler($e);

                }catch(DBParamException $e){
                    $e->handler($e);
                }
                break;
        
            case "sort":
                try{
                    switch($content) {
                        case "sortby_id_desc":
                            $noticeDto = $noticeDao->getNoticeAllSortByIdDesc();
                            $this->noticeDto = $noticeDto;      
                            break;
                        case "sortby_id_asc":
                            $noticeDto = $noticeDao->getNoticeAllSortByIdAsc();
                            $this->noticeDto = $noticeDto;      
                            break;
                        default:
                    }
                } catch(MyPDOException $e){
                    $e->handler($e);

                }catch(NoRecordException $e){
                    $e->handler($e);
                }
                break;
        
            default:
                try{
                    $noticeDto = $noticeDao->getNoticeInfoAll();
                    $this->noticeDto = $noticeDto;

                } catch(MyPDOException $e){
                    $e->handler($e);

                }catch(NoRecordException $e){
                    $e->handler($e);
                }
                break;
        }
    }
    
    public function getNotices(){
        return $this->noticeDto;   
    }
    
}
?>