<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config; 

$adminOrders = new \Controllers\AdminOrdersAction();
$adminOrders->execute();
$orders = $adminOrders->getOrders();
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
    $('.sort').click(function(){ 
        var content = $(this).data("value");
        $('input#content').val(content);
        $('form#sortForm').submit();
    });
});
    
$(function(){
    $('.admin_order_detail').click(function(){ 
        var orderId = $(this).data("order");
        $('input#order_id').val(orderId);
        var customerId = $(this).data("customer");
        $('input#customer_id').val(customerId);
        $('form#orderDetailForm').submit();
    });
});
    
// --> 
</script>
</head>
<body class="admin" id="admin_orders">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2>購入履歴管理画面</h2>
                    </div>
		            <div class="main_contents_inner">
                        <table class="admin_orders_list_wrapper">
                            <tr>
                                <th>ご注文日<br/><a class="sort" data-value="sortby_purchase_date_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="sortby_purchase_date_asc">▲</a></th>
                                <th>注文番号</th>
                                <th>顧客ID</th>
                                <th>合計金額(税込)<br/><a class="sort" data-value="sortby_total_amount_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="sortby_total_amount_asc">▲</a></th>
                                <th>合計数量<br/><a class="sort" data-value="sortby_total_quantity_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="sortby_total_quantity_asc">▲</a></th>
                                <th>消費税</th>
                                <th>配送料</th>
                                <th></th>
                            </tr>
                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><?=Config::h($order->getPurchaseDate());?></td>
                                    <td><?=Config::h($order->getOrderId());?></td>
                                    <td><?=Config::h($order->getCustomerId());?></td>
                                    <td>&yen;<?=Config::h(number_format($order->getTotalAmount()));?></td>
                                    <td><?=Config::h($order->getTotalQuantity());?></td>
                                    <td><?=Config::h(number_format($order->getTax()));?></td>
                                    <td><?=Config::h($order->getPostage());?></td>
                                    <td class="detail_link_wrap">
                                        <input type="button" class="btn_cmn_01 btn_design_02 admin_order_detail" value="詳細" data-order="<?=Config::h($order->getOrderId());?>" data-customer="<?=Config::h($order->getCustomerId());?>"/>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
		            </div>
		        </div>
		    </div>
		</div>
		<div id="footer">
		    <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
		</div>
        <form method="POST" id="sortForm" action="#">
            <input type="hidden" id="content" name="content" value>
            <input type="hidden" name="cmd" value="sort">
        </form>
        <form method="GET" id="orderDetailForm" action="/html/admin/admin_order_detail.php">
            <input type="hidden" id="order_id" name="order_id" value>
            <input type="hidden" id="customer_id" name="customer_id" value>
        </form>
    </div>
</body>
</html>