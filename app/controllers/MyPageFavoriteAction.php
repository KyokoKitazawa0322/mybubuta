<?php
namespace Controllers;
use \Models\MyPageFavoriteDao;
use \Models\ItemsDto;
use \Models\OriginalException;
use \Config\Config;

class MyPageFavoriteAction{
    
    private $favoriteDto; 
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        $itemCode = filter_input(INPUT_POST, 'item_code');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        $mypageFavoriteDao = new MyPageFavoriteDao();
        $customerId = $_SESSION['customer_id'];

        /*========================================================
        item_detail.phpで「お気に入り保存」ボタンが押された時の処理
        =========================================================*/
        
        if($cmd == "add_favorite" ){
            /*- 非ログイン状態の場合はフラグをたててログイン画面へ -*/
            if(!isset($customerId)){    
                $_SESSION['fav_flug'] = "is";
                $_SESSION['add_item_code'] = $itemCode;
                header('Location:/html/login.php');
                exit();
            }else{
                try{
                    $mypageFavoriteDao->insertIntoFavorite($itemCode, $customerId);
                    
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
        }
        
        /*==============================================================
        　ログイン状態の判定(セッション切れの場合はlogin.phpへ)
        ===============================================================*/
        
         if(!isset($customerId)){
            header('Location:/html/login.php');
            exit();
         }

        /*===============================================================
        　item_detail.phpで「お気に入り保存」ボタンが押され、その後ログインをはさんだ場合の処理
        ================================================================*/
        
        if(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "is"){
            $addItemCode = $_SESSION['add_item_code'];
            
            try{
                $mypageFavoriteDao->insertIntoFavorite($addItemCode, $customerId);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');
                
            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
                unset($_SESSION['fav_flug']);
                unset($_SESSION['add_item_code']);
        }

        /*===============================================================
        　「カートにいれる」ボタンがおされたときの処理
        ================================================================*/
        if($cmd == "add_cart"){
            $_SESSION['add_cart_from_fav'] = "undone";
            $_SESSION['item_code'] = $itemCode;
            header('Location:/html/cart.php');
            exit();
        }
        
        /*===============================================================
        　「削除」ボタンがおされたときの処理
        ================================================================*/
        if($cmd == "delete"){            
            try{
                $mypageFavoriteDao->deleteFavorite($itemCode, $customerId);
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

        /*===============================================================
       　 お気に入り商品の一覧を取得
        ================================================================*/
        try{
            $favoriteDto = $mypageFavoriteDao->getFavoriteAll($customerId);
            $this->favoriteDto = $favoriteDto;
            
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');
        }
    }
    
    public function getFavoriteDto(){
        return $this->favoriteDto;   
    }
}
?>    

   