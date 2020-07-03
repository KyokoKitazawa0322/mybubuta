<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\CustomerDao;    
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;

class MyPageDeliveryAction extends \Controllers\CommonMyPageAction{
    
    private $customerDto; 
    private $deliveryDto;
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        $deliveryId = filter_input(INPUT_POST, 'del_id');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   

        $deliveryDao = new DeliveryDao();
        $customerDao = new CustomerDao();
        
        /*====================================================================
        　「削除」ボタンが押された時の処理
        =====================================================================*/
        
        if($cmd == "delete"){
            try{
                $deliveryDao->deleteDeliveryInfo($customerId, $deliveryId);

                //全件削除した場合にデフォルト住所を基本登録にもどす
                $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
                if(!$deliveryDto){
                    $customerDao->setDeliveryFlag($customerId);
                }
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamException $e){
                $e->handler($e);
            }
        }
        
        /*====================================================================
        　「配送先設定」ボタンがおされたときの処理
        =====================================================================*/
        
        if($cmd=="update"){
            /*- 配送先情報があるか確認し、取得できた場合のみ更新処理。-*/
            $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            if($deliveryDto){
                /*- customerテーブルの住所が選択された時の処理 -*/
                if($deliveryId == "def"){
                    try{
                        /*- 「いつもの配送先住所」解除(deliveryテーブルとcustomerテーブルを全てFALSEに更新) -*/
                        $deliveryDao->releaseDeliveryFlag($customerId);
                        $customerDao->setDeliveryFlag($customerId);
                        
                    } catch(MyPDOException $e){
                        $e->handler($e);

                    }catch(DBParamException $e){
                        $e->handler($e);
                    }

                /*- deliveryテーブルの住所が選択された時の処理 -*/
                }else{
                    try{
                        /*- (値精査)「いつもの配送先住所」解除前にdelivery_idを確認し、取得できた場合のみ更新処理。なければgetDeliveryInfoByIdの中で例外発生-*/
                        $deliveryDto = $deliveryDao->getDeliveryInfoById($customerId, $deliveryId);
                        if($deliveryDto){
                            /*- 「いつもの配送先住所」解除(deliveryテーブルとcustomerテーブルを全てFALSEに更新) -*/
                            $deliveryDao->releaseDeliveryFlag($customerId);
                            $deliveryDao->setDeliveryFlag($customerId, $deliveryId);
                        }
                    } catch(MyPDOException $e){
                        $e->handler($e);

                    }catch(DBParamException $e){
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