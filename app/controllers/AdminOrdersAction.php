<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;

use \Config\Config;
use \Models\OriginalException;
    
class AdminOrdersAction{
    
    private $orders = [];
        
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
        $orderHistoryDao = new OrderHistoryDao();
        
        if($cmd == "sort"){
            try{
                switch($content) {
                    case "sortby_purchase_date_asc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByPurchaseDateASC();
                        break;
                    case "sortby_total_amount_asc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalAmountASC();
                        break;  
                    case "sortby_total_quntity_asc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalQuantityASC();
                        break;  
                    case "sortby_purchase_date_desc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByPurchaseDateDESC();
                        break;
                    case "sortby_total_amount_desc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalAmountDESC();
                        break;  
                    case "sortby_total_quntity_desc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalQuantityDESC();
                        break;  
                    default:
                        throw new InvalidParamException("Invalid param for sort:".$content);
                } 
            } catch(\PDOException $e){
 
            }
            
        }else{
            try{
                $orders = $orderHistoryDao->getOrdersAll();
                $this->orders = $orders;
                
                
            } catch(\PDOException $e){


            }catch(OriginalException $e){

            }
        }
    }

    
    public function getOrders(){
        return $this->orders;   
    }
    
}
?>