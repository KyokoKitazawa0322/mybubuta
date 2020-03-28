<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();
if(isset($_SESSION['customer_id'])){
    header("Location:mypage.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品詳細｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body id="login">
<div class="wrapper">
    
    <!--　ヘッダー　-->
    <div class="header">
        <div class="header_inner">
            <div class="header_contents">
                <a href="/sample/item_list.php">
                    <img class="main_logo" src="common/img/main_logo.png">
                </a>
                <div class="header_logo_area">
                    <a href="<?=$server?>login.php">
                        <img class="header_logo" src="common/img/header_icon_member.png">
                    </a>
                    <a href="<?=$server?>mypage_favorite.php">
                        <img class="header_logo" src="common/img/header_icon_like.png">
                    </a>
                    <a href="<?=$server?>cart.php">
                        <img class="header_logo" src="common/img/header_icon_cart.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--　ヘッダーここまで　-->

    <div class="container">
        
    <!-- メインコンテンツ -->
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="login_title">
                    <h2>
                        <img class="login_logo" src="common/img/main_contents_title_login.png" alt="ログイン">
                    </h2>
                </div>
                <div class="login_wrapper">
	            <div class="account_wrapper center_line">
					<div class="login_title_wrapper">
						<h3>会員登録をされている方</h3>
					</div>
					<form name="loginForm" action="mypage.php" method="POST">
						<div class="account_field">
							<div class="account-label">
								<label for="mail">メールアドレス</label>
							</div>
							<input class="login_form_input" type="text" placeholder="" name="mail" value="<?php if(isset($_COOKIE['mail'])){echo $_COOKIE['mail'];} ?>">
							
						</div>
						<div class="account_field">
							<div class="account_label">
								<label for="password">パスワード</label>
							</div>
							<div class="login_form_input_wrapper">
								<input class="login_form_input" name="password" type="password" placeholder="" value="<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password'];} ?>">
							</div>
                            <?php if(isset($_SESSION['login_error'])):?>
                                <p class="login_error_txt"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
                            <?php endif; ?>
						</div>
						<div class="login_button_wrapper">
							<input class="btn_cmn_mid btn_design_01" type="submit" value="ログイン">
                            <input type="hidden" name="cmd" value="do_login">
						</div>  
					</form>
				</div>          
                
                <div class="account_wrapper">
					<div class="account_title_wrapper">
						<h3>会員登録をされていない方</h3>
					</div>
                    <div class="new_account_button_wrapper">
                        <input class="btn_cmn_mid btn_design_01" type="button" value="新規会員登録" onClick="location.href='register.php'" />    
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

