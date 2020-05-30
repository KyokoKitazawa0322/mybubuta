<?php
namespace Controllers;
use \Models\MyPageFavoriteDao;
use \Models\ItemsDto;

class MyPageFavoriteAction{
    
    private $favoriteDto; 
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        $itemCode = filter_input(INPUT_GET, 'item_code');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        $dao = new MyPageFavoriteDao();
        $customerId = $_SESSION['customer_id'];

        //詳細画面で「お気に入り保存」ボタンが押された時の処理
        if($cmd == "add_favorite" ){
            //非ログイン状態の場合はフラグをたててログイン画面へ
            if(!isset($customerId)){    
                $_SESSION['fav_flug']=1;
                $_SESSION['add_item_code'] = $itemCode;
                header('Location:/html/login.php');
                exit();
            }else{
                try{
                    $dao->insertIntoFavorite($itemCode, $customerId);
                    
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

         //ログイン状態の判定(セッション切れの場合はlogin.phpへ)
         if(!isset($customerId)){
            header('Location:/html/login.php');
            exit();
         }

        //詳細画面で「お気に入り保存」ボタンが押され、その後ログインをはさんだ場合の処理
        if(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "1"){
            $addItemCode = $_SESSION['add_item_code'];
            
            try{
                $dao->insertIntoFavorite($addItemCode, $customerId);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');
                
            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
                $_SESSION['fav_flug'] = NULL;
                $_SESSION['add_item_code'] = NULL;
        }

        //削除」ボタンが押された時の処理
        if($cmd == "del"){            
            try{
                $dao->deleteFavorite($itemCode, $customerId);
            }catch(\PDOEexception $e){
                die('SQLエラー :'.$e->getMessage());
            }
        }

        //お気に入り商品一覧表示
        try{
            $this->favoriteDto = $dao->getFavoriteAll($customerId);
            
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');
        }
    }
    
    /** @return ItemsDto */
    public function getFavoriteDto(){
        return $this->favoriteDto;   
    }
}
?>    

   