<?php
namespace Controllers;

use \Models\FavoriteDao;
use \Models\ItemsDto;
use \Models\ItemsDao;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageFavoriteAction extends \Controllers\CommonMyPageAction{
    
    private $favoriteDto; 
        
    public function execute(){
            
        $cmd = Config::getPOST("cmd");
        $itemCode = Config::getPOST('item_code');
        
        $this->checkLogoutRequest($cmd);
        
        if(isset($_SESSION['customer_id'])){
            $customerId = $_SESSION['customer_id'];
        }else{
            $customerId = FALSE;   
        }

        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $favoriteDao = new FavoriteDao($pdo);
        
        }catch(DBConnectionException $e){
            $e->handler($e); 
        }
        
        /*========================================================
        item_detail.phpで「お気に入り保存」ボタンが押された時の処理
        =========================================================*/
        
        if($cmd == "add_favorite"){
            
            /*- 非ログイン状態の場合はフラグをたててログイン画面へ -*/
            if(!$customerId){    
                
                $_SESSION['trace_for_login'] = array(
                    "from" => "item_detail",
                    "item_code" => $itemCode
                );
                
                header('Location:/html/login.php');
                exit();
            }else{
                try{
                    $favoriteDao->insertIntoFavorite($itemCode, $customerId);
                    
                } catch(MyPDOException $e){
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    $e->handler($e);
                }
            }
        }
        
        /*==============================================================
        　ログイン状態の判定(セッション切れの場合はlogin.phpへ)
        ===============================================================*/
        
        $this->checkLogin();

        /*===============================================================
        　item_detail.phpで「お気に入り保存」ボタンが押され、その後ログインをはさんだ場合の処理
        ================================================================*/
        
        if(isset($_SESSION['trace_for_login'])){
            
            $trackItem = $_SESSION['trace_for_login'];    
            $from = $trackItem['from'];
            $itemCode = $trackItem['item_code'];
            
            if($from == "item_detail"){    
                try{
                    $favoriteDao->insertIntoFavorite($itemCode, $customerId);

                } catch(MyPDOException $e){
                    unset($_SESSION['trace_for_login']);
                    $e->handler($e);

                }catch(DBParamException $e){
                     unset($_SESSION['trace_for_login']);
                    $e->handler($e);
                }
                unset($_SESSION['trace_for_login']);
            }
        }

        /*===============================================================
        　「カートにいれる」ボタンがおされたときの処理
        ================================================================*/
        if($cmd == "add_cart"){
            
            $_SESSION['add_cart'] = array(
                "item_code" => $itemCode,
                "item_quantity" => 1
            );
            
            header('Location:/html/cart.php');
            exit();
        }
        
        /*===============================================================
        　「削除」ボタンがおされたときの処理
        ================================================================*/
        if($cmd == "delete"){            
            try{
                $favoriteDao->deleteFavorite($itemCode, $customerId);
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamException $e){
                $e->handler($e);
            }
        }

        /*===============================================================
       　 お気に入り商品の一覧を取得
        ================================================================*/
        try{
            $favoriteDto = $favoriteDao->getFavoriteAll($customerId);
            $this->favoriteDto = $favoriteDto;
            
            } catch(MyPDOException $e){
                $e->handler($e);
        }
    }
    
    public function getFavoriteDto(){
        return $this->favoriteDto;   
    }
    
    public function checkItemStatus($status){
        if($status==1 || $status==2 || $status==5){
            return true;   
        }
    }
}
?>    

   