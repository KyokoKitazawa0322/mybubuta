<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\ItemsDao;
use \Models\ItemsDto;

use \Models\CsrfValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;

class OrderCompleteAction{

    public function execute(){

        if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
         }

        $cmd = filter_input(INPUT_POST, 'cmd');
        
        /*====================================================================
        　register_confirm.phpで「注文を確定する」ボタンが押された時の処理
        =====================================================================*/
        $token = filter_input(INPUT_POST, "token_order_complete");
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

        /*- 商品のitem_statusが1(販売中)か最終確認 -*/
        try{
            foreach($_SESSION['cart'] as $item){
                $itemCode = $item['item_code'];
                $itemsDao = new ItemsDao();
                $itemsDao->getItemByItemCodeForPurchase($itemCode);
            }
        } catch(MyPDOException $e){
            $e->handler($e);

        } catch(DBParamException $e){
            $_SESSION['purchase_error'] = $itemCode;
            header("Location:/html/order/order_confirm.php");   
            exit();
        }

        try{
            $orderHistoryDao = new OrderHistoryDao();

            $orderHistoryDao->insertOrderHistory($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel);

            /*- INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録 -*/
            $orderDetailDao = new OrderDetailDao();

            $orderHistoryDto = $orderHistoryDao->getOrderId($customerId);
            $orderId = $orderHistoryDto->getOrderId();

            $itemsDao = new ItemsDao();
            $cart = $_SESSION['cart'];        

            foreach($cart as $item){
                $itemCode = $item['item_code'];
                $itemQuantity = $item['item_quantity'];
                $itemPrice = $item['item_price'];
                $itemTax = $item['item_tax'];
                $orderDetailDao->insertOrderDetail($orderId, $itemCode, $itemQuantity, $itemPrice, $itemTax);
                $itemsDao->recordItemSales($itemQuantity, $itemCode);
            }

            unset($_SESSION['cart']);
            unset($_SESSION['order']);
            unset($_SESSION['delivery']);
            unset($_SESSION['def_addr']);
            unset($_SESSION['pay_type']);
            unset($_SESSION['pay_error']);
            unset($_SESSION['payment_term']);
            unset($_SESSION['cmd']);
            unset($_SESSION['availableForPurchase']);

        } catch(MyPDOException $e){
            $e->handler($e);

        } catch(DBParamException $e){
            $e->handler($e);   
        }
    }   
}
?>    

   