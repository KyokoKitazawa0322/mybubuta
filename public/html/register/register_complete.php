<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

mb_internal_encoding("utf-8");
$registerComplete = new \Controllers\RegisterCompleteAction();
$registerComplete->execute();
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
<script type="text/javascript">
    <!--
    $(function(){
     history.pushState(null, null, null); //ブラウザバック無効化
     //ブラウザバックボタン押下時
     $(window).on("popstate", function (event) {
      window.location.replace('mypage.php');
     });
    });
    // --> 
</script>
</head>
<body id="register_complete">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="register_title">
                    <h2>
                        <img class="register_logo" src="/img/main_contents_title_register.png" alt="新規会員登録">
                    </h2>
                    <div class="txt_wrapper">
                        <p>会員登録が完了しました。</p>
                        <div class="complete_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="button" value="マイページ" onClick="location.href='/html/mypage/mypage.php'" />
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
     