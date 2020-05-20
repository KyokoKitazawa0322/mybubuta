<?php
namespace Controllers;
use \Models\CustomerDao;

class MyPageLeaveAction {
    public function execute(){
        
        if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        //削除ボタンがおされたときの処理
        if(isset($_POST['cmd'])&&$_POST['cmd']=="leave"){
            $customerDao = new CustomerDao();
            $customerDto = $customerDao->getCustomerById($customerId);
            if($customerDto){ 
                if(!password_verify($_POST['memPwd'], $customerDto->getHashPassword())){
                    echo 'パスワードが正しくありません。';
                }else{
                    $customerDao->deleteCustomerInfo($customerId);
                    unset($_SESSION['customer_id']);
                    unset($_COOKIE['password']);
                    unset($_COOKIE['mail']);
                    header('Location:/html/mypage/leave_complete.php');
                    exit();
                }
            }
        }
    }
}
?>