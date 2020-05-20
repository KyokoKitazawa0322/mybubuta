<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;

class OrderCompleteAction{

    public function execute(){

        /**--------------------------------------------------------
         * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
         ---------------------------------------------------------*/
         if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
         }

        //リロード防止
        if (isset($_POST['reload']) && $_SESSION['reload'] == $_POST['reload']) {
            //一致するならセッションデータ削除
            $_SESSION['reload'] = "";    
            //以下、一致したとき（初回訪問）の処理
            /**--------------------------------------------------------
             *　order_comfirm.phpで注文確定ボタンがおされたときの処理
             ---------------------------------------------------------*/
            if(isset($_POST['cmd']) && $_POST['cmd'] == "order_comp"){
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
                
                $cart = $_SESSION['cart'];
                
                try{
                    $orderHistoryDao = new OrderHistoryDao();
                    $orderDetailDao = new OrderDetailDao();
                    
                    $orderHistoryDao->insertOrderHistory($customerId, $totalPayment, $totalAmount, $tax, $postage, $payment, $name, $address, $post, $tel);

                    //INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録
         
                    $res = $orderHistoryDao->getOrderId($customerId);
                    $orderId = $res['order_id'];
                    $cart = $_SESSION['cart'];
                    
                    foreach($cart as $item){
                        $itemCode = $item['item_code'];
                        $itemCount = $item['item_count'];
                        //new
                        $itemPrice = $item['item_price'];
                        $itemTax = $item['item_tax'];
                        $orderDetailDao->insertOrderDetail($orderId, $itemCode, $itemCount, $itemPrice, $itemTax);
                    }
                    
                    $_SESSION['cart'] = NULL;
                    $_SESSION['order'] = NULL;
                    $_SESSION['delivery'] = NULL;
                    $_SESSION['def_addr'] = NULL;
                    $_SESSION['isPay'] = NULL;
                    $_SESSION['payType'] = NULL;
                    
                }catch(\PDOException $e){
                    die('SQLエラー :'.$e->getMessage());
                }
            }else{
                header('Location:/html/login.php');
                exit();  
            }
        }else{
            header('Location:/html/login.php');
            exit();
        }
        
    }
}
?>    

   