<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\CustomerDao;    
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageDeliveryAction extends \Controllers\CommonMyPageAction{
    
    private $customerDto; 
    private $deliveryDto;
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        $deliveryId = filter_input(INPUT_POST, 'del_id');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   

        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $deliveryDao = new DeliveryDao($pdo);
            $customerDao = new CustomerDao($pdo);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }
        /*====================================================================
        　「削除」ボタンが押された時の処理
        =====================================================================*/
        
        if($cmd == "delete"){
            try{
                $model->beginTransaction();
            } catch(MyPDOException $e){
                $e->handler($e);
            }
            
            try{
                $deliveryDao->deleteDeliveryInfo($customerId, $deliveryId);

                //全件削除した場合にデフォルト住所を基本登録にもどす
                $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
                if(!$deliveryDto){
                    $customerDao->setDeliveryFlag($customerId);
                }
                $model->commit();
                
            } catch(MyPDOException $e){
                if ($pdo->inTransaction()){
                    $pdo->rollback();
                }
                $e->handler($e);

            }catch(DBParamException $e){
                $pdo->rollback();
                $e->handler($e);
            }
        }
        
        /*====================================================================
        　「配送先設定」ボタンがおされたときの処理
        =====================================================================*/
        
        if($cmd=="update"){
            /*- 配送先情報があるか確認し、取得できた場合のみ更新処理。-*/
            try{
                $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            } catch(MyPDOException $e){
                $e->handler($e);
            }
            
            if($deliveryDto){
                try{
                    $model->beginTransaction();
                } catch(MyPDOException $e){
                    $e->handler($e);
                }
                
                if($deliveryId == "def"){
                    /*- customerテーブルの住所が選択された時の処理 -*/
                    /*- 「いつもの配送先住所」解除(deliveryテーブルとcustomerテーブルを全てFALSEに更新) -*/
                    try{
                        $deliveryDao->releaseDeliveryFlag($customerId);
                        $customerDao->setDeliveryFlag($customerId);
                        $model->commit();
                        
                    } catch(MyPDOException $e){
                        if ($pdo->inTransaction()){
                            $pdo->rollback();
                        }
                        $e->handler($e);

                    }catch(DBParamException $e){
                        $pdo->rollback();
                        $e->handler($e);
                    }

                /*- deliveryテーブルの住所が選択された時の処理 -*/
                }else{
                    /*- 「いつもの配送先住所」解除(deliveryテーブルとcustomerテーブルを全てFALSEに更新) -*/
                    try{
                        $deliveryDao->releaseDeliveryFlag($customerId);
                        $deliveryDao->setDeliveryFlag($customerId, $deliveryId);
                        $model->commit();
                            
                    } catch(MyPDOException $e){
                        if ($pdo->inTransaction()){
                            $pdo->rollback();
                        }
                        $e->handler($e);

                    }catch(DBParamException $e){
                        $pdo->rollback();
                        $e->handler($e);
                    }
                }
                
            }
        }
        
        try{
            /*- 会員登録情報の取得 -*/
            $this->customerDto = $customerDao->getCustomerById($customerId);
            /*- 配送先情報の取得 -*/
            $this->deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
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