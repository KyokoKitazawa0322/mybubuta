<?php
require_once (__DIR__ ."/../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$login = new \Controllers\LoginAction();
$login->execute();
header('X-FRAME-OPTIONS: SAMEORIGIN');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>商品詳細｜洋服の通販サイト</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body id="login">
<div class="wrapper">
    <?php require_once(__DIR__.'/common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="login_title">
                    <h2>
                        <img class="login_logo" src="/img/main_contents_title_login.png" alt="ログイン">
                    </h2>
                </div>
                <div class="login_wrapper">
	            <div class="account_wrapper center_line">
					<div class="login_title_wrapper">
						<h3>会員登録をされている方</h3>
					</div>
					<form name="loginForm" action="" method="POST">
						<div class="account_field">
							<div class="account-label">
								<label for="mail">メールアドレス</label>
							</div>
							<input class="login_form_input" type="text" placeholder="" name="mail" value="<?=Config::h($login->echoMail())?>">
							
						</div>
						<div class="account_field">
							<div class="account_label">
								<label for="password">パスワード</label>
							</div>
							<div class="login_form_input_wrapper">
								<input class="login_form_input" name="password" type="password" placeholder="" value="<?=Config::h($login->echoPassword())?>">
							</div>
                            <?php if(isset($_SESSION['login_error'])):?>  
                                <?php if($_SESSION['login_error'] == 'pass_error'):?>
                                    <p class="login_error_txt">ログインできませんでした。メールアドレス、パスワードをご確認ください。</p>
                                <?php else:?>
                                    <p class="login_error_txt">登録されていないメールアドレスです。</p>
                                <?php endif;?>
                            <?php endif;?>
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
                        <input class="btn_cmn_mid btn_design_01" type="button" value="新規会員登録" onClick="location.href='/html/register/register.php'" />    
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

