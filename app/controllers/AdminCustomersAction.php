<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
    
class AdminCustomersAction{
    
    private $customersDto;
        
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $content = filter_input(INPUT_POST, 'content');
        $customerDao = new CustomerDao();
        
        if($cmd == "sort"){
            try{
                switch($content) {
                    case "sortby_insert_date_asc":
                        $customersDto = $customerDao->getCustomersAllSortByInsertDateAsc();
                        break;     
                    case "sortby_insert_date_desc":
                        $customersDto = $customerDao->getCustomersAllSortByInsertDateDesc();
                        break;     
                    default:
                        throw new InvalidParamException("Invalid param for sort:".$content);
                }
                    $this->customersDto = $customersDto;
                
            } catch(InvalidParamException $e){
                $e->handler($e);
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(NoRecordException $e){
                $e->handler($e);
            }
            
        }else{
            
            try{
                $customersDto = $customerDao->getCustomersAll();
                $this->customersDto = $customersDto;
                
            } catch(MyPDOException $e){
                $e->handler($e);
                
            } catch(NoRecordException $e){
                $e->handler($e);
            }
        }
    }
    
    public function getCustomers(){
        return $this->customersDto;   
    }
    
}
?>