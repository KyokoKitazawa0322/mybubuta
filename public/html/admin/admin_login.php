<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_start();

use \Config\Config;
$adminLogin = new \Controllers\AdminLoginAction();
$adminLogin->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body class="admin" id="admin_login">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2>管理者ログイン</h2>
                    </div>
		            <div class="main_contents_inner">
                        <form name="loginForm" action="#" method="POST">
                            <div class="account_field">
                                <div class="account-label">
                                    <label for="admin_id">ID</label>
                                </div>
                                <input class="login_form_input" type="text" placeholder="" name="admin_id" value="<?=Config::h($adminLogin->echoID())?>">

                            </div>
                            <div class="account_field">
                                <div class="account_label">
                                    <label for="admin_password">パスワード</label>
                                </div>
                                <div class="login_form_input_wrapper">
                                    <input class="login_form_input" name="admin_password" type="password" placeholder="" value="<?=Config::h($adminLogin->echoPassword())?>">
                                </div>
                                <?php if($adminLogin->checkLoginError()):?>
                                    <p class="login_error_txt">ログインできませんでした。ID、パスワードをご確認ください。</p>
                                <?php endif;?>
                            </div>
                            <div class="login_button_wrapper">
                                <input class="btn_cmn_mid btn_design_01" type="submit" value="ログイン">
                                <input type="hidden" name="cmd" value="admin_do_login">
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