<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config; 

$adminCustomerOrderHistory = new \Controllers\AdminCustomerOrderHistoryAction();
$adminCustomerOrderHistory->execute();
$orders = $adminCustomerOrderHistory->getOrders();
$customerId = $adminCustomerOrderHistory->getCustomerId();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
<!--
$(function(){
    $('.admin_order_detail').click(function(){ 
        var orderId = $(this).data("order");
        $('input#order_id').val(orderId);
        $('form#orderDetailForm').submit();
    });
});
    
// --> 
</script>
</head>
<body class="admin" id="admin_customer_order_history">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_customers.php">顧客管理画面</a></h2>
                    </div>
		            <div class="main_contents_inner">
                        <a href="/html/admin/admin_customer_detail.php?customer_id=<?=$customerId?>" class="admin_link">顧客登録情報へ戻る</a>
                        <?php if($orders):?>
                            <table class="admin_customer_list_wrapper">
                                <caption>購入履歴</caption>
                                <tr>
                                    <th>ご注文日</th>
                                    <th>ご注文番号</th>
                                    <th>合計金額</th>
                                    <th>決済方法</th>
                                    <th></th>
                                </tr>
                                <?php foreach($orders as $order):?>
                                    <tr>
                                        <td class="admin_order_date"><?=$order->getPurchaseDate();?></td>
                                        <td class="admin_order_number"><?=$order->getOrderId();?></td>
                                        <td class="admin_order_amount">&yen;<?=number_format($order->getTotalAmount());?></td>
                                        <td class="admin_order_payment_term"><?=$order->getPaymentTerm();?></td>
                                        <td class="detail_link_wrap">
                                            <input type="button" class="btn_cmn_01 btn_design_02 admin_order_detail" value="詳細" data-order="<?=$order->getOrderId();?>"/>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </table>
                        <?php else:?>
                            <p>購入履歴なし</p>
                        <?php endif;?>
		            </div>
		        </div>
		    </div>
		</div>
		<div id="footer">
		    <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
		</div>
        <form method="GET" id="orderDetailForm" action="/html/admin/admin_customer_order_detail.php">
            <input type="hidden" id="order_id" name="order_id" value>
            <input type="hidden" id="customer_id" name="customer_id" value="<?=$customerId?>">
        </form>
    </div>
</body>
</html>