<?php
namespace Controllers;
use \Models\MyPageFavoriteDao;
use \Models\ItemsDto;

class MyPageFavoriteAction{
    
    private $favoriteDto; 
        
    public function execute(){
        
        try{
            $dao = new MyPageFavoriteDao();
            $customerId = $_SESSION['customer_id'];
            if(isset($_GET['item_code'])){
                $itemCode = $_GET['item_code'];   
            }

            /**---------------------------------------------------
             * 詳細画面で「お気に入り保存」ボタンが押された時に処理を行う
             ----------------------------------------------------*/
            if(isset($_GET["cmd"]) && $_GET["cmd"] == "add_favorite" ){
                //非ログイン状態の場合はフラグをたててログイン画面へ
                if(!isset($customerId)){    
                    $_SESSION['fav_flug']=1;
                    $_SESSION['add_item_code'] = $itemCode;
                    header('Location:/html/login.php');
                    exit();
                }else{
                    $dao->insertIntoFavorite($itemCode, $customerId);
                }
            }
            
            /**---------------------------------------------------
             * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
             ----------------------------------------------------*/
             if(!isset($customerId)){
                header('Location:/html/login.php');
                exit();
             }

            /**---------------------------------------------------
             * 詳細画面で「お気に入り保存」ボタンが押され、その後ログインをはさんだ場合の処理
             ----------------------------------------------------*/
            if(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "1"){
                $dao->insertIntoFavorite($_SESSION['add_item_code'], $customerId);
                unset($_SESSION['fav_flug']);
                unset($_SESSION['add_item_code']);
            }

            /**----------------------------------------------------
              「削除」ボタンが押された時の処理
            ------------------------------------------------------*/
            if(isset($_GET["cmd"]) && $_GET["cmd"] == "del"){
                $dao->deleteFavorite($itemCode, $customerId);
            }

            /**----------------------------------------------------
              一覧表示
            ------------------------------------------------------*/
            $this->favoriteDto = $dao->getFavoriteAll($customerId);
        }catch(\PDOEexception $e){
            die('SQLエラー :'.$e->getMessage());
        }  
    }

    /** @return ItemsDto */
    public function getFavoriteDto(){
        return $this->favoriteDto;   
    }
}
?>    

   