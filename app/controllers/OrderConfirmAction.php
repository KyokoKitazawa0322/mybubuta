<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class OrderConfirmAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){        

        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        /*====================================================================
         cart.phpで「レジに進む」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "order_confirm"){
            
            try{
                $this->checkValidationResult();

            }catch(InvalidParamException $e){
                $e->handler($e);   
            }
            
            $var = 1;
            $totalPrice = 0; 
            $totalAmount = 0;
            $totalQuantity = 0;
            $tax = 0;

            /*- カート情報及びcart.phpで選択した商品の点数をとりだし、セッションに格納 -*/
            for($i = 0 ; $i<count($_SESSION["cart"]); $i++ ){

                    
                $itemQuantity = Config::getPOST("cart{$var}");
                $_SESSION["cart"][$i]['item_quantity'] = $itemQuantity;

                /*- $_SESSION['order']に格納する値を計算-*/
                $totalQuantity += $_SESSION["cart"][$i]['item_quantity']; 
                $totalAmount += $_SESSION["cart"][$i]['item_price_with_tax'] * $_SESSION["cart"][$i]['item_quantity'];
                $tax += $_SESSION["cart"][$i]['item_tax'] * $_SESSION["cart"][$i]['item_quantity'];

                $var++;
            }
            if($totalAmount >= Config::POSTAGEFREEPRICE){
                $postage = 0;
            }else{
                $postage = Config::POSTAGE;
            }

            /*- 注文処理で使用するセッション変数の初期化 -*/
            $_SESSION['pay_error'] = NULL;
            $_SESSION['pay_type'] = NULL;
            $_SESSION['payment_term'] = NULL;
            $_SESSION['def_addr'] = NULL;
            $_SESSION['delivery'] = NULL;

            $_SESSION['order'] = array(
                'total_quantity' => $totalQuantity,  
                'total_amount' => $totalAmount,
                'tax' => $tax,
                'postage' => $postage
            );
        }
        
        /*- $_SESSION['order']の値がない訪問は例外発生 -*/
        try{
            $this->checkOrder();
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }
        
        /*——————————————————————————————————————————————————————————————
        　非ログイン状態の場合の処理
        ————————————————————————————————————————————————————————————————*/

        if(!isset($_SESSION['customer_id'])){
            $_SESSION['track_for_login']['from'] = "order";
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        /*——————————————————————————————————————————————————————————————
            以下$_SESSION['order']/$_SESSION['customer_id']がある時の共通処理
        ————————————————————————————————————————————————————————————————*/
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $customerDao = new CustomerDao($pdo);
            $deliveryDao = new DeliveryDao($pdo);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }

        try{
            /*——————————————————————————————————————————————————————————————
             「配送先確定」ボタンがおされたときの処理
            ————————————————————————————————————————————————————————————————*/
            if($cmd == "del_comp"){

                $def_addr = Config::getPOST('def_addr');
                $_SESSION['def_addr'] = $def_addr;

                if($def_addr != "customer") {
                    $delivery  = $deliveryDao->getDeliveryInfoById($customerId, $def_addr);
                    $this->saveDeliveryData($delivery);
                }else{
                    $customer = $customerDao->getCustomerById($customerId); 
                    $this->saveCustomerData($customer);
                }
            }else{
            /*——————————————————————————————————————————————————————————————
             「配送先確定」ボタンがおされたとき以外の共通処理
            ————————————————————————————————————————————————————————————————*/
                $customer = $customerDao->getCustomerById($customerId);
                if(!$customer->getDeliveryFlag()){    
                    /*- customerテーブルの住所が配送先のデフォルトでなければ
                    deliveryテーブルからデフォルト設定された住所を取得、セッションに格納 -*/
                    $delivery = $deliveryDao->getDefDeliveryInfo($customerId);
                    $this->saveDeliveryData($delivery);
                }else{
                    $this->saveCustomerData($customer);
                }

            }
        }catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
        
        /*——————————————————————————————————————————————————————————————
         「決済方法確定」ボタンがおされたときの処理
        ————————————————————————————————————————————————————————————————*/
        if($cmd == "pay_comp") {
            $payType = Config::getPOST('pay_type');
            $_SESSION['pay_error'] = NULL;
            $_SESSION['payment_term'] = NULL;

            if(!$payType){   
                $_SESSION['pay_error']="is";
                header('Location:/html/order/order_pay_list.php');
                exit();   
            }else{
                $_SESSION['pay_type'] = $payType;

                if($payType == "1") {
                    $_SESSION['payment_term'] = "クレジットカード";
                }elseif($payType == "2") {
                    $_SESSION['payment_term'] = "代引き";
                }else{
                    $_SESSION['payment_term'] = "銀行振込";
                } 
            }
        }
        
        /*——————————————————————————————————————————————————————————————
            共通処理
        ————————————————————————————————————————————————————————————————*/
       try{
           $this->checkItemInfo($pdo);

       } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
    }
    
    /*---------------------------------------*/
    public function checkValidationResult(){
        
        if(!isset($_SESSION['cart']) || !isset($_SESSION['availableForPurchase'])){
            if(isset($_SESSION['cart'])){
                $cart = print_r($_SESSION['cart'], true);
            }else{
                $cart = "nothing";   
            }
            if(isset($_SESSION['availableForPurchase'])){
                $availableForPurchase = "TRUE";
            }else{
                $availableForPurchase = "nothing";   
            }
            throw new InvalidParamException('Invalid param for order_confirm:$cart='.$cart.'/$_SESSION["availableForPurchase"]='.$availableForPurchase);
        }
    }
    
    public function checkOrder(){
        if(!isset($_SESSION['order'])){
            throw new InvalidParamException('Invalid param for order_confirm:$_SESSION["order"]="nothing"');
        }
    }
    
    /*- DTOクラスで用意したgetterメソッド名は同じだが、念のためにcustomerテーブルとdeliveryテーブルとで分けて下記メソッドを用意。 -*/
    public function saveDeliveryData($delivery){
        $_SESSION['delivery'] = array(
            'name' => $delivery->getFullName(),
            'post' => $delivery->getPost(),
            'address' => $delivery->getAddress(),
            'tel' => $delivery->getTel()
        );
    }
    
    public function saveCustomerData($customer){
        $_SESSION['delivery'] = array(
            'name' => $customer->getFullName(),
            'post' => $customer->getPost(),
            'address' => $customer->getAddress(),
            'tel' => $customer->getTel()
        );
    }
    
    public function checkItemInfo($pdo){
        
        $_SESSION['purchase_error'] = false;
        
        $itemsDao = new ItemsDao($pdo);
        
        foreach($_SESSION['cart'] as &$item){

            $itemCode = $item['item_code'];
            $itemQuantity = $item['item_quantity'];

            $itemsDto = $itemsDao->getItemByItemCode($itemCode);
            $itemStatus = $itemsDto->getItemStatus();
            $itemStock = $itemsDto->getItemStock();
            $item['item_status'] = $itemStatus;
            $item['item_stock'] = $itemStock;
            
            if($itemStatus !== "1" || $itemStock<$itemQuantity){
                $_SESSION['purchase_error'] = TRUE;
            }
        }
    }
    
    public function checkValue($key, $value){
        if(isset($_SESSION[$key]) && $_SESSION[$key] == $value){
            return true;
        }
    }
    
    public function checkIssetPayType(){
        if(!isset($_SESSION['pay_type']) || $_SESSION['pay_type'] == "none"){
            return false;   
        }else{
            return true;   
        }
    }
    
    public function echoOrder($key){
        return  $_SESSION['order'][$key];   
    }
    
    public function echoDelivery($key){
        return  $_SESSION['delivery'][$key];   
    }
    
    public function calculateTotal(){
        return $_SESSION['order']['total_amount'] + $_SESSION['order']['postage'];
    }
    
    public function checkIssetPurchaseError(){
        if(isset($_SESSION['purchase_error']) && $_SESSION['purchase_error']){
            return true;   
        }else{
            return false;   
        }
    }
    
    public function alertStock($itemStock, $itemQuantity){
        if($itemStock < $itemQuantity){
            return true;   
        }else{
            return false;
        }   
    }
}

?>    

   