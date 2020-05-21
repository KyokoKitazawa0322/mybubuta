<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

$mypageOrderDetail = new \Controllers\MyPageOrderDetailAction();
$mypageOrderDetail->execute();
$order = $mypageOrderDetail->getOrderHistoryDto();
$details = $mypageOrderDetail->getOrderDetailDto();
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

<body class="mypage" id="history_detail">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>ご注文履歴明細</h2>
                <div class="main_contents_inner">
                    <div class="cart_item_box">
                        <div class="order_detail_box">
                            <dl class="list_order_detail_01">
                                <dt>ご注文日 :</dt>
                                <dd><?= $order->getPurchaseDate();?></dd>
                                <dt>ご注文番号 :</dt>
                                <dd><?= $order->getOrderId();?></dd>
                                <dt>ご注文金額 :</dt>
                                <dd>&yen;<?= number_format($order->getTotalPayment())?></dd>
                                <dt>決済方法 :</dt>
                                <dd><?= $order->getPayment();?></dd>
                            </dl>
                        </div>
                        <div class="order_detail_box">
                            <h3 class="ttl_cmn">配送先住所</h3>
                            <dl class="list_order_detail_02">
                                <dt>名前 :</dt>
                                <dd><?= $order->getDeliveryName(); ?></dd>
                                <dt>郵便番号 :</dt>
                                <dd><?= $order->getDeliveryPost();?></dd>
                                <dt>電話番号:</dt>
                                <dd><?= $order->getDeliveryTel();?></dd>
                                <dt>住所 :</dt>
                                <dd><?= $order->getDeliveryAddr(); ?></dd>
                            </dl>
                        </div>
                        <div class="order_detail_box">
                            <h3 class="ttl_cmn last_ttl_cmn">ご注文内容</h3>
                            <div class="shipping_box_wrap">
                                <?php foreach($details as $detail):?>
                                    <div class="cart_item">
                                        <div class="cart_item_img">
                                            <img src="/img/items/<?= $detail->getItemImage(); ?>"/>
                                        </div>
                                        <div class="cart_item_txt">
                                            <h4><?= $detail->getItemName();?></h4>
                                            <dl class="buy_itemu_menu mod_order_info">
                                                <dt>価格:</dt>
                                                <dd>
                                                    &yen;<?= $detail->getitemPriceWithTax();?>(税込)
                                                </dd>
                                            </dl>
                                            <dl class="buy_item_amount mod_order_info">
                                                <dt>数量:</dt>
                                                <dd>
                                                    <?= $detail->getItemCount(); ?>個
                                                </dd>
                                            </dl>
                                            <dl class="mod_order_info mod_order_total">
                                                <dt>小計:</dt>
                                                <dd>
                                                    &yen;<?= number_format($detail->getTotalPrice());?>(税込)
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                    <div class="box-shipping-sub">
                        <div class="payment_box">
                            <div class="payment_details">
                                <dl class="mod_payment mod_payment_details">
                                    <dt>商品点数</dt>
                                    <dd><?= $order->getTotalAmount();?>点</dd>
                                    <dt>商品代金合計(税込)</dt>
                                    <dd>&yen;<?= number_format($order->getTotalPayment());?></dd>
                                    <dt>送料</dt>
                                    <dd>&yen;<?= $order->getPostage();?></dd>
                                    <dt>内消費税</dt>
                                    <dd>&yen;<?= number_format($order->getTax());?></dd>
                                </dl>
                                <div class="payment_total">
                                    <dl class="mod_payment mod_payment_total">
                                        <dt>ご注文合計</dt>
                                        <dd>&yen;<?= number_format($order->getTotalPayment() + $order->getPostage());?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="btn_link_wrap">
                            <a href="/html/mypage/mypage_order_history.php" class="btn_cmn_01 btn_design_03">ご注文履歴に戻る</a>
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

