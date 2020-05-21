<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$myPageUpdateConfirm = new \Controllers\MyPageUpdateConfirmAction();
$myPageUpdateConfirm->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body class="mypage update_confirm">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>登録内容の確認・変更</h2>
                <div class="register_wrapper">
                    <div class="register_form">
                    <div class="register_field name_field">
                        <div class="register_form_row">
                            <p class="register_form_title">氏名</p>
                            <p class="name_label">姓:</p>
                            <div class="name_input_wrapper">
                                <p><?= $_SESSION['update']['last_name'];?></p>
                            </div>
                            <p class="name_label">名:</p>
                            <div class="name_input_wrapper">
                                <p><?= $_SESSION['update']['first_name'];?></p>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">フリガナ(カタカナ)</p>
                            <p class="name_label">セイ:</p>
                            <div class="name_input_wrapper">
                                <p><?= $_SESSION['update']['ruby_last_name'];?></p>
                            </div>
                            <p class="name_label">メイ:</p>
                            <div class="name_input_wrapper">
                                <p><?= $_SESSION['update']['ruby_first_name'];?></p>
                            </div>
                        </div>
                    </div>                                         
                    <div class="register_field">
                        <div class="register_form_row">
                            <p class="register_form_title">郵便番号</p>
                            <div class="addr01_input_wrapper">
                                <p><?= $_SESSION['update']['address01'];?></p>
                            </div>
                            <span class="txt_dash">－</span>
                            <div class="addr01_input_wrapper">
                                <p><?= $_SESSION['update']['address02'];?></p>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">都道府県</p>
                            <div class="add_list_wrapper">
                               <p><?= $_SESSION['update']['address03'];?></p>
                            </div>		
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">市区町村</p>
                            <p><?= $_SESSION['update']['address04'];?></p>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">番地</p>
                            <p><?= $_SESSION['update']['address05'];?></p>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">建物名</p>
                            <p><?= $_SESSION['update']['address06'];?></p>
                        </div>
                    </div>                       
                    <div class="register_field tel_field">
                        <div class="register_form_row">
                            <p class="register_form_title">電話番号</p>
                            <p><?= $_SESSION['update']['tel'];?></p>
                        </div>
                    </div>
                    <div class="register_field mail_field">
                        <div class="register_form_row">
                            <p class="register_form_title">メール</p>
                            <p><?= $_SESSION['update']['mail'];?></p>
                        </div>
                    </div>
                    <div class="register_field pass_field">
                        <div class="register_form_row">
                            <p class="register_form_title">パスワード</p>
                            <div class="pass_icon">
                                <p>
                                    <?php if(isset($_SESSION['password_input'])):?>
                                        <?php for($i = 0; $i < strlen($_SESSION['update']['password']); $i++):?> 
                                        ●
                                        <?php endfor;?>
                                    <?php else:?>変更なし
                                    <?php endif;?>
                                </p>
                            </div>
                        </div>
                    </div>
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
     <?php require_once(__DIR__.'/mypage_common.php'); ?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>

