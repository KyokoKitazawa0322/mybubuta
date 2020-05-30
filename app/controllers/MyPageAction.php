<?php
namespace Controllers;
class MyPageAction {
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }   
        
    }
}
?>