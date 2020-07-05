<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_start();

$orderPayList = new \Controllers\OrderPayListAction();
$orderPayList->execute();
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
        history.pushState(null, null, null);
        $(window).on("popstate", function (event) {
            window.location.replace('/html/order/order_confirm.php');
        });
    });
// --> 
</script>
</head>
<body class="mypage" id="order_pay_list">
<div class="wrapper">  
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="payment_title">
                    <h2>決済方法を選んでください</h2>
                </div>
                <div class="main_contents_inner">
                    <form action="/html/order/order_confirm.php" method="POST">
                        <ul class="list_cash_opt">
                            <?php if(isset($_SESSION['pay_error'])):?>
                            <p class="error">選択して下さい</p>
                            <?php endif;?>
                            <li>
                                <input class="" id="label_01" type="radio" name="pay_type" value="1" <?php if(isset($_SESSION['pay_type']) && $_SESSION['pay_type'] == "1"){echo "checked";}?>>
                                <label class="" for="label_01">
                                    <div class="select_pay_wrap">
                                        <h3>クレジットカード</h3> 
                                    </div>
                                </label>   
                            </li>
                            <li>
                                <input class="" id="label_02" type="radio" name="pay_type" value="2" <?php if(isset($_SESSION['pay_type']) && $_SESSION['pay_type'] == "2"){echo "checked";}?>>
                                <label class="" for="label_02">
                                    <div class="select_pay_wrap">
                                        <h3>代引き</h3>
                                        <p>※代引き手数料：210円</p>
                                    </div>
                                </label>
                            </li>
                            <li>
                                <input class="" id="label_03" type="radio" name="pay_type" value="3" <?php if(isset($_SESSION['pay_type']) && $_SESSION['pay_type'] == "3"){echo "checked";}?>>
                                <label class="" for="label_03">
                                    <div class="select_pay_wrap">
                                        <h3>銀行振込</h3>
                                        <p>銀行振込手数料はお客様ご負担となります。</p>
                                    </div>
                                </label>
                            </li>
                        </ul>
                        <div class="cart_button_area">
                            <input type="submit" class="btn_cmn_l btn_design_01" value="決済方法を変更する"/>
                            <input type="hidden" name="cmd" value="pay_comp">
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
