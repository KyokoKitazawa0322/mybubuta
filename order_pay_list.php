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
  登録情報があれば取得しvalueで表示
 ---------------------------------------------------------*/

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage" id="order_pay_list">
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
    <!-- メインコンテンツ -->
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="payment_title">
                    <h2>決済方法を選んでください</h2>
                </div>
                <div class="main_contents_inner">
                    <form action="order_confirm.php" method="POST">
                        <ul class="list_cash_opt">
                            <?php if(isset($_SESSION['pay_error'])):?>
                            <p class="error">選択して下さい</p>
                            <?php endif;?>
                            <li>
                                <input class="" id="label_01" type="radio" name="payTypeSelect" value="1" <?php if(isset($_SESSION['pay'])&&$_SESSION['pay'] == "1"){echo "checked";}?>>
                                <label class="" for="label_01">
                                    <div class="select_pay_wrap">
                                        <h3>クレジットカード</h3> 
                                    </div>
                                </label>   
                            </li>
                            <li>
                                <input class="" id="label_02" type="radio" name="payTypeSelect" value="2" <?php if(isset($_SESSION['pay'])&&$_SESSION['pay'] == "2"){echo "checked";}?>>
                                <label class="" for="label_02">
                                    <div class="select_pay_wrap">
                                        <h3>代引き</h3>
                                        <p>※代引き手数料：210円</p>
                                    </div>
                                </label>
                            </li>
                            <li>
                                <input class="" id="label_03" type="radio" name="payTypeSelect" value="3" <?php if(isset($_SESSION['pay'])&&$_SESSION['pay'] == "3"){echo "checked";}?>>
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
