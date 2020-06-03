<?php
namespace Controllers;

class MyPageUpdateConfirmAction {

    
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
        
        if(!isset($_SESSION['update_data']) || $_SESSION['update_data'] != "clear"){
            header('Location:/html/mypage/mypage_update.php');
            exit();
        }
    }
}
?>