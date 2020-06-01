<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\OriginalException;
use \Config\Config;

class OrderCompleteAction{

    public function execute(){

        if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
         }

        $cmd = filter_input(INPUT_POST, 'cmd');
       
        //リロード防止
        if($_SESSION['cmd'] == $cmd) {
            //一致するならセッションデータ削除
            $_SESSION['cmd'] = "";    
            //以下、一致したとき（初回訪問）の処理
            
            if(!isset($_SESSION['order']['payment'])){
                header('Location:/html/order/order_confirm.php');
                $_SESSION['isPay'] = "none";
                exit();
            }


            $customerId = $_SESSION['customer_id'];
            $totalPayment = $_SESSION['order']['total_payment'];
            $totalAmount = $_SESSION['order']['total_amount'];
            $tax = $_SESSION['order']['tax'];
            $postage = $_SESSION['order']['postage'];
            $payment = $_SESSION['order']['payment'];
            $name = $_SESSION['delivery']['name'];
            $address = $_SESSION['delivery']['address'];
            $post = $_SESSION['delivery']['post'];
            $tel = $_SESSION['delivery']['tel'];


            $orderHistoryDao = new OrderHistoryDao();
            $orderDetailDao = new OrderDetailDao();

            try{
                $orderHistoryDao->insertOrderHistory($customerId, $totalPayment, $totalAmount, $tax, $postage, $payment, $name, $address, $post, $tel);

                //INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録
                $orderHistoryDto = $orderHistoryDao->getOrderId($customerId);
                $orderId = $orderHistoryDto->getOrderId();
                $cart = $_SESSION['cart'];

                foreach($cart as $item){
                    $itemCode = $item['item_code'];
                    $itemCount = $item['item_count'];
                    //new
                    $itemPrice = $item['item_price'];
                    $itemTax = $item['tax'];
                    $orderDetailDao->insertOrderDetail($orderId, $itemCode, $itemCount, $itemPrice, $itemTax);
                }

                $_SESSION['cart'] = NULL;
                $_SESSION['order'] = NULL;
                $_SESSION['delivery'] = NULL;
                $_SESSION['def_addr'] = NULL;
                $_SESSION['isPay'] = NULL;
                $_SESSION['payType'] = NULL;

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

   