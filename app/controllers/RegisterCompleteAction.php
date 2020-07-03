<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Config\Config;

class RegisterCompleteAction{
          
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        /*====================================================================
        　register_confirm.phpで「この内容で登録をする」ボタンが押された時の処理
        =====================================================================*/
        
        try{
            $this->checkToken();
                
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

        } catch(MyPDOException $e){
            $e->handler($e);
        }
    }
    
    /*---------------------------------------*/
    //トークンをセッションから取得
    public function checkToken(){
        $tokenComplete = filter_input(INPUT_POST, "token_complete");
        //セッションがないか生成したトークンと異なるトークンでPOSTされたときは不正アクセス
        if(!isset($_SESSION['token']['complete']) || ($_SESSION['token']['complete'] != $tokenComplete)){
            if(!isset($_SESSION['token']['complete'])){
                $sessionTokenComplete = "nothing"; 
            }else{
                $sessionTokenComplete = $_SESSION['token']['complete'];   
            }
            throw new InvalidParamException('Invalid param for register_complete:$tokenComplete='.$tokenComplete.'/$_SESSION["token"]["complete"]='.$sessionTokenComplete);
        }else{
            unset($_SESSION['token']);   
        }
    }
}
?>    

   