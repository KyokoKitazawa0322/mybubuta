<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config; 

$adminCustomerOrderDetail = new \Controllers\AdminCustomerOrderDetailAction();
$adminCustomerOrderDetail->execute();
$details = $adminCustomerOrderDetail->getOrderDetail();
$order = $adminCustomerOrderDetail->getOrderHistory();
$customerId = $adminCustomerOrderDetail->getCustomerId();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body class="admin" id="admin_customer_order_detail">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_customers.php">顧客管理画面</a></h2>
                    </div>
                     <div class="main_contents_inner">
                        <a href="/html/admin/admin_customer_order_history.php?customer_id=<?=$customerId?>" class="admin_link">購入履歴一覧に戻る</a>
                        <div class="cart_item_box">
                            <div class="order_detail_box">
                                <dl class="list_order_detail_01">
                                    <dt>ご注文日 :</dt>
                                    <dd><?=Config::h($order->getPurchaseDate());?></dd>
                                    <dt>ご注文番号 :</dt>
                                    <dd><?=Config::h($order->getOrderId());?></dd>
                                    <dt>ご注文金額 :</dt>
                                    <dd>&yen;<?=Config::h(number_format($order->getTotalAmount()))?></dd>
                                    <dt>決済方法 :</dt>
                                    <dd><?=Config::h($order->getPaymentTerm());?></dd>
                                </dl>
                            </div>
                            <div class="order_detail_box">
                                <h3 class="ttl_cmn">配送先住所</h3>
                                <dl class="list_order_detail_02">
                                    <dt>名前 :</dt>
                                    <dd><?=Config::h($order->getDeliveryName());?></dd>
                                    <dt>郵便番号 :</dt>
                                    <dd><?=Config::h($order->getDeliveryPost());?></dd>
                                    <dt>電話番号:</dt>
                                    <dd><?=Config::h($order->getDeliveryTel());?></dd>
                                    <dt>住所 :</dt>
                                    <dd><?=Config::h($order->getDeliveryAddr());?></dd>
                                </dl>
                            </div>
                            <div class="order_detail_box">
                                <h3 class="ttl_cmn last_ttl_cmn">ご注文内容</h3>
                                <div class="shipping_box_wrap">
                                    <?php foreach($details as $detail):?>
                                        <div class="cart_item">
                                            <div class="cart_item_img">
                                                <img src="<?=Config::h($detail->getItemImagePath());?>"/>
                                            </div>
                                            <div class="cart_item_txt">
                                                <h4><?=Config::h($detail->getItemName());?></h4>
                                                <dl class="buy_itemu_menu mod_order_info">
                                                    <dt>価格:</dt>
                                                    <dd>
                                                        &yen;<?=Config::h(number_format($detail->getitemPriceWithTax()));?>(税込)
                                                    </dd>
                                                </dl>
                                                <dl class="buy_item_quantity mod_order_info">
                                                    <dt>数量:</dt>
                                                    <dd>
                                                        <?=Config::h($detail->getItemQuantity());?>個
                                                    </dd>
                                                </dl>
                                                <dl class="mod_order_info mod_order_total">
                                                    <dt>小計:</dt>
                                                    <dd>
                                                        &yen;<?=Config::h(number_format($detail->getTotalAmount()));?>(税込)
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
                                        <dd><?=Config::h($order->getTotalQuantity());?>点</dd>
                                        <dt>商品代金合計(税込)</dt>
                                        <dd>&yen;<?=Config::h(number_format($order->getTotalAmount()));?></dd>
                                        <dt>送料</dt>
                                        <dd>&yen;<?=Config::h($order->getPostage());?></dd>
                                        <dt>内消費税</dt>
                                        <dd>&yen;<?=Config::h(number_format($order->getTax()));?></dd>
                                    </dl>
                                    <div class="payment_total">
                                        <dl class="mod_payment mod_payment_total">
                                            <dt>ご注文合計</dt>
                                            <dd>&yen;<?=Config::h(number_format($order->getTotalAmount() + $order->getPostage()));?></dd>
                                        </dl>
                                    </div>
                                </div>
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