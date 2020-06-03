<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;
use \Models\OriginalException;
use \Config\Config;

class OrderDeliveryListAction{

    private $customerDto;
    private $deliveryDto;
    
    public function execute(){

        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        $delId = filter_input(INPUT_POST, 'del_id');
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $customerDao = new CustomerDao();
        $deliveryDao = new DeliveryDao();
        
        /*====================================================================
         「削除」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "delete"){
            try{
                $deliveryDao->deleteDeliveryInfo($customerId, $delId);
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
        /*=============================================================*/
   
        try{
            /*- customerテーブルの登録情報を取得 -*/
            $customerDto = $customerDao->getCustomerById($customerId);
            $this->customerDto = $customerDto;
        
            /*- deliverテーブルの登録情報を取得 -*/
            $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            $this->deliveryDto = $deliveryDto;
            
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

    public function getCustomer(){
        return $this->customerDto;
    }
    
    public function getDelivery(){
        return $this->deliveryDto;   
    }
    
    public function checkCustomer($customer){
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr'] == "customer"){
                echo 'checked="checked"';
            }
        }elseif($customer->getDeliveryFlag() == 0){
            echo 'checked="checked"';
        }
    }
        
    public function checkDelivery($delivery){
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr'] == $delivery->getDeliveryId()){
                echo 'checked="checked"';
            }
        }elseif($delivery->getDeliveryFlag() == 0){ 
            echo 'checked="checked"';
        }
    }
}

?>    

   