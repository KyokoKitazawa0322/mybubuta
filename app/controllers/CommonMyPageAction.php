<?php
namespace Controllers;

class CommonMyPageAction {

    
    public function checkLogoutRequest($cmd){
        
        if($cmd == "do_logout" ){
            unset($_SESSION['customer_id']);
        }
    }
    
    public function checkLogin(){
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }
    }
}
?>