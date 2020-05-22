<?php
namespace Controllers;
use \Models\ItemsDto;
use \Models\ItemsDao;
use \Models\MyPageFavoriteDao;
use \Config\Config;
    
class CartAction{
    
    public function execute(){
        
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
        if(isset($_POST['item_code'])){
            $itemCode = $_POST['item_code'];
        }
        if(isset($_SESSION['customer_id'])){
            $customerId = $_SESSION['customer_id'];   
        }
        
        //「削除」ボタンが押された時の処理
        if(isset($_GET['cmd']) && $_GET['cmd'] == "del"){
            $deleteItemcode = $_GET['item_code'];
            for($i=0; $i<count($_SESSION['cart']); $i++){
                if($_SESSION['cart'][$i]['item_code'] == $deleteItemcode){
                    unset($_SESSION['cart'][$i] );
                }
            }
            $_SESSION['cart'] = array_merge($_SESSION['cart']);
        }

        //favorite.phpからカートにいれるボタンがおされたときの処理
        if(isset($_POST['cmd']) && $_POST['cmd'] == "add_cart_fromFav"){
            $is_already_exists  = 0;
            for($i=0; $i<count($_SESSION['cart']); $i++){
                if( $_SESSION['cart'][$i]['item_code'] == $itemCode){
                    // 追加する商品がカートに既に存在している場合は数量を合算。
                    $_SESSION['cart'][$i]['item_count'] += 1;
                    $is_already_exists = 1;
                }
            }

            //追加する商品がカートに存在しない場合、カートに新規登録。
            if( $is_already_exists == 0 ){
                $itemsDao = new ItemsDao();
                try{
                    $dto = $itemsDao->findItemByItemCode($itemCode);
                }catch(\PDOException $e){
                    die('SQLエラー :'.$e->getMessage());
                }
                if($dto) {
                    $item['item_code'] = $dto->getItemCode();
                    $item['item_image'] = $dto->getItemImage();
                    $item['item_name'] = $dto->getItemName();
                    $item['item_price'] = $dto->getItemPrice();
                    $item['item_tax'] = $dto->getItemTax();
                    $item['item_price_with_tax'] = $dto->getItemPriceWithTax();
                    $item['item_count'] = 1;
                    array_push($_SESSION['cart'], $item);
                }
            }
        }

        //お気に入りに移動ボタンがおされたとき(ログイン状態/非ログイン状態)
        if(isset($_POST['cmd']) && $_POST['cmd'] == "move_fav" ){
            $favoriteDao = new MyPageFavoriteDao();
            if(isset($customerId)){
                //カートから削除
                 for($i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $itemCode){
                        unset( $_SESSION['cart'][$i] );
                    }
                }
                $_SESSION['cart'] = array_merge($_SESSION['cart']); 
                try{
                    $favoriteDao->insertIntoFavorite($itemCode, $customerId);
                }catch(\PDOException $e){
                    die('SQLエラー :'.$e->getMessage());
                }
            }else{
                //ログイン状態がなければlogin.phpへ
                $_SESSION['cart_flag']=1;
                $_SESSION['move_fav_item_code']=$itemCode;
                header('Location:/html/login.php');
                exit();
            }
        }

        //非ログイン状態でお気に入りに移動ボタン->ログイン->リダイレクトでもどったときの処理 
        if(isset($_SESSION['cart_flag']) && $_SESSION['cart_flag'] == "1"){
            $favoriteDao = new MyPageFavoriteDao();
            $moveItemCode = $_SESSION['move_fav_item_code'];
            if(isset($customerId)){
                //カートから削除
                 for( $i=0; $i<count($_SESSION['cart']); $i++ ){
                    if( $_SESSION['cart'][$i]['item_code'] == $moveItemCode){
                        unset( $_SESSION['cart'][$i]);
                    }
                }
                $_SESSION['cart'] = array_merge($_SESSION['cart']);  
                try{
                    $favoriteDao->insertIntoFavorite($moveItemCode, $customerId);
                }catch(\PDOException $e){
                    die('SQLエラー :'.$e->getMessage());
                }
                unset($_SESSION['cart_flag']);
                unset($_SESSION['move_fav_item_code']);
            }else{
                header('Location:/html/login.php');
            }
        }

         //リクエスト cmd の中身が、「add_cart」であった場合の処理。
         if(isset($_POST['cmd']) && $_POST['cmd'] == "add_cart"){
            $itemCount = $_POST['item_count'];
            $is_already_exists  = 0;
            for( $i=0; $i<count($_SESSION['cart']); $i++){
                if( $_SESSION['cart'][$i]['item_code'] == $itemCode){
                    // 追加する商品がカートに既に存在している場合は数量を合算。
                    $_SESSION['cart'][$i]['item_count'] = $_SESSION['cart'][$i]['item_count'] + $itemCount;
                    $is_already_exists = 1;
                }
            }

            // 追加する商品がカートに存在しない場合、カートに新規登録。
            if($is_already_exists == 0 ){ 
                $itemsDao = new ItemsDao();
                try{
                    $dto = $itemsDao->findItemByItemCode($itemCode);
                }catch(\PDOException $e){
                    die('SQLエラー :'.$e->getMessage());
                }

                if($dto) {
                    $item['item_code'] = $dto->getItemCode();
                    $item['item_count'] = $_POST['item_count'];
                    $item['item_image'] = $dto->getItemImage();
                    $item['item_name'] = $dto->getItemName();
                    $item['item_price'] = $dto->getItemPrice();
                    $item['item_tax'] = $dto->getItemTax();
                    $item['item_price_with_tax'] = $dto->getItemPriceWithTax();
                    array_push($_SESSION['cart'], $item);
                }
            }
        }
    }
}

?>    

   