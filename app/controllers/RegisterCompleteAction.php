<?php
//削除フラグ
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;

class RegisterCompleteAction{
          
    public function execute(){
        
        if (isset($_POST['reload']) && $_SESSION['reload'] == $_POST['reload']) {
            
            $_SESSION['reload'] = "";    
            
            try{
                
                $customerDao = new CustomerDao();

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
                
                $customerDao->insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail);

                $customerDto = $customerDao->getCustomerByMail($mail);
                $_SESSION['customer_id'] = $customerDto->getCustomerId();
                $_SESSION['register'] = NULL; 
                
            }catch(\PDOException $e){
                die('SQLエラー :'.$e->getMessage());
            }
        }else{
            header('Location:/html/item_list.php');
            exit();
        }
    }
}
?>    

   