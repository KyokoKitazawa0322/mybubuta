<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

$mypageOrderHistory = new \Controllers\MyPageOrderHistoryAction();
$mypageOrderHistory->execute();
$orders = $mypageOrderHistory->getOrders();
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
<body class="mypage" id="order_history">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>ご注文履歴</h2>
                <div class="main_contents_inner">
                    <?php if($orders): ?>
                        <div class="box_order_history">
                            <div class="box_order_header">
                                <div class="box_col_date">ご注文日</div>
                                <div class="box_col_number">ご注文番号</div>
                                <div class="box_col_price">合計金額</div>
                                <div class="box_col_method">決済方法</div>
                            </div>
                            <div class="box_order_info">
                                <?php foreach($orders as $order):?>
                                    <div class="box_row">
                                        <span class="his_ttl-sp">ご注文日:</span>
                                        <p class="box_col_date"><?= $order->getPurchaseDate();?></p>
                                        <span class="his_ttl-sp">ご注文番号:</span>
                                        <p class="box_col_number"><?= $order->getOrderId(); ?></p>
                                        <span class="his_ttl-sp">合計金額:</span>
                                        <p class="box_col_price">&yen;<?= number_format($order->getTotalAmount());?></p>
                                        <span class="his_ttl-sp">決済方法:</span>
                                        <p class="box_col_method"><?= $order->getPaymentTerm(); ?></p>
                                        <div class="detail_link_wrap">
                                            <form method="POST" action="mypage_order_detail.php">
                                                <input type="submit" class="btn_cmn_01 btn_design_02" value="詳細を見る">
                                                <input type="hidden" name="order_id" value="<?= $order->getOrderId();?>">
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach ;?>
                            </div>
                        </div>
                    <?php else :?>
                    <div class="txt_wrapper">
                        <p class="none_txt">購入履歴はありません。</p>
                    </div>
                    <?php endif; ?>
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

