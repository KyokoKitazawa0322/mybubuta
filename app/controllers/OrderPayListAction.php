<?php
namespace Controllers;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Config\Config;

class OrderPayListAction{

    public function execute(){
        
        /**--------------------------------------------------------
         * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
         ---------------------------------------------------------*/
         if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
        }
        
        try{
            if(!isset($_SESSION['availableForPurchase'])){
                throw new InvalidParamException('Invalid param for order_confirm_pay_list:$_SESSION["availableForPurchase"]=nothing');
            }
        } catch(InvalidParamException $e){
            $e->handler($e);
        }
    }
}

?>    

   