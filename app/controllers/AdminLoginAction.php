<?php
namespace Controllers;

use \Models\AdminDao;
use \Models\AdminDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class AdminLoginAction{
    
    public function execute(){
        
        /*====================================================================a
      　  $_SESSION['admin_id']があればadmin_index.phpへリダイレクト
        =====================================================================*/
        if(isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_index.php");
            exit();
        }
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        /*====================================================================
        　ログイン認証
        =====================================================================*/
        
        if($cmd == "admin_do_login"){
            $_SESSION['admin_login_error'] = null;
            $adminId = filter_input(INPUT_POST, 'admin_id');
            $adminPassword = filter_input(INPUT_POST, 'admin_password');
            
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $admindao = new AdminDao($pdo);
                $admin = $admindao->adminLogin($adminId, $adminPassword);
                
            }catch(DBConnectionException $e){
                $e->handler($e);   
                
            } catch(MyPDOException $e){
                $e->handler($e);
            }
            
            if(!$admin){ 
                /*——————————————————————————————————————————————————————————————
                　ログイン失敗した際の処理
                 ————————————————————————————————————————————————————————————————*/  
                $_SESSION['admin_login_error'] = 'login_error';
            }else{
                /*——————————————————————————————————————————————————————————————
                 ログイン成功した際の処理
                ————————————————————————————————————————————————————————————————*/  
                session_regenerate_id(true);
                unset($_SESSION['admin_login_error']);
                $_SESSION['admin_id'] = $admin->getAdminId();
                setcookie('admin_id','',time()-3600,'/');
                setcookie('admin_password','',time()-3600,'/');
                setcookie('admin_password','',time()-3600,'/');
                setcookie('admin_id',$admin->getAdminId(),time()+60*60*24*7);
                setcookie('admin_password',$admin->getAdminPassword(),time()+60*60*24*7);
                
                header("Location:/html/admin/admin_index.php");
                exit();
            }
        }
    }
    
    public function echoValue($value){
        if(isset($_POST[$value])){
            echo $_POST[$value];
        }elseif(isset($_COOKIE[$value])){
            echo $_COOKIE[$value];
        }
    }
    
    public function checkLoginError(){
        if(isset($_SESSION['admin_login_error']) && $_SESSION['admin_login_error'] == 'login_error'){
            return true;
        }
    }
}
?>