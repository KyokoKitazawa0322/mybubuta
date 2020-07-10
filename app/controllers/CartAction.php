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
            
        $itemCodeByPost = filter_input(INPUT_POST, 'item_code');
        $itemCodeByGet = filter_input(INPUT_GET, 'item_code');
        $itemQuantity = filter_input(INPUT_POST, 'item_quantity', FILTER_VALIDATE_INT);
        $cmdPost = filter_input(INPUT_POST, 'cmd');
        $cmdGet = filter_input(INPUT_GET, 'cmd');
    
        if(isset($_SESSION['customer_id'])){
            $customerId = $_SESSION['customer_id'];   
        }
        unset($_SESSION['purchase_error']);
        
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
       　 favorite.phpでカートにいれるボタンがおされたときの処理
        =====================================================================*/
    
        if(isset($_SESSION['add_cart_from_fav']) && $_SESSION['add_cart_from_fav'] == "undone"){

            $is_already_exists  = false;
            $itemCodeByFavorite = $_SESSION['item_code'];
            
            for($i=0; $i<count($_SESSION['cart']); $i++){
                if( $_SESSION['cart'][$i]['item_code'] == $itemCodeByFavorite){
                    /*- 追加する商品がカートに既に存在している場合は数量を合算。-*/
                    $_SESSION['cart'][$i]['item_quantity'] += 1;
                    $is_already_exists = true;
                }
            }
            
            /*- 追加する商品がカートに存在しない場合、カートに新規登録 -*/
            if(!$is_already_exists){
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $itemsDao = new ItemsDao($pdo);
                    $dto = $itemsDao->getItemByItemCodeForPurchase($itemCodeByFavorite);
                    
                    $item['item_code'] = $dto->getItemCode();
                    $item['item_quantity'] = 1;
            
                    array_push($_SESSION['cart'], $item);
                
                }catch(DBConnectionException $e){
                    $e->handler($e);   

                } catch(MyPDOException $e){
                    unset($_SESSION['add_cart_from_fav']);
                    unset($_SESSION['item_code']);
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    unset($_SESSION['add_cart_from_fav']);
                    unset($_SESSION['item_code']);
                    $e->handler($e);
                }
            }
            unset($_SESSION['add_cart_from_fav']);
            unset($_SESSION['item_code']);
        }

        /*====================================================================
       　お気に入りに移動ボタンがおされたとき(ログイン状態/非ログイン状態)
        =====================================================================*/
        
        if($cmdPost == "move_fav" ){
            
            if(isset($customerId)){
            
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
                $_SESSION['cart_flag'] = "is";
                $_SESSION['move_fav_item_code'] = $itemCodeByPost;
                
                header('Location:/html/login.php');
                exit();
            }
        }

        /*====================================================================
      　  非ログイン状態でお気に入りに移動ボタン->ログイン->リダイレクトでもどったときの処理 
        =====================================================================*/
        
        if(isset($_SESSION['cart_flag']) && $_SESSION['cart_flag'] == "is"){
        
            if(isset($customerId)){    
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $favoriteDao = new FavoriteDao($pdo);
                    $moveItemCode = $_SESSION['move_fav_item_code'];
                    $favoriteDao->insertIntoFavorite($moveItemCode, $customerId);
                    
                    unset($_SESSION['cart_flag']);
                    unset($_SESSION['move_fav_item_code']);
                    
                }catch(DBConnectionException $e){
                    unset($_SESSION['cart_flag']);
                    unset($_SESSION['move_fav_item_code']);
                    $e->handler($e);   

                } catch(MyPDOException $e){
                    unset($_SESSION['cart_flag']);
                    unset($_SESSION['move_fav_item_code']);
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    unset($_SESSION['cart_flag']);
                    unset($_SESSION['move_fav_item_code']);
                    $e->handler($e);
                }
                    
                /*- お気に入りに登録後カートから削除 -*/
                 for( $i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $moveItemCode){
                        unset($_SESSION['cart'][$i]);
                    }
                }
                    
                $_SESSION['cart'] = array_merge($_SESSION['cart']);  
                    
            }else{
                header('Location:/html/login.php');
            }
        }

        /*====================================================================
      　  item_detail.phpでカートに入れるボタンがおされた時の処理
        =====================================================================*/
         
         if($cmdPost == "add_cart" && isset($_SESSION['add_cart']) && $_SESSION['add_cart'] == 'undone'){
            
            $is_already_exists  = false;
             
            for($i=0; $i<count($_SESSION['cart']); $i++){
                if( $_SESSION['cart'][$i]['item_code'] == $itemCodeByPost){
                    /*- 追加する商品がカートに既に存在している場合は数量を合算。 -*/  
                    $_SESSION['cart'][$i]['item_quantity'] = $_SESSION['cart'][$i]['item_quantity'] + $itemQuantity;
                    $is_already_exists = true;
                }
            }
             
             /*- 追加する商品がカートに存在しない場合、カートに新規登録。 -*/    
            if(!$is_already_exists){ 
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $itemsDao = new ItemsDao($pdo);
                    $dto = $itemsDao->getItemByItemCodeForPurchase($itemCodeByPost);

                    $item['item_code'] = $dto->getItemCode();
                    $item['item_quantity'] = $itemQuantity;
                    
                    array_push($_SESSION['cart'], $item);
                    unset($_SESSION['add_cart']);
                    
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

                for($i=0; $i<count($_SESSION['cart']); $i++){

                    $itemCode = $_SESSION['cart'][$i]['item_code'];
                    $dto = $itemsDao->getItemByItemCode($itemCode);

                    /*- 個数(item_quantity)以外については最新の情報を取得 -*/
                    $_SESSION['cart'][$i]['item_image_path'] = $dto->getItemImagePath();
                    $_SESSION['cart'][$i]['item_name'] = $dto->getItemName();
                    $_SESSION['cart'][$i]['item_price'] = $dto->getItemPrice();
                    $_SESSION['cart'][$i]['item_tax'] = $dto->getItemTax();
                    $_SESSION['cart'][$i]['item_price_with_tax'] = $dto->getItemPriceWithTax();
                    $_SESSION['cart'][$i]['item_status'] = $dto->getItemStatus();

                    if($dto->getItemStatus() !== "1"){
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
}

?>    

   