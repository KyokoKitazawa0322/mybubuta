<?php
namespace Controllers;

class MyPageAction {
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        if($cmd == "do_logout" ){
            unset($_SESSION['customer_id']);
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }   
    }
}
?>