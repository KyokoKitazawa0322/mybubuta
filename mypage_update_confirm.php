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

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage update_confirm">
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
                <h2>登録内容の確認・変更</h2>
                <div class="register_wrapper">
                    <div class="register_form">
 <!------------------------------------------------------------
                           名前フォーム
 -------------------------------------------------------------->
                    <div class="register_field name_field">
                        <div class="register_form_row">
                            <p class="register_form_title">氏名</p>
                            <p class="name_label">姓:</p>
                            <div class="name_input_wrapper">
                                <p><?php echo $_SESSION['update']['name01'];?></p>
                            </div>
                            <p class="name_label">名:</p>
                            <div class="name_input_wrapper">
                                <p><?php echo $_SESSION['update']['name02'];?></p>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">フリガナ(カタカナ)</p>
                            <p class="name_label">セイ:</p>
                            <div class="name_input_wrapper">
                                <p><?php echo $_SESSION['update']['name03'];?></p>
                            </div>
                            <p class="name_label">メイ:</p>
                            <div class="name_input_wrapper">
                                <p><?php echo $_SESSION['update']['name04'];?></p>
                            </div>
                        </div>
                    </div>
 <!------------------------------------------------------------
                           住所フォーム
 -------------------------------------------------------------->                                              
                    <div class="register_field">
                        <div class="register_form_row">
                            <p class="register_form_title">郵便番号</p>
                            <div class="addr01_input_wrapper">
                                <p><?php echo $_SESSION['update']['add01'];?></p>
                            </div>
                            <span class="txt_dash">－</span>
                            <div class="addr01_input_wrapper">
                                <p><?php echo $_SESSION['update']['add02'];?></p>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">都道府県</p>
                            <div class="add_list_wrapper">
                               <p><?php echo $_SESSION['update']['add03'];?></p>
                            </div>		
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">市区町村</p>
                            <p><?php echo $_SESSION['update']['add04'];?></p>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">番地</p>
                            <p><?php echo $_SESSION['update']['add05'];?></p>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">建物名</p>
                            <p><?php echo $_SESSION['update']['add06'];?></p>
                        </div>
                    </div>                     
 <!------------------------------------------------------------
                           電話フォーム
 -------------------------------------------------------------->   
                    <div class="register_field tel_field">
                        <div class="register_form_row">
                            <p class="register_form_title">電話番号</p>
                            <p><?php echo $_SESSION['update']['tel'];?></p>
                        </div>
                    </div>
 <!------------------------------------------------------------
                           メールフォーム
 -------------------------------------------------------------->
                    <div class="register_field mail_field">
                        <div class="register_form_row">
                            <p class="register_form_title">メール</p>
                            <p><?php echo $_SESSION['update']['mail'];?></p>
                        </div>
                    </div>
 <!------------------------------------------------------------
                           パスワードフォーム
 -------------------------------------------------------------->
                    <div class="register_field pass_field">
                        <div class="register_form_row">
                            <p class="register_form_title">パスワード</p>
                            <div class="pass_icon">
                                <p><?php if(!empty($_POST['password'])){echo $_SESSION['update']['password'];}else{echo "変更なし";} ?></p>
                            </div>
                        </div>
                    </div>
 <!------------------------------------------------------------
                          会員登録ボタン
 -------------------------------------------------------------->
                    <div class="confirm_button_wrapper">
                        <div class="confirm_button_inner">
                            <input class="return_button btn_design_03" name="cmd" type="button" onClick="history.back()" value="前の画面に戻る"/>
                        </div>
                        <form action="mypage_update_complete.php" method="POST">
                            <div class="confirm_button_inner">
                                <input class="register_button btn_design_01" name="cmd" type="submit" value="この内容で登録する"/>
                                <input type="hidden" name="cmd" value="do_register">
                            </div>
                        </form>
                        </div>
                    </div>
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

