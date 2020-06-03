<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;

class RegisterConfirmAction{
        
    public function execute(){
        
        if($_SESSION['register']['input'] != "complete"){
            header('Location:/html/item_list.php');
        }
    }
}