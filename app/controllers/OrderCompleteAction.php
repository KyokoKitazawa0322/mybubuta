<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\Model;

use \Models\CsrfValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class OrderCompleteAction  extends \Controllers\CommonMyPageAction{

    public function execute(){

        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin(); 
        
        /*====================================================================
        　register_confirm.phpで「注文を確定する」ボタンが押された時の処理
        =====================================================================*/
        
        $token = Config::getPOST( "token_order_complete");
        $formName = "token_order_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
            
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }
        
        /*——————————————————————————————————————————————————————————————
         決済方法が選択されてなければリダイレクト
        ————————————————————————————————————————————————————————————————*/

        if(!isset($_SESSION['payment_term'])){
            header('Location:/html/order/order_confirm.php');
            $_SESSION['pay_type'] = "none";
            exit();
        }
        
        /*——————————————————————————————————————————————————————————————*/

        $customerId = $_SESSION['customer_id'];
        $totalAmount = $_SESSION['order']['total_amount'];
        $totalQuantity = $_SESSION['order']['total_quantity'];
        $tax = $_SESSION['order']['tax'];
        $postage = $_SESSION['order']['postage'];
        $paymentTerm = $_SESSION['payment_term'];
        $name = $_SESSION['delivery']['name'];
        $address = $_SESSION['delivery']['address'];
        $post = $_SESSION['delivery']['post'];
        $tel = $_SESSION['delivery']['tel'];

        /*- 商品のitem_status(=1：販売中ならOK)とitem_stock(購入数量が在庫より少なければOK)を最終確認 -*/
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            
            $this->checkItemInfo($pdo);
        
            if(isset($_SESSION['purchase_error']) && $_SESSION['purchase_error']){
                header("Location:/html/order/order_confirm.php");   
                exit();
            }
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
        } catch(MyPDOException $e){
            $e->handler($e);

        } catch(DBParamException $e){
            $e->handler($e);
        }

        try{
            $model->beginTransaction();
            
        } catch(MyPDOException $e){
            $e->handler($e);
        }
                
        try{
            $this->insertOrderInfo($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel, $pdo);
            
            $model->commit();

        }catch(MyPDOException $e){
            if($pdo->inTransaction()){$pdo->rollback();}
            $e->handler($e);

        }catch(DBParamException $e){
            if($pdo->inTransaction()){$pdo->rollback();}
            $e->handler($e);   
        } 
            unset($_SESSION['cart']);
            unset($_SESSION['order']);
            unset($_SESSION['delivery']);
            unset($_SESSION['def_addr']);
            unset($_SESSION['pay_type']);
            unset($_SESSION['pay_error']);
            unset($_SESSION['payment_term']);
            unset($_SESSION['availableForPurchase']);
            unset($_SESSION['purchase_error']);
    }   
    
    /*---------------------------------------*/
    
    public function checkItemInfo($pdo){
    
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
    
    public function insertOrderInfo($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel, $pdo){
        
        $orderHistoryDao = new OrderHistoryDao($pdo);
        $orderHistoryDao->insertOrderHistory($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel);

        /*- INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録 -*/
        $orderDetailDao = new OrderDetailDao($pdo);

        $orderHistoryDto = $orderHistoryDao->getOrderId($customerId);
        $orderId = $orderHistoryDto->getOrderId();

        $itemsDao = new ItemsDao($pdo);
        $cart = $_SESSION['cart'];        

        foreach($cart as $item){
            $itemCode = $item['item_code'];
            $itemQuantity = $item['item_quantity'];
            $itemPrice = $item['item_price'];
            $itemTax = $item['item_tax'];
            
            $orderDetailDao->insertOrderDetail($orderId, $itemCode, $itemQuantity, $itemPrice, $itemTax);
            $itemsDao->recordItemSales($itemQuantity, $itemCode);
        }
    }
}
?>    

   