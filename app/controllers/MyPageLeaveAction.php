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

class MyPageLeaveAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = Config::getPOST("cmd");
    
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];  
        
        /*====================================================================
         削除ボタンがおされたときの処理
        =====================================================================*/
 
        if($cmd == "leave"){
            
            $memPwd = Config::getPOST('memPwd');
            
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $customerDao = new CustomerDao($pdo);
                
                $customerDto = $customerDao->getCustomerById($customerId);
                $hashPassword = $customerDto->getHashPassword();
                
                if(!password_verify($memPwd, $hashPassword)){
                    echo 'パスワードが正しくありません。';
                    
                }else{
                    $deliveryDao = new DeliveryDao($pdo);
                    
                    $customerDao->deleteCustomerInfo($customerId);
                    $deliveryDao->deleteAllDeliveryInfo($customerId);
                        
                    unset($_SESSION['customer_id']);
                    unset($_COOKIE['password']);
                    unset($_COOKIE['mail']);
                    header('Location:/html/mypage/leave/leave_complete.php');
                    exit();
                }

            }catch(DBConnectionException $e){
                $e->handler($e);   
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamlException $e){
                $e->handler($e);
            }
        
        }
    }
}
?>