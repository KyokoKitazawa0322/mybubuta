<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;
use \Models\OriginalException;
use \Config\Config;

class OrderConfirmAction{
        
    public $reload_off;
    
    public function execute(){        

        //リロード対策
        $_SESSION['reload'] = "first";
        $this->reload_off = $_SESSION['reload'];

        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }

        $customerDao = new CustomerDao();
        $deliveryDao = new DeliveryDao();
        
        /**-------------------------------------------------------
           前ページ情報をセッションへ格納
         ---------------------------------------------------------*/
        if($cmd == "order_confirm"){

            $var = 1;
            $totalPrice = 0; 
            $totalPayment = 0; 
            $totalTax = 0;
            
            for($i = 0 ; $i<count($_SESSION["cart"]); $i++ ){
                $_SESSION["cart"][$i]['item_count'] = $_POST["cart{$var}"];
                $totalAmount += $_SESSION["cart"][$i]['item_count']; 
                $totalPayment = $_SESSION["cart"][$i]['item_price_with_tax'] * $_SESSION["cart"][$i]['item_count']
                $totalTax += $_SESSION["cart"][$i]['tax'] * $_SESSION["cart"][$i]['item_count']
                $var++;
            }
            
            if($totalPayment >= Config::POSTAGEFREEPRICE){
                $postage = 0;
            }else{
                $postage = Config::POSTAGE;
            }
                
            $_SESSION['order'] = array(
                'total_amount' => $totalAmount,  
                'total_payment' => $totalPayment,
                'tax' => $tax
                'postage' => $postage,
            );
        }
        
        //前画面のデータをセッションに格納したのち、非ログイン状態の場合はフラグをたててログイン画面へ。
        if(!isset($_SESSION["customer_id"])){
            $_SESSION['order_flag'] = "is";
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        //cart.php以外からの訪問はリダイレクトでcart.phpへ
        if(!isset($_SESSION["order"])){
            header('Location:/html/cart.php');
            exit();
        }

        try{
            $customer = $customerDao->getCustomerById($customerId);
            if($customer->getDelFlag() !== "0"){    
                //customerテーブルの住所が配送先のデフォルトでなければdeliveryテーブルからデフォルト設定された住所を取得、セッションに格納
                $delivery = $deliveryDao->getDefDeliveryInfo($customerId);
                $this->saveDeliveryData($delivery);

            }else{
                $this->saveCustomerData($customer);
            }

        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');

        }catch(OriginalException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
            header('Content-Type: text/plain; charset=UTF-8', true, 400);
            die('エラー:'.$e->getMessage());
        }

        /**--------------------------------------------------------
           配送先確定ボタンがおされたときの処理
         ---------------------------------------------------------*/
        if($cmd == "del_comp"){
            $def_addr = filter_input(INPUT_POST, 'def_addr');
            $_SESSION['def_addr'] = $def_addr;
            
            if($def_addr !== "customer") {
                try{
                    $delivery  = $deliveryDao->getDeliveryInfoById($customerId, $def_addr);
                    $this->saveDeliveryData($delivery);
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
                try{
                    $customer = $customerDao->getCustomerById($customerId); 
                    $this->saveCustomerData($customer);

                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');

                }catch(OriginalException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
            }
        }
        
        /**--------------------------------------------------------
           決済方法確定ボタンが押されたときの処理
         ---------------------------------------------------------*/
        if($cmd == "pay_comp") {
            $payType = filter_input(INPUT_POST, 'payType');
            $_SESSION['pay_error'] = NULL;
            $_SESSION['isPay'] = NULL;
            $_SESSION['order']['payment'] = NULL;

            if(!$payType){   
                $_SESSION['pay_error']="is";
                header('Location:/html/order/order_pay_list.php');
                exit();   
            }else{
                $_SESSION['payType'] = $payType;

                if($payType == "1") {
                    $_SESSION['order']['payment'] = "クレジットカード";
                }elseif($payType == "2") {
                    $_SESSION['order']['payment'] = "代引き";
                }else{
                    $_SESSION['order']['payment'] = "銀行振込";
                } 
            }
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

   