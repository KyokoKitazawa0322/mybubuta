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
        
        if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_logout" ){
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
        if(isset($_POST['del_item'])){
            try{
                $deliveryDao->deleteDeliveryInfo($customerId, $deliveryId);

                //全件削除した場合にデフォルト住所を基本登録にもどす
                $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
                if(!$deliveryDto){
                    $customerDao->setDeliveryDefault($customerId);
                }
            } catch(\PDOException $e){
                Config::OutPutLog('SQLエラー:.'.$e->getMessage());
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');
            }catch(OriginalException $e){
                Config::OutPutLog('不正値エラー:.'.$e->getMessage().'ExceptionCode='.$e->getCode());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
        }
        /**--------------------------------------------------------
           配送先設定ボタンがおされたときの処理
         ---------------------------------------------------------*/
        if(isset($_POST['set']) && $deliveryId){
            if($deliveryId=="def"){
                //customers:del_flag=0(デェフォルト)
                //delivery:del_flag=1に
                try{
                    $customerDao->setDeliveryDefault($customerId);
                    $deliveryDao->releaseDeliveryDefault($customerId);
                } catch(\PDOException $e){
                    Config::OutPutLog('SQLエラー:.'.$e->getMessage());
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
                }catch(OriginalException $e){
                    Config::OutPutLog('不正値エラー:.'.$e->getMessage().'ExceptionCode='.$e->getCode());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
                
        //配送先登録情報であれば値はdelivery_id
            }else{
                //いつもの配送先に設定されている住所を解除
                try{
                    $deliveryDao->releaseDeliveryDefault($customerId);
                    $customerDao->releaseDeliveryDefault($customerId);
                    $deliveryDao->setDeliveryDefault($customerId, $deliveryId);
                } catch(\PDOException $e){
                    Config::OutPutLog('SQLエラー:.'.$e->getMessage());
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
                }catch(OriginalException $e){
                    Config::OutPutLog('不正値エラー:.'.$e->getMessage().'ExceptionCode='.$e->getCode());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
            }
        }
        
        try{
            //会員登録情報の取得
            $this->customerDto = $customerDao->getCustomerById($customerId);
            //配送先情報の取得（あれば表示）
            $this->deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
        } catch(\PDOException $e){
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            Config::OutPutLog('SQLエラー:.'.$e->getMessage());
            die('エラー:データベースの処理に失敗しました。');
        }catch(OriginalException $e){
            header('Content-Type: text/plain; charset=UTF-8', true, 400);
            Config::OutPutLog('不正値エラー:.'.$e->getMessage().'ExceptionCode='.$e->getCode());
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