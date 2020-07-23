<?php
namespace Controllers;

use \Models\ItemsDto;
use \Models\ItemsDao;
use \Models\FavoriteDao;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class CartAction{
    
    private $availableForPurchase = TRUE;
        
    public function execute(){
        
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
            
        $itemCodeByPost = Config::getPOST('item_code');
        $itemCodeByGet = Config::getGET('item_code');
        $cmdPost = Config::getPOST('cmd');
        $cmdGet = Config::getGET('cmd');
    
        if(isset($_SESSION['customer_id'])){
            $customerId = $_SESSION['customer_id'];   
        }else{
            $customerId = FALSE;   
        }
        /*=====================================================================
        　「削除」ボタンが押された時の処理
        ======================================================================*/
        
        if($cmdGet == "del"){
            
            for($i=0; $i<count($_SESSION['cart']); $i++){
                if($_SESSION['cart'][$i]['item_code'] == $itemCodeByGet){
                    unset($_SESSION['cart'][$i]);
                }
            }
            $_SESSION['cart'] = array_merge($_SESSION['cart']);
        }


        /*====================================================================
      　  item_detail.php/favorite.phpでカートに入れるボタンがおされた時の処理
        =====================================================================*/
         
         if(isset($_SESSION['add_cart'])){
             
            $addCart = $_SESSION['add_cart'];
            $itemCode = $addCart['item_code'];
            $itemQuantity = $addCart['item_quantity'];
        
            $is_already_exists  = false;
            
            foreach($_SESSION['cart'] as &$cart){
                if($cart['item_code'] == $itemCode){
                    /*- 追加する商品がカートに既に存在している場合は数量を合算。 -*/  
                    $cart['item_quantity'] += $itemQuantity;
                    $is_already_exists = true;
                }
            }

             /*- 追加する商品がカートに存在しない場合、カートに新規登録。 -*/    
            if(!$is_already_exists){ 
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $itemsDao = new ItemsDao($pdo);
                    $dto = $itemsDao->getItemByItemCodeForPurchase($itemCode);

                    $item['item_code'] = $dto->getItemCode();
                    $item['item_quantity'] = $itemQuantity;

                    array_push($_SESSION['cart'], $item);

                }catch(DBConnectionException $e){
                    unset($_SESSION['add_cart']);
                    $e->handler($e);   

                } catch(MyPDOException $e){
                    unset($_SESSION['add_cart']);
                    $e->handler($e);

                }catch(DBParamException $e){
                    unset($_SESSION['add_cart']);
                    $e->handler($e);
                }
            }
             unset($_SESSION['add_cart']);
        }

        /*====================================================================
       　お気に入りに移動ボタンがおされたとき(ログイン状態/非ログイン状態)
        =====================================================================*/
        
        if($cmdPost == "move_fav"){
            
            if($customerId){
                /*- お気に入りに登録 -*/
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $favoriteDao = new FavoriteDao($pdo);
                    $favoriteDao->insertIntoFavorite($itemCodeByPost, $customerId);
                    
                }catch(DBConnectionException $e){
                    $e->handler($e);   
                    
                } catch(MyPDOException $e){
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    $e->handler($e);
                }
                
                /*- お気に入りに登録後カートから削除 -*/
                for($i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $itemCodeByPost){
                        unset($_SESSION['cart'][$i]);
                    }
                }
                
                $_SESSION['cart'] = array_merge($_SESSION['cart']); 
                
            }else{
                
                /*- ログイン状態がなければlogin.phpへ -*/
                $_SESSION['track_for_login'] = array(
                    'from' => 'cart',
                    'item_code' => $itemCodeByPost
                );
                
                header('Location:/html/login.php');
                exit();
            }
        }

        /*====================================================================
      　  非ログイン状態でお気に入りに移動ボタン->ログイン->リダイレクトでもどったときの処理 
        =====================================================================*/
        
        if(isset($_SESSION['track_for_login'])){
            
            $trackItem = $_SESSION['track_for_login'];    
            $from = $trackItem['from'];
            $itemCode = $trackItem['item_code'];
            
            if($from == "cart" && $customerId){
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $favoriteDao = new FavoriteDao($pdo);

                    $favoriteDao->insertIntoFavorite($itemCode, $customerId);

                    unset($_SESSION['track_for_login']);

                }catch(DBConnectionException $e){
                    unset($_SESSION['track_for_login']);
                    $e->handler($e);   

                } catch(MyPDOException $e){
                    unset($_SESSION['track_for_login']);
                    $e->handler($e);

                }catch(DBParamException $e){
                    unset($_SESSION['track_for_login']);
                    $e->handler($e);
                }

                /*- お気に入りに登録後カートから削除 -*/
                 for( $i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $itemCode){
                        unset($_SESSION['cart'][$i]);
                    }
                }

                $_SESSION['cart'] = array_merge($_SESSION['cart']); 
            }
        }

        /*====================================================================
      　  $_SESSION['cart']の値があった場合の共通処理
        =====================================================================*/
        $_SESSION['availableForPurchase'] = NULL;

        if($_SESSION['cart']){
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $itemsDao = new ItemsDao($pdo);   

                foreach($_SESSION['cart'] as &$cart){

                    $itemCode = $cart['item_code'];
                    $dto = $itemsDao->getItemByItemCode($itemCode);

                    /*- 個数(item_quantity)以外については最新の情報を取得 -*/
                    $cart['item_image_path'] = $dto->getItemImagePath();
                    $cart['item_name'] = $dto->getItemName();
                    $cart['item_price'] = $dto->getItemPrice();
                    $cart['item_tax'] = $dto->getItemTax();
                    $cart['item_price_with_tax'] = $dto->getItemPriceWithTax();
                    $cart['item_status'] = $dto->getItemStatus();
                    $cart['item_stock'] = $dto->getItemStock();
                    
                    if($cart['item_quantity']>$cart['item_stock']){
                        $cart['item_quantity'] = $cart['item_stock'];
                    }
                    if($cart['item_status'] !== "1"){
                        $this->availableForPurchase = FALSE;   
                    }
                }

            }catch(DBConnectionException $e){
                $e->handler($e);   

            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamException $e){
                $e->handler($e);
            }
        }else{
            $this->availableForPurchase = FALSE;  
        }
            
        if($this->availableForPurchase){
            $_SESSION['availableForPurchase'] = TRUE;
        }
    }
    
    public function getAvailableForPurchase(){
        return $this->availableForPurchase;   
    }
   
    public function alertStock($itemStock){
        if($itemStock <= 10){
            return true;   
        }else{
            return false;
        }   
    }

            
}

?>    

   