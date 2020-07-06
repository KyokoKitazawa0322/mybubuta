<?php
require_once (__DIR__ ."/../../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$myPageDeliveryComplete = new \Controllers\MyPageDeliveryCompleteAction();
$myPageDeliveryComplete->execute();
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

<body class="mypage" id="del_comp">
<div class="wrapper">
    <?php require_once(__DIR__.'/../../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>配送先の編集</h2>
                <div class="register_wrapper">
                    <div class="txt_wrapper">
                        <p>配送先の編集が完了しました。</p>
                        
                        <div class="complete_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="button" value="配送先の登録・変更" onClick="location.href='/html/mypage/delivery/mypage_delivery.php'" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php require_once(__DIR__.'/../mypage_common.php');?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved. </p>
    </div>
</div>
</body>
</html>

