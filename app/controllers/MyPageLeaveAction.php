<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\OriginalException;
use \Config\Config;

class MyPageLeaveAction {
    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
    
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        /*====================================================================
         削除ボタンがおされたときの処理
        =====================================================================*/
 
        if($cmd == "leave"){
            
            $memPwd = filter_input(INPUT_POST, 'memPwd');
            $customerDao = new CustomerDao();
            
            try{
                $customerDto = $customerDao->getCustomerById($customerId);
                $hashPassword = $customerDto->getHashPassword();
                
                if(!password_verify($memPwd, $hashPassword)){
                    echo 'パスワードが正しくありません。';
                }else{
                    $customerDao->deleteCustomerInfo($customerId);
                    unset($_SESSION['customer_id']);
                    unset($_COOKIE['password']);
                    unset($_COOKIE['mail']);
                    header('Location:/html/mypage/leave_complete.php');
                    exit();
                }

            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');

            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
        
        }
    }
}
?>