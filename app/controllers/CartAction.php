<?php
namespace Controllers;
use \Models\ItemsDto;
use \Models\ItemsDao;
use \Models\MyPageFavoriteDao;
use \Config\Config;
use \Models\OriginalException;
    
class CartAction{
    
    public function execute(){
        
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
            
        $itemCodeByPost = filter_input(INPUT_POST, 'item_code');
        $itemCodeByGet = filter_input(INPUT_GET, 'item_code');
        $itemCount = filter_input(INPUT_POST, 'item_quantity', FILTER_VALIDATE_INT);
        $cmdPost = filter_input(INPUT_POST, 'cmd');
        $cmdGet = filter_input(INPUT_GET, 'cmd');

    
        if(isset($_SESSION['customer_id'])){
            $customerId = $_SESSION['customer_id'];   
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
       　 favorite.phpからカートにいれるボタンがおされたときの処理
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
                    $dto = $itemsDao->findItemByItemCode($itemCodeByFavorite);
                    
                    $item['item_code'] = $dto->getItemCode();
                    $item['item_image'] = $dto->getItemImage();
                    $item['item_name'] = $dto->getItemName();
                    $item['item_price'] = $dto->getItemPrice();
                    $item['item_tax'] = $dto->getItemTax();
                    $item['item_price_with_tax'] = $dto->getItemPriceWithTax();
                    $item['item_quantity'] = 1;
            
                    array_push($_SESSION['cart'], $item);
                
                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
                    
                }catch(OriginalException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
            }
            unset($_SESSION['add_cart_from_fav']);
            unset($_SESSION['item_code']);
        }

        /*====================================================================
       　お気に入りに移動ボタンがおされたとき(ログイン状態/非ログイン状態)
        =====================================================================*/
        
        if($cmdPost == "move_fav" ){
            $favoriteDao = new MyPageFavoriteDao();
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
                    
                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
                
                }catch(OriginalException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
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
                
                $favoriteDao = new MyPageFavoriteDao();
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
                    
                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
            
                }catch(OriginalException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
            }else{
                header('Location:/html/login.php');
            }
        }

        /*====================================================================
      　  item_detail.phpからカートに入れるボタンがおされた時の処理
        =====================================================================*/
         
         if($cmdPost == "add_cart" && isset($_SESSION['add_cart']) && $_SESSION['add_cart'] == 'undone'){
            
            $is_already_exists  = false;
             
            for( $i=0; $i<count($_SESSION['cart']); $i++){
                if( $_SESSION['cart'][$i]['item_code'] == $itemCodeByPost){
                    /*- 追加する商品がカートに既に存在している場合は数量を合算。 -*/  
                    $_SESSION['cart'][$i]['item_quantity'] = $_SESSION['cart'][$i]['item_quantity'] + $itemCount;
                    $is_already_exists = true;
                }
            }
             
             /*- 追加する商品がカートに存在しない場合、カートに新規登録。 -*/    
            if(!$is_already_exists){ 
                $itemsDao = new ItemsDao();
                try{
                    $dto = $itemsDao->findItemByItemCode($itemCodeByPost);

                    $item['item_code'] = $dto->getItemCode();
                    $item['item_quantity'] = $itemCount;
                    $item['item_image'] = $dto->getItemImage();
                    $item['item_name'] = $dto->getItemName();
                    $item['item_price'] = $dto->getItemPrice();
                    $item['item_tax'] = $dto->getItemTax();
                    $item['item_price_with_tax'] = $dto->getItemPriceWithTax();
                    array_push($_SESSION['cart'], $item);
                    
                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
                    
                }catch(OriginalException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                    header('Content-Type: text/plain; charset=UTF-8', true, 400);
                    die('エラー:'.$e->getMessage());
                }
            }
             unset($_SESSION['add_cart']);
        }
    }
}

?>    

   