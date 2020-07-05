<?php
namespace Controllers;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

class LeftPaneAction {
    
    public function echoValue($key){
        if(isset($_SESSION['search'][$key])){
            echo $_SESSION['search'][$key];
        }
    }

    public function checkCategoryValue($key){
        if(isset($_SESSION['search']['category'][$key])){
            echo "checked";
        }
    }
    
    public function checkValue($key, $value){
        if(isset($_SESSION['search'][$key]) && $_SESSION['search'][$key] == $value){
            echo "selected";
        }
    }
}
?>    

   