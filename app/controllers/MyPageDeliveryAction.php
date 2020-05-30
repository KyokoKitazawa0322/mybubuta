<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\CustomerDao;    
use \Models\OriginalException;
use \Config\Config;

class MyPageDeliveryAction{
    
    private $customerDto; 
    private $deliveryDto;
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }

        $deliveryDao = new DeliveryDao();
        $customerDao = new CustomerDao();
        
        $deliveryId = filter_input(INPUT_POST, 'del_id');
        
        /**--------------------------------------------------------
           削除ボタンがおされたときの処理
         ---------------------------------------------------------*/
        if($cmd == "delete"){
            try{
                $deliveryDao->deleteDeliveryInfo($customerId, $deliveryId);

                //全件削除した場合にデフォルト住所を基本登録にもどす
                $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
                if(!$deliveryDto){
                    $customerDao->setDeliveryDefault($customerId);
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
        }
        
        /**--------------------------------------------------------
           配送先設定ボタンがおされたときの処理
         ---------------------------------------------------------*/
        if($cmd == "update" && $deliveryId){
            if($deliveryId == "def"){
                try{
                    $customerDao->setDeliveryDefault($customerId);
                    $deliveryDao->releaseDeliveryDefault($customerId);

                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');

                }catch(OriginalException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
                
        //配送先登録情報であれば値はdelivery_id
            }else{
                try{
                    $deliveryDao->releaseDeliveryDefault($customerId);
                    $customerDao->releaseDeliveryDefault($customerId);
                    $deliveryDao->setDeliveryDefault($customerId, $deliveryId);
                    
                } catch(\PDOException $e){
                    Config::outputLog($e);
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
            
                }catch(OriginalException $e){
                    Config::outputLog($e->getMessage().$e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
            }
        }
        
        try{
            //会員登録情報の取得
            $this->customerDto = $customerDao->getCustomerById($customerId);
            //配送先情報の取得
            $this->deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            
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
    
    public function getCustomerDto(){
        return $this->customerDto;   
    }
    
    public function getDeliveryDto(){
        return $this->deliveryDto;   
    }
}
?>