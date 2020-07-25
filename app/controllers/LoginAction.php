<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class LoginAction{
    
    private $customer;
    
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['customer_id']があればマイページへリダイレクト
        =====================================================================*/
        if(isset($_SESSION['customer_id'])){
            header("Location:/html/mypage/mypage.php");
            exit();
        }
        
        $cmd = Config::getPOST("cmd");

        /*====================================================================
        　ログイン認証
        =====================================================================*/
        
        if($cmd == "do_login"){
            
            unset($_SESSION['login_error']);
            $mail = Config::getPOST('mail');
            $password = Config::getPOST('password');
            
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $customerDao = new CustomerDao($pdo);
                $customer = $customerDao->getCustomerByMail($mail);
                $this->customer = $customer;
                
            }catch(DBConnectionException $e){
                $e->handler($e);   
                
            } catch(MyPDOException $e){
                $e->handler($e);
            }
            
            /*——————————————————————————————————————————————————————————————
             ログイン失敗した際の処理(メールアドレスの登録なし)
             ————————————————————————————————————————————————————————————————*/  
            if(!$customer){ 
                $_SESSION['login_error'] = 'mail_error';
                
            }else{
                $hash_pass = $customer->getHashPassWord();
                /*——————————————————————————————————————————————————————————————
                　ログイン失敗した際の処理(メールアドレスの登録はあるがパスワード不一致)
                 ————————————————————————————————————————————————————————————————*/  
                if(!password_verify($password, $hash_pass)){
                    $_SESSION['login_error'] = 'pass_error';
              
                /*——————————————————————————————————————————————————————————————
                 ログイン成功した際の処理
                ————————————————————————————————————————————————————————————————*/  
                }else{    
                    
                    session_regenerate_id(true);
                    unset($_SESSION['login_error']);
                    
                    $_SESSION['customer_id'] = $customer->getCustomerId();
                    
                    setcookie('mail','',time()-3600,'/');
                    setcookie('password','',time()-3600,'/');
                    setcookie('mail',$mail,time()+60*60*24*7);
                    setcookie('password',$password,time()+60*60*24*7);
                        
                    /*——————————————————————————————————————————————————————————————
                        ログインアイコンからのログインはmypage.phpへ移動
                    ————————————————————————————————————————————————————————————————*/  
                    if(!isset($_SESSION['track_for_login'])){
                        
                        $this->unsetSession();
                        header("Location:/html/mypage/mypage.php");
                        exit();
                        
                    }else{

                        $from = $_SESSION['track_for_login']['from'];
                        switch($from){
                            /*——————————————————————————————————————————————————————————————
                                (1)非ログイン状態でcart.phpから「お気に入りに移動」ボタンをおした後ログイン 
                            ————————————————————————————————————————————————————————————————*/
                            case "cart":       
                                /*- カート画面へもどす -*/
                                $this->unsetSession();
                                header("Location:/html/cart.php");
                                exit();

                            /*——————————————————————————————————————————————————————————————
                             (2)非ログイン状態でcart.phpから「レジに進む」ボタンをおした後ログイン
                            ————————————————————————————————————————————————————————————————*/
                            case "order":
                                /*-購入確認画面へ移動 -*/
                                $this->unsetSessionForOrder();
                                header("Location:/html/order/order_confirm.php");
                                exit();
                                
                            /*——————————————————————————————————————————————————————————————
                             (3)非ログイン状態でitem_detail.phpからお気に入りボタンをおした後ログイン
                            ————————————————————————————————————————————————————————————————*/
                            case "item_detail":
                               /*- お気に入り画面へリダイレクト -*/
                                $this->unsetSession();
                                header("Location:/html/mypage/myp
                                age_favorite.php");
                                exit();
                        }
                    }
                }
            }
        }
    }
    
    public function echoMail(){
        $mail = Config::getPOST('mail');
        $cookieMail = Config::getCookie('mail');
        if($mail){
            echo $mail;
        }elseif($cookieMail){
            echo $cookieMail;
        }else{
            //マイページ公開用
            echo "hanako@yahoo.co.jp";   
        }
    }
    
    public function echoPassword(){
        $password = Config::getPOST('password');
        $cookiePassword = Config::getCookie('password');
        if($password){
            echo $password;
        }elseif($cookiePassword){
            echo $cookiePassword;
        }else{
            //マイページ公開用
            echo "hanako875";   
        }
    }
    
    public function unsetSession(){
        $tmpSessionKeyName = array();
        foreach($_SESSION as $key => $val) {
            if($key !== "cart" && $key !== "search" && $key !== "customer_id"){
                $tmpSessionKeyName[$key] = $key;
            }
        }
        foreach($tmpSessionKeyName as $key){
            unset($_SESSION[$key]);
        }
    }
    
    public function unsetSessionForOrder(){
        $tmpSessionKeyName = array();
        foreach($_SESSION as $key => $val) {
            if($key !== "cart" && $key !== "search" && $key !== "order" && $key !== "customer_id" && $key !== "availableForPurchase"){
                $tmpSessionKeyName[$key] = $key;
            }
        }
        foreach($tmpSessionKeyName as $key){
            unset($_SESSION[$key]);
        }
    }
}
?>