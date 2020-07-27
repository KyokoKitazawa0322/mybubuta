<?php
namespace Controllers;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Config\Config;

class OrderPayListAction extends \Controllers\CommonMyPageAction{

    public function execute(){
        
        /**--------------------------------------------------------
         * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
         ---------------------------------------------------------*/
        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin(); 
        
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

   