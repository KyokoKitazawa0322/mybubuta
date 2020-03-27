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
            header("Location:{$server}login.php");
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
            header("Location:{$server}cart.php");
            exit();
        }
        
        //ログイン状態なしでカートからレジに進むボタンをおしたとき
        if(isset($_SESSION['order_flag']) && $_SESSION['order_flag'] == "1"){
            header("Location:{$server}order_confirm.php");
            exit();
        }

        //ログイン状態なしでdetail.phpからお気に入りボタンをおしたとき
        if(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "1"){
            header("Location:{$server}mypage_favorite.php");
            exit();
        }
        header("Location:{$server}mypage.php");
        exit();
        }
    //ログイン失敗
    }else{
        $_SESSION['login_error'] = '登録されていないメールアドレスです。';
        header("Location:{$server}login.php");
        exit();
    }
    $con->close();
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage">
<div class="wrapper">
    
    <!--　ヘッダー　-->
    <div class="header">
        <div class="header_inner">
            <div class="header_contents">
                <a href="item_list.php?cmd=item_list">
                    <img class="main_logo" src="common/img/main_logo.png">
                </a>
                <div class="header_logo_area">
                    <a href="login.php">
                        <img class="header_logo" src="common/img/header_icon_member.png">
                    </a>
                    <a href="mypage_favorite.php">
                        <img class="header_logo" src="common/img/header_icon_like.png">
                    </a>
                    <a href="cart.php">
                        <img class="header_logo" src="common/img/header_icon_cart.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--　ヘッダーここまで　-->
    

    <div class="container">
     <!-- 左メニュー -->
     <?php require_once('mypage_common.php'); ?>
    <!-- メインコンテンツ -->
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>マイページ</h2>    
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
