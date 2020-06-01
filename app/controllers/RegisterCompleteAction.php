<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;

class RegisterCompleteAction{
          
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "complete" && isset($_SESSION['register'])){
            
            $lastName = $_SESSION['register']['last_name'];
            $firstName = $_SESSION['register']['first_name'];
            $rubyLastName = $_SESSION['register']['ruby_last_name'];
            $rubyFirstName = $_SESSION['register']['ruby_first_name'];
            $address01 = $_SESSION['register']['address01'];
            $address02 = $_SESSION['register']['address02'];
            $address03 = $_SESSION['register']['address03'];
            $address04 = $_SESSION['register']['address04'];
            $address05 = $_SESSION['register']['address05'];
            $address06 = $_SESSION['register']['address06'];
            $tel = $_SESSION['register']['tel'];
            $mail = $_SESSION['register']['mail'];
            $password = $_SESSION['register']['password'];
            
            $customerDao = new CustomerDao();
            
            try{
                $customerDao->insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail);
            }catch(\PDOException $e){
                die('SQLエラー :'.$e->getMessage());
            }
            
            try{
                $customerDto = $customerDao->getCustomerByMail($mail); 
            }catch(\PDOException $e){
                die('SQLエラー :'.$e->getMessage());
            }
            
            $_SESSION['customer_id'] = $customerDto->getCustomerId();
            unset($_SESSION['register']); 
        }else{
            header('Location:/html/item_list.php');
            exit();
        }
    }
}
?>    

   