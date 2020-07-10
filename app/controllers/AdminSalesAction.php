<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class AdminSalesAction{
    
    private $result;
    private $orderDetails = [];
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        /*====================================================================
      　  検索ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "search_sales"){
            
            unset($_SESSION['search_error']);
            $content = filter_input(INPUT_POST, 'content');
            $year = filter_input(INPUT_POST, 'year');
            $month = filter_input(INPUT_POST, 'month');
            $day = filter_input(INPUT_POST, 'day');
            $year_2 = filter_input(INPUT_POST, 'year_2');
            $month_2 = filter_input(INPUT_POST, 'month_2');
            $day_2 = filter_input(INPUT_POST, 'day_2');
            
            if($month){
                $month = sprintf('%02d', $month);
            }
            if($day){
                $day = sprintf('%02d', $day); 
            }
            
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $orderHistoryDao = new OrderHistoryDao($pdo);
                $orderDetailDao = new OrderDetailDao($pdo);
                                                     
            }catch(DBConnectionException $e){
                $e->handler($e);   
            }

            switch($content){
                case "month":
                    if(!$year||!$month){
                        $_SESSION['search_error']['select'] = TRUE;
                    }else{
                        try{
                            try{
                                $day = "01";
                                $dateTime = new \DateTime($year.'-'.$month.'-'.$day);
                                $month = $dateTime->format('Y-m');
                                
                            }catch(\Exception $e){
                                throw new InvalidParamException($e->getMessage());   
                            }
                            
                            $this->result = $orderHistoryDao->getOrderHistoryByMonth($month);
                            $_SESSION['search_term'] = $dateTime->format('Y年n月');

                            $this->orderDetails = $orderDetailDao->getOrderDetailByMonth($month);

                        }catch(MyPDOException $e){
                            $e->handler($e);  

                        }catch(InvalidParamException $e){
                            $e->handler($e);
                        }
                    }
                    break;

                case "day":
                    if(!$year||!$month||!$day){
                        $_SESSION['search_error']['select'] = TRUE;
                    }else{
                        try{
                            try{
                                $dateTime = new \DateTime($year.'-'.$month.'-'.$day);
                                $date = $dateTime->format('Y-m-d');
                            }catch(\Exception $e){
                                throw new InvalidParamException($e->getMessage());   
                            }

                            $this->result = $orderHistoryDao->getOrderHistoryByDate($date);
                            $_SESSION['search_term'] = $dateTime->format('Y年n月j日');

                            $this->orderDetails = $orderDetailDao->getOrderDetailByDate($date);

                        }catch(MyPDOException $e){
                            $e->handler($e);  

                        }catch(InvalidParamException $e){
                            $e->handler($e);
                        }
                    }
                    break;

                case "term":
                    if(!$year||!$month||!$day||!$year_2||!$month_2||!$day_2){
                        $_SESSION['search_error']['select'] = TRUE;
                    }else{
                        try{
                            try{
                                $dateTime_1 = new \DateTime($year.'-'.$month.'-'.$day);
                                $dateTime_2 = new \DateTime($year_2.'-'.$month_2.'-'.$day_2);
                                $date_1 = $dateTime_1->format('Y-m-d');
                                $date_2 = $dateTime_2->format('Y-m-d');
                            }catch(\Exception $e){
                                throw new InvalidParamException($e->getMessage());   
                            }

                            $this->result = $orderHistoryDao->getOrderHistoryByTerm($date_1, $date_2);
                            $_SESSION['search_term'] = $dateTime_1->format('Y年n月j日')."～".$dateTime_2->format('Y年n月j日');

                            $this->orderDetails = $orderDetailDao->getOrderDetailByTerm($date_1, $date_2);

                        }catch(MyPDOException $e){
                            $e->handler($e);  

                        }catch(InvalidParamException $e){
                            $e->handler($e);
                        }
                    }
                    break;

                default:
                    $_SESSION['search_error']['radio'] = TRUE;  
            }
        }
    }
        
    public function getResult(){
        return $this->result;   
    } 
    
    public function getOrderDetails(){
        return $this->orderDetails;   
    } 
    
    public function checkRadioValue($content){
        $postContent = filter_input(INPUT_POST, 'content');
        if($postContent==$content){
            echo "checked";
        }
    }
    
    public function checkOptionValue($content, $date){
        $postContent = filter_input(INPUT_POST, 'content');
        $postDate = filter_input(INPUT_POST, $date);
        if($postContent==$content && $postDate){
            echo $postDate;
        }else{
            echo "0";   
        }
    }
}

?>