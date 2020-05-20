<?php
namespace Controllers;

class OrderPayListAction{

    public function execute(){
        /**--------------------------------------------------------
         * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
         ---------------------------------------------------------*/
         if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
        }
    }
}

?>    

   