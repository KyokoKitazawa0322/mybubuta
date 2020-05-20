<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

mb_internal_encoding("utf-8");
$myPageLeave = new \Controllers\MyPageLeaveAction();
$myPageLeave->execute();
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

<body class="mypage" id="leave">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
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
    <?php require_once(__DIR__.'/mypage_common.php'); ?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
