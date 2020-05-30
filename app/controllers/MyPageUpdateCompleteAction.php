<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\OriginalException;
use \Config\Config;
use \Models\CommonValidator;

class MyPageUpdateCompleteAction {

    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout"){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
    
        if($cmd == "do_register"){
            $password = $_SESSION['update']['password'];
            $lastName = $_SESSION['update']['last_name'];
            $firstName = $_SESSION['update']['first_name'];
            $rubyLastName = $_SESSION['update']['ruby_last_name'];
            $rubyFirstName = $_SESSION['update']['ruby_first_name'];
            $address01 = $_SESSION['update']['address01'];
            $address02 = $_SESSION['update']['address02'];
            $address03 = $_SESSION['update']['address03'];
            $address04 = $_SESSION['update']['address04'];
            $address05 = $_SESSION['update']['address05'];
            $address06 = $_SESSION['update']['address06'];
            $tel = $_SESSION['update']['tel'];
            $mail = $_SESSION['update']['mail'];

            try {
                $customerDao = new CustomerDao();
                $customerDao->updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail, $customerId);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');
                
            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
            
            $_SESSION['update'] = NULL;
            $_SESSION['password_input'] = NULL;
        } 
        //order_delivery_listからきた場合
        if(isset($_SESSION['from_order_flag'])){
            header('Location:/html/order/order_delivery_list.php');
            $_SESSION['from_order_flag']=NULL;
            exit();
        } 
    }
}
?>