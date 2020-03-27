<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

/**--------------------------------------------------------
 * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
 ---------------------------------------------------------*/
 if(!isset($_SESSION['customer_id'])){
    header('Location:login.php');
     exit();
}

/**--------------------------------------------------------
   削除ボタンがおされたときの処理
 ---------------------------------------------------------*/

if(isset($_POST['cmd'])&&$_POST['cmd']=="leave"){
    $sql = "SELECT * FROM customers WHERE customer_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['customer_id']);
    $stmt->execute();

    if($result = $stmt->fetch()){ 
        if(!password_verify($_POST['memPwd'], $result['hash_password'])){
            echo 'パスワードが正しくありません。';
        }else{
            $sql = "DELETE from customers where customer_id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindvalue(1, $_SESSION['customer_id']);
            $stmt->execute();
            unset($_SESSION['customer_id']);
            unset($_COOKIE['password']);
            unset($_COOKIE['mail']);
            header('Location:leave_complete.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage" id="leave">
<div class="wrapper">
    
    <!--　ヘッダー　-->
    <div class="header">
        <div class="header_inner">
            <div class="header_contents">
                <a href="item_list.php">
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
                <h2>退会</h2>
                <div class="main_contents_inner">
                    <h3>【確認事項】</h3>
                    <ul class="leave_list">
                        <li class="leave_list_item">下記注意事項を確認・了承してから退会手続きを行って下さい。</li>
                        <li class="leave_list_item">・退会後の会員登録内容の確認はできなくなります。</li>
                        <li class="leave_list_item">・退会した会員アカウントを復活させることはできません。</li>
                    </ul>
                    <h3 class="leave_txt">パスワードを入力し、退会ボタンを押して下さい。</h3>
                    <form action="#" method="POST">
                        <div class="leave_Pwd_wrapper">
                            <input type="password" maxlength="20" class="form_input_item" name="memPwd">
                        </div>
                        <div class="leave_btn_wrap">
                            <input type="submit" class="btn_cmn_l btn_design_01" value="退会する">
                            <input type="hidden" name="cmd" value="leave">
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
