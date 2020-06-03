<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\OriginalException;
use \Config\Config;

class OrderCompleteAction{

    public function execute(){

        if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
         }

        $cmd = filter_input(INPUT_POST, 'cmd');
        
        /*====================================================================
        　register_confirm.phpで「登録する」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "order_complete" && isset($_SESSION['order'])){
            
            /*——————————————————————————————————————————————————————————————
             決済方法が選択されてなければリダイレクト
            ————————————————————————————————————————————————————————————————*/
    
            if(!isset($_SESSION['order']['payment_term'])){
                header('Location:/html/order/order_confirm.php');
                $_SESSION['isPay'] = "none";
                exit();
            }
            /*——————————————————————————————————————————————————————————————*/
            
            $customerId = $_SESSION['customer_id'];
            $totalAmount = $_SESSION['order']['total_amount'];
            $totalQuantity = $_SESSION['order']['total_quantity'];
            $tax = $_SESSION['order']['tax'];
            $postage = $_SESSION['order']['postage'];
            $paymentTerm = $_SESSION['order']['payment_term'];
            $name = $_SESSION['delivery']['name'];
            $address = $_SESSION['delivery']['address'];
            $post = $_SESSION['delivery']['post'];
            $tel = $_SESSION['delivery']['tel'];


            $orderHistoryDao = new OrderHistoryDao();
            $orderDetailDao = new OrderDetailDao();
            $itemsDao = new ItemsDao();
                
            try{
                $orderHistoryDao->insertOrderHistory($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel);

                /*- INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録 -*/
                $orderHistoryDto = $orderHistoryDao->getOrderId($customerId);
                $orderId = $orderHistoryDto->getOrderId();
                $cart = $_SESSION['cart'];

                foreach($cart as $item){
                    $itemCode = $item['item_code'];
                    $itemQuantity = $item['item_quantity'];
                    $itemPrice = $item['item_price'];
                    $itemTax = $item['item_tax'];
                    $orderDetailDao->insertOrderDetail($orderId, $itemCode, $itemQuantity, $itemPrice, $itemTax);
                    $itemsDao->insertItemSales($itemQuantity, $itemCode);
                }
                unset($_SESSION['cart']);
                unset($_SESSION['order']);
                unset($_SESSION['delivery']);
                unset($_SESSION['def_addr']);
                unset($_SESSION['isPay']);
                unset($_SESSION['payType']);
                unset($_SESSION['pay_error']);
                unset($_SESSION['cmd']);
    
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');

            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
        }else{
            header('Location:/html/login.php');
            exit();  
        }
    }   
}
?>    

   