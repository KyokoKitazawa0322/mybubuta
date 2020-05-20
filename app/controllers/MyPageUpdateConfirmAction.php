<?php
namespace Controllers;

class MyPageUpdateConfirmAction {

    
    public function execute(){
        
        if(isset($_POST['cmd']) && $_POST['cmd'] == 'do_logout' ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        if(!isset($_SESSION['update']['input'])){
            header('Location:/html/login.php');       
        }
    }
}
?>