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
        $cmd = Config::getPOST("cmd");
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $noticeId = Config::getPOST('notice_id');
        $content = Config::getPOST('content');
        
       try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $noticeDao = new NoticeDao($pdo);
        
       }catch(DBConnectionException $e){
            $e->handler($e);   
       }
        
        if($cmd == "delete"){
            $token = Config::getPOST( "token");
            try{
                CsrfValidator::validate($token);
                $noticeDao->deleteNoticeInfo($noticeId);

            }catch(InvalidParamException $e){
                $e->handler($e);   

            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamException $e){
                $e->handler($e);
            }
        }
        
        if($cmd == "sort"){
            try{
                switch($content) {
                    case "sortby_id_desc":
                        $noticeDto = $noticeDao->getNoticeAllSortByIdDesc();
                        $this->noticeDto = $noticeDto;      
                        break;
                    case "sortby_id_asc":
                        $noticeDto = $noticeDao->getNoticeAllSortByIdAsc();
                        $this->noticeDto = $noticeDto;      
                    default:
                        throw new InvalidParamException("Invalid param for sort:".$content);
                }
            } catch(InvalidParamException $e){
                $e->handler($e);
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(NoRecordException $e){
                $e->handler($e);
            }
        }else{
            try{
                $noticeDto = $noticeDao->getNoticeInfoAll();
                $this->noticeDto = $noticeDto;

            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(NoRecordException $e){
                $e->handler($e);
            }
        }
    }
    
    public function getNotices(){
        return $this->noticeDto;   
    }
    
}
?>