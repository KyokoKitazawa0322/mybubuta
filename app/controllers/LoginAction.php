<?php
//削除フラグ
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;

class LoginAction{
    
    public function execute(){
        try{
            $dao = new CustomerDao();

            //セッションがあればマイページへリダイレクト
            if(isset($_SESSION['customer_id'])){
                header("Location:/html/mypage/mypage.php");
            }

            /**--------------------------------------------------------
                    ログイン処理
            ----------------------------------------------------------*/

            if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_login" ){
                $_SESSION['login_error'] = null;
                $customer = $dao->getCustomerByMail($_POST['mail']);
                if($customer){ 
                    $hash_pass = $customer->getHashPassWord($_POST['mail']);
                    //メールの登録はあったがパス不一致
                    if(!password_verify($_POST['password'], $hash_pass)){
                        $_SESSION['login_error'] = 'pass_error';
            /*            header("Location:/html/login.php");
                        exit();  */
                    }else{

                        //ログイン成功した際の処理    

                        session_regenerate_id(true);
                        $_SESSION['customer_id'] = $customer->getCustomerId();
                        setcookie('mail','',time()-3600,'/');
                        setcookie('password','',time()-3600,'/');
                        setcookie('mail',$_POST['mail'],time()+60*60*24*7);
                        setcookie('password',$_POST['password'],time()+60*60*24*7);

                        //①非ログイン状態でカートからお気に入りに移動ボタンをおした後ログイン
                        if(isset($_SESSION['cart_flag']) && $_SESSION['cart_flag'] == "1"){
                            //カート画面へもどす
                            header("Location:/html/cart.php");
                            exit();
                        }

                        //②非ログイン状態でカートからレジに進むボタンをおした後ログイン
                        elseif(isset($_SESSION['order_flag']) && $_SESSION['order_flag'] == "1"){
                            //購入確認画面へ移動
                            header("Location:/html/order/order_confirm.php");
                            exit();
                        }

                        //③非ログイン状態でdetail.phpからお気に入りボタンをおした後ログイン
                        elseif(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "1"){
                           // お気に入り画面へもどす
                            header("Location:/html/mypage/mypage_favorite.php");
                            exit();
                        //それ以外(ログインアイコンからのログイン)はマイページトップへ移動
                        }else{
                            header("Location:/html/mypage/mypage.php");
                            exit();
                        }
                    }
                //ログイン失敗
                }else{
                    $_SESSION['login_error'] = 'mail_error';
            /*        header("Location:/html/login.php");
                    exit();*/
                }
            }
        }catch(\PDOException $e){
            
            die('SQLエラー :'.$e->getMessage());
        }
    }
}


?>    

   