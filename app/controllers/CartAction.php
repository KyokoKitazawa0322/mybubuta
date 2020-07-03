<?php
namespace Controllers;
use \Models\ItemsDto;
use \Models\ItemsDao;
use \Models\FavoriteDao;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
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
                $itemsDao = new ItemsDao();
                try{
                    $dto = $itemsDao->getItemByItemCodeForPurchase($itemCodeByFavorite);
                    
                    $item['item_code'] = $dto->getItemCode();
                    $item['item_quantity'] = 1;
            
                    array_push($_SESSION['cart'], $item);
                
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
            $favoriteDao = new FavoriteDao();
            
            if(isset($customerId)){
                /*- カートから削除 -*/
                 for($i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $itemCodeByPost){
                        unset($_SESSION['cart'][$i]);
                    }
                }
                
                $_SESSION['cart'] = array_merge($_SESSION['cart']); 
            
                try{
                    $favoriteDao->insertIntoFavorite($itemCodeByPost, $customerId);
                    
                } catch(MyPDOException $e){
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    $e->handler($e);
                }
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
                
                $favoriteDao = new FavoriteDao();
                $moveItemCode = $_SESSION['move_fav_item_code'];
                
                /*- カートから削除 -*/
                 for( $i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $moveItemCode){
                        unset($_SESSION['cart'][$i]);
                    }
                }
                
                $_SESSION['cart'] = array_merge($_SESSION['cart']);  
                
                try{
                    $favoriteDao->insertIntoFavorite($moveItemCode, $customerId);
                    $_SESSION['cart_flag'] = NULL;
                    $_SESSION['move_fav_item_code'] = NULL;
                    
                } catch(MyPDOException $e){
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    $e->handler($e);
                }
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
                $itemsDao = new ItemsDao();
                try{
                    $dto = $itemsDao->getItemByItemCodeForPurchase($itemCodeByPost);

                    $item['item_code'] = $dto->getItemCode();
                    $item['item_quantity'] = $itemQuantity;
                    
                    array_push($_SESSION['cart'], $item);
      
                } catch(MyPDOException $e){
                    $e->handler($e);
                    
                }catch(DBParamException $e){
                    $e->handler($e);
                }
            }
             unset($_SESSION['add_cart']);
        }
        
        try{

            $_SESSION['availableForPurchase'] = NULL;
            
            if($_SESSION['cart']){
                for($i=0; $i<count($_SESSION['cart']); $i++){

                    $itemsDao = new ItemsDao(); 
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
            }else{
                $this->availableForPurchase = FALSE;  
            }
            
            if($this->availableForPurchase){
                $_SESSION['availableForPurchase'] = TRUE;
            }
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
    }
    
    public function getAvailableForPurchase(){
        return $this->availableForPurchase;   
    }
}

?>    

   