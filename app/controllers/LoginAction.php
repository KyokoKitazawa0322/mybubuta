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
    
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['customer_id']があればマイページへリダイレクト
        =====================================================================*/
        if(isset($_SESSION['customer_id'])){
            header("Location:/html/mypage/mypage.php");
            exit();
        }
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        /*====================================================================
        　ログイン認証
        =====================================================================*/
        
        if($cmd == "do_login"){
            unset($_SESSION['login_error']);
            $mail = filter_input(INPUT_POST, 'mail');
            $password = filter_input(INPUT_POST, 'password');
            
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $customerDao = new CustomerDao($pdo);
                
            }catch(DBConnectionException $e){
                $e->handler($e);   
            }
            
            try{
                $customer = $customerDao->getCustomerByMail($mail);
            } catch(MyPDOException $e){
                $e->handler($e);
            }
            
            if(!$customer){ 
                /*——————————————————————————————————————————————————————————————
                　ログイン失敗した際の処理(メールアドレスの登録なし)
                 ————————————————————————————————————————————————————————————————*/  
                $_SESSION['login_error'] = 'mail_error';
            }else{
                $hash_pass = $customer->getHashPassWord();
                /*——————————————————————————————————————————————————————————————
                　ログイン失敗した際の処理(メールアドレスの登録はあるがパスワード不一致)
                 ————————————————————————————————————————————————————————————————*/  
                if(!password_verify($password, $hash_pass)){
                    $_SESSION['login_error'] = 'pass_error';
              
                }else{    
                    /*——————————————————————————————————————————————————————————————
                  　  ログイン成功した際の処理
                    ————————————————————————————————————————————————————————————————*/  
                    session_regenerate_id(true);
                    unset($_SESSION['login_error']);
                    $_SESSION['customer_id'] = $customer->getCustomerId();
                    setcookie('mail','',time()-3600,'/');
                    setcookie('password','',time()-3600,'/');
                    setcookie('mail',$mail,time()+60*60*24*7);
                    setcookie('password',$password,time()+60*60*24*7);

                    /*——————————————————————————————————————————————————————————————
                 　   (1)非ログイン状態でカートから「お気に入りに移動」ボタンをおした後ログイン 
                    ————————————————————————————————————————————————————————————————*/
                    if(isset($_SESSION['cart_flag']) && $_SESSION['cart_flag'] == "is"){
                        /*- カート画面へもどす -*/
                        header("Location:/html/cart.php");
                        exit();
                    }
                    
                    /*——————————————————————————————————————————————————————————————
                   　 (2)非ログイン状態でカートから「レジに進む」ボタンをおした後ログイン
                    ————————————————————————————————————————————————————————————————*/
                    elseif(isset($_SESSION['order_flag']) && $_SESSION['order_flag'] == "is"){
                        /*-購入確認画面へ移動 -*/
                        unset($_SESSION['order_flag']);
                        header("Location:/html/order/order_confirm.php");
                        exit();
                    }

                    /*——————————————————————————————————————————————————————————————
                     (3)非ログイン状態でdetail.phpからお気に入りボタンをおした後ログイン
                    ————————————————————————————————————————————————————————————————*/
                    elseif(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "is"){
                       /*- お気に入り画面へリダイレクト -*/
                        header("Location:/html/mypage/mypage_favorite.php");
                        exit();
                    }
                    
                    /*——————————————————————————————————————————————————————————————
                        それ以外(ログインアイコンからのログイン)はmypage.phpへ移動
                    ————————————————————————————————————————————————————————————————*/     
                    else{
                        header("Location:/html/mypage/mypage.php");
                        exit();
                    }
                }
            }
        }
    }
    
    public function echoMail(){
        if(isset($_POST['mail'])){
            echo $_POST['mail'];
        }elseif(isset($_COOKIE['mail'])){
            echo $_COOKIE['mail'];
        }else{
            echo "hanako@yahoo.co.jp";   
        }
    }
    
    public function echoPassword(){
        if(isset($_POST['password'])){
            echo $_POST['password'];
        }elseif(isset($_COOKIE['password'])){
            echo $_COOKIE['password'];
        }else{
            echo "hanako875";   
        }
    }
}
?>