<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;
use \Models\CsrfValidator;

$orderConfirm = new \Controllers\OrderConfirmAction();
$orderConfirm->execute();
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
<body id="order_confirm">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="cart_title">
                    <h2>
                        <img class="product_logo" src="/img/main_contents_title_cart.png" alt="カートの中">
                    </h2>
                </div>
                <div class="main_contents_inner">
                    <div class="cart_item_box"> 
                        <?php if(isset($_SESSION['pay_type']) && $_SESSION['pay_type'] == "none"):?>
                            <p class="purchase_error_text">決済方法が未選択です。</p>  
                        <?php endif;?>
                        <?php if(isset($_SESSION['purchase_error'])):?>
                            <p class="purchase_error_text">購入できない商品が含まれてます。</p> 
                        <?php endif;?>
                        <h3 class="ttl_cmn">配送先住所</h3>
                        <div class="shipping_box_wrap">
                            <div class="shipping_box">
                                <dl class="shipping_address">
                                    <dt>名前：</dt>
                                    <dd><?=Config::h($_SESSION['delivery']['name']);?></dd>
                                    <dt>郵便番号：</dt>
                                    <dd><?=Config::h($_SESSION['delivery']['post']);?></dd>
                                    <dt>電話番号：</dt>
                                    <dd><?=Config::h($_SESSION['delivery']['tel']);?></dd>
                                    <dt>住所：</dt>
                                    <dd><?=Config::h($_SESSION['delivery']['address']);?></dd>
                                </dl>
                            </div>
                            <div class="update_link_wrap">
                                <a class="btn_cmn_01 btn_design_03" href="/html/order/order_delivery_list.php">変更</a>
                            </div>
                        </div>
                        <h3 class="ttl_cmn">決済方法</h3>
                        <div class="shipping_box_wrap">
                            <div class="shipping_box">
                            <?php if(isset($_SESSION['pay_type'])):?>
                                <?php if($_SESSION['pay_type'] == "1"):?>
                                    <h4>クレジットカード</h4> 
                                <?php elseif($_SESSION['pay_type'] == "2"):?>
                                    <h4>代引き</h4>
                                    <p>※代引き手数料：210円</p>
                                <?php elseif($_SESSION['pay_type'] == "3"):?>
                                    <h4>銀行振込</h4>
                                    <p>銀行振込手数料はお客様ご負担となります。</p>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if(!isset($_SESSION['pay_type']) || $_SESSION['pay_type'] == "none"):?>
                                    <h4>決済方法を選択してください。</h4>
                            <?php endif;?>
                            </div>
                            <div class="update_link_wrap">
                                <a class="btn_cmn_01 btn_design_03" href="/html/order/order_pay_list.php">変更</a>
                            </div>
                        </div>
                        <h3 class="ttl_cmn last_ttl_cmn">ご注文内容</h3>
                        <div class="shipping_box_wrap">                        
                        <?php
                            foreach($_SESSION["cart"] as $cart) {
                            $item_total_price = $cart['item_price_with_tax'] * $cart['item_quantity'];
                        ?>
                            <div class="cart_item">
                                <?php if($cart["item_status"]=="2"):?>
                                    <p class="status_text">この商品は現在、入荷待ちです。</p>
                                <?php elseif($cart["item_status"]=="3"):?>
                                    <p class="status_text">この商品は販売終了しました。</p>
                                <?php elseif($cart["item_status"]=="4"):?>
                                    <p class="status_text">この商品は現在、一時掲載を停止してます。</p>
                                <?php elseif($cart["item_status"]=="5"):?>
                                    <p class="status_text">この商品は現在、品切れ中です(入荷未定)。</p>
                                <?php endif;?>
                                <div class="cart_item_img">
                                    <a href="/html/item_detail.php?item_code=<?=Config::h($cart['item_code'])?>">
                                        <img src="<?=Config::h($cart["item_image_path"]);?>"/>
                                    </a>
                                </div>
                                <div class="cart_item_txt">
                                    <h4><?=$cart["item_name"];?></h4>
                                    <dl class="buy_itemu_menu mod_order_info">
                                        <dt>価格:</dt>
                                        <dd>
                                            &yen;<?=Config::h(number_format($cart["item_price_with_tax"]));?>(税込)
                                        </dd>
                                    </dl>
                                    <dl class="buy_item_quantity mod_order_info">
                                        <dt>数量:</dt>
                                        <dd>
                                            <?=$cart['item_quantity'];?>個
                                        </dd>
                                    </dl>
                                    <dl class="mod_order_info mod_order_total">
                                        <dt>小計:</dt>
                                        <dd>
                                            &yen;<?=Config::h(number_format($item_total_price));?>(税込)
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        <?php }?>                                    
                        </div>
                    </div>
                    <div class="box-shipping-sub">
                        <div class="payment_box">
                            <div class="payment_details">
                                <dl class="mod_payment mod_payment_details">
                                    <dt>商品点数</dt>
                                    <dd><?=Config::h($_SESSION['order']['total_quantity']);?>点</dd>
                                    <dt>商品代金合計(税込)</dt>
                                    <dd>&yen;<?=Config::h(number_format($_SESSION['order']['total_amount']));?></dd>
                                    <dt>送料</dt>
                                    <dd>&yen;<?=Config::h($_SESSION['order']['postage']);?></dd>
                                    <dt>内消費税</dt>
                                    <dd>&yen;<?=Config::h(number_format($_SESSION['order']['tax']));?></dd>
                                </dl>
                                <div class="payment_total">
                                    <dl class="mod_payment mod_payment_total">
                                        <dt>ご注文合計</dt>
                                        <dd>&yen;<?=Config::h(number_format($_SESSION['order']['total_amount']+$_SESSION['order']['postage']));?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="cart_button_area">
                        <?php if(!isset($_SESSION['purchase_error'])):?>
                            <form action="/html/order/order_complete.php" method="POST">
                                <input type="submit" class="btn_cmn_l btn_design_01" value="注文を確定する"/>
                                <input type="hidden" name="token_order_complete" value="<?=CsrfValidator::maketoken("token_order_complete")?>">
                            </form>
                        <?php else:?>
                            <form action="/html/cart.php" method="POST">
                                <input type="submit" class="btn_cmn_l btn_design_01" value="カートに戻る"/>
                                <input type="hidden" name="cmd" value="back_to_cart"/>
                            </form>
                        <?php endif;?>
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