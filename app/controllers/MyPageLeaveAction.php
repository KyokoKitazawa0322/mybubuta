<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\CustomerDao;    

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

class MyPageLeaveAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
    
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];  
        
        /*====================================================================
         削除ボタンがおされたときの処理
        =====================================================================*/
 
        if($cmd == "leave"){
            
            $memPwd = filter_input(INPUT_POST, 'memPwd');
            
            try{
                $customerDao = new CustomerDao();
                
                $customerDto = $customerDao->getCustomerById($customerId);
                $hashPassword = $customerDto->getHashPassword();
                
                if(!password_verify($memPwd, $hashPassword)){
                    echo 'パスワードが正しくありません。';
                    
                }else{
                    $deliveryDao = new DeliveryDao();
                    
                    $customerDao->deleteCustomerInfo($customerId);
                    $deliveryDao->deleteAllDeliveryInfo($customerId);
                        
                    unset($_SESSION['customer_id']);
                    unset($_COOKIE['password']);
                    unset($_COOKIE['mail']);
                    header('Location:/html/mypage/leave_complete.php');
                    exit();
                }

            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamlException $e){
                $e->handler($e);
            }
        
        }
    }
}
?>