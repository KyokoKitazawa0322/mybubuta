<?php
namespace Controllers;
use \Models\ItemsDto;
use \Models\ItemsDao;
use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;

class OrderConfirmAction{
        
    public $reload_off;
    
    public function execute(){        
        
        //cart.php以外からの訪問はリダイレクトでcart.phpへ
        if(!isset($_SESSION["cart"])){
            header('Location:/html/cart.php');
            exit;
        }

        //リロード対策
        $_SESSION['reload'] = "first";
        $this->reload_off = $_SESSION['reload'];
        
        $customerId = $_SESSION['customer_id'];
        
        try{
            $customerDao = new CustomerDao();
            $itemsDao = new ItemsDao();
            $deliveryDao = new DeliveryDao();

            /**--------------------------------------------------------
               決済方法確定ボタンが押されたときの処理
             ---------------------------------------------------------*/
            if(isset($_POST['cmd']) && $_POST['cmd']=="pay_comp") {
                    $_SESSION['pay_error'] = NULL;
                    $_SESSION['isPay'] = NULL;
                    $_SESSION['order']['payment'] = NULL;
                if(!isset($_POST['payType'])){   
                    $_SESSION['pay_error']="is";
                    header('Location:order_pay_list.php');
                    exit();   
                }

                $_SESSION['payType'] = $_POST['payType'];
                
                if($_POST['payType']=="1") {
                    $_SESSION['order']['payment'] = "クレジットカード";
                } elseif ($_POST['payType']=="2") {
                    $_SESSION['order']['payment'] = "代引き";
                } else{
                    $_SESSION['order']['payment'] = "銀行振込";
                } 
            }
            /**-------------------------------------------------------
               前ページ情報をセッションへ格納
             ---------------------------------------------------------*/
            if(!empty($_SESSION["cart"])){
                if(isset($_POST['cmd']) && $_POST['cmd']=="order_confirm") {
                    
                    $total_amount = $_POST['total_amount'];
                    $total_payment = $_POST['total_payment'];
                    $postage = $_POST['postage'];
                    $tax = $_POST['tax'];
                    
                    $_SESSION['order'] = array(
                        'total_amount' => $total_amount,  
                        'total_payment' => $total_payment,
                        'postage' => $postage,
                        'tax' => $tax
                    );
                    
                    //商品点数（個別）
                    $var = 1;
                    for($i = 0 ; $i<count($_SESSION["cart"]); $i++ ){
                        $_SESSION["cart"][$i]['item_count'] = $_POST["cart{$var}"];
                        $var++;
                    }

                    //前画面のデータをセッションに格納したのち、非ログイン状態の場合はフラグをたててログイン画面へ。
                     if(!isset($customerId)){
                        $_SESSION['order_flag'] = 1;
                        header('Location:/html/login.php');
                        exit();
                    }
                }

                $customer = $customerDao->getCustomerById($customerId);

                if($customer->getDelFlag() !== "0"){    
                    //customerテーブルの住所が配送先のデフォルトでなければdeliveryテーブルからデフォルト設定された住所を取得、セッションに格納
                    $delivery = $deliveryDao->getDefDeliveryInfo($customerId);
                    $this->saveDeliveryData($delivery);
                }else{
                    $this->saveDeliveryData($customer);
                }
            }

            /**--------------------------------------------------------
               配送先確定ボタンがおされたときの処理
             ---------------------------------------------------------*/
            if(isset($_POST['cmd']) && $_POST['cmd']=="del_comp") {
                $_SESSION['def_addr'] = $_POST['def_addr'];
                if($_POST['def_addr']!=="1") {
                    $delivery  = $deliveryDao->getDeliveryInfoById($customerId, $_POST['def_addr']);
                    $this->saveDeliveryData($delivery);
                } else {
                    $customer = $customerDao->getCustomerById($customerId); 
                    $this->saveCustomerData($customer);
                }
                /**--------------------------------------------------------
                   決済方法確定ボタンがおされたときの処理
                 ---------------------------------------------------------*/
                if(isset($_POST['cmd']) && $_POST['cmd']=="pay_comp") {
                    $_SESSION['isPay'] = NULL;
                }
            }
        }catch(\PDOException $e){
            die('SQLエラー :'.$e->getMessage());
        }
    }
    
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
}

?>    

   