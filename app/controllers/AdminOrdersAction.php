<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\Model;

use \Config\Config;

use \Models\OriginalException;
use \Models\DBConnectionException;
use \Models\InvalidParamException;

class AdminOrdersAction{
    
    private $orders = [];
        
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        $cmd = Config::getPOST("cmd");
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $content = Config::getPOST('content');
    
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $orderHistoryDao = new OrderHistoryDao($pdo);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }
        
        if($cmd == "sort"){
            try{
                switch($content) {
                    case "sortby_purchase_date_asc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByPurchaseDateASC();
                        break;
                    case "sortby_total_amount_asc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalAmountASC();
                        break;  
                    case "sortby_total_quantity_asc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalQuantityASC();
                        break;  
                    case "sortby_purchase_date_desc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByPurchaseDateDESC();
                        break;
                    case "sortby_total_amount_desc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalAmountDESC();
                        break;  
                    case "sortby_total_quantity_desc":
                        $this->orders = $orderHistoryDao->getOrdersAllSortByTotalQuantityDESC();
                        break;
                    default:
                        throw new InvalidParamException("Invalid param for sort:".$content);
                } 
            } catch(InvalidParamException $e){
                $e->handler($e);
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(NoRecordException $e){
                $e->handler($e);
            }
            
        }else{
            try{
                $orders = $orderHistoryDao->getOrdersAll();
                $this->orders = $orders;
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(NoRecordException $e){
                $e->handler($e);
            }
        }
    }

    
    /*---------------------------------------*/
    public function getOrders(){
        return $this->orders;   
    }
}
?>