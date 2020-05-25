<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;

class RegisterConfirmAction{
        
    private $reload_off; 
        
    public function execute(){
        
        if($_SESSION['register']['input'] == "complete"){
            $_SESSION['reload'] = "first";
            $this->reload_off = $_SESSION['reload'];
        }else{
            header('Location:/html/item_list.php');
        }
    }
    
    public function getReloadOff(){
        return $this->reload_off;    
    }
}