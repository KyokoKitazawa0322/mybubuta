<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\OriginalException;
use \Config\Config;

class RegisterCompleteAction{
          
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        /*====================================================================
        　register_confirm.phpで「この内容で登録をする」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "complete" && isset($_SESSION['register'])){
            
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
            
            $customerDao = new CustomerDao();
            
            try{
                $customerDao->insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail);
                
                $customerDto = $customerDao->getCustomerByMail($mail); 
            
                $_SESSION['customer_id'] = $customerDto->getCustomerId();
                unset($_SESSION['register']); 
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');

            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
            
        }else{
            header('Location:/html/item_list.php');
            exit();
        }
    }
}
?>    

   