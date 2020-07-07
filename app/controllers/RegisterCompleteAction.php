<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;

use \Models\CsrfValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;


class RegisterCompleteAction{
          
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        /*====================================================================
        　register_confirm.phpで「この内容で登録をする」ボタンが押された時の処理
        =====================================================================*/
        
        $token = filter_input(INPUT_POST, "token_register_complete");
        $formName = "token_register_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

        $lastName = $_SESSION['register']['last_name'];
        $firstName = $_SESSION['register']['first_name'];
        $rubyLastName = $_SESSION['register']['ruby_last_name'];
        $rubyFirstName = $_SESSION['register']['ruby_first_name'];
        $zipCode01 = $_SESSION['register']['zip_code_01'];
        $zipCode02 = $_SESSION['register']['zip_code_02'];
        $prefecture = $_SESSION['register']['prefecture'];
        $city = $_SESSION['register']['city'];
        $blockNumber = $_SESSION['register']['block_number'];
        $buildingName = $_SESSION['register']['building_name'];
        $tel = $_SESSION['register']['tel'];
        $mail = $_SESSION['register']['mail'];
        $password = $_SESSION['register']['password'];

        try{
            $customerDao = new CustomerDao();
            
            $customerDao->insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail);

            $customerDto = $customerDao->getCustomerByMail($mail); 
            $_SESSION['customer_id'] = $customerDto->getCustomerId();
            
            unset($_SESSION['register']); 
            setcookie('mail','',time()-3600,'/');
            setcookie('password','',time()-3600,'/');
            setcookie('mail',$mail,time()+60*60*24*7);
            setcookie('password',$password,time()+60*60*24*7);

        } catch(MyPDOException $e){
            $e->handler($e);
        }
    }
}
?>    

   