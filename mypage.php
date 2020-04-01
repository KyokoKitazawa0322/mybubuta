<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

/**--------------------------------------------------------
        ログイン処理
----------------------------------------------------------*/

if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_login" ){
    unset($_SESSION['login_error']);
    $sql = "SELECT * FROM customers WHERE mail=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1,$_POST['mail']);
    $stmt->execute();

    if($result = $stmt->fetch()){ 
        //メールの登録はあったがパス不一致
    if(!password_verify($_POST['password'], $result['hash_password'])){
        $_SESSION['login_error'] = 'ログインできませんでした。メールアドレス、パスワードをご確認ください。';
            header("Location:login.php");
        exit();  
    }else{
        //ログイン成功した際の処理    
        session_regenerate_id(true);
        $_SESSION['customer_id'] = $result['customer_id'];
        setcookie('mail','',time()-3600,'/');
        setcookie('password','',time()-3600,'/');
        setcookie('mail',$_POST['mail'],time()+60*60*24*7);
        setcookie('password',$_POST['password'],time()+60*60*24*7);

        //ログイン状態なしでカートからお気に入りに移動ボタンをおしたとき
        if(isset($_SESSION['cart_flag']) && $_SESSION['cart_flag'] == "1"){
            header("Location:cart.php");
            exit();
        }
        
        //ログイン状態なしでカートからレジに進むボタンをおしたとき
        if(isset($_SESSION['order_flag']) && $_SESSION['order_flag'] == "1"){
            header("Location:order_confirm.php");
            exit();
        }

        //ログイン状態なしでdetail.phpからお気に入りボタンをおしたとき
        if(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "1"){
            header("Location:mypage_favorite.php");
            exit();
        }
        header("Location:mypage.php");
        exit();
        }
    //ログイン失敗
    }else{
        $_SESSION['login_error'] = '登録されていないメールアドレスです。';
        header("Location:login.php");
        exit();
    }
    $con->close();
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body class="mypage">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="mypage_title">
                    <h2>マイページ</h2>
                </div>
                <div class="main_contents_inner">
                    <h3 class="ttl_cmn">会員様へのお知らせ</h3>
                </div>
                <div class="txt_wrapper">
                    <p class="none_txt">現在お知らせはありません。</p>
                </div>
            </div>
        </div>
        <?php require_once('mypage_common.php'); ?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
