<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_start();

$AdminIndex = new \Controllers\AdminIndexAction();
$AdminIndex->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body class="admin" id="admin_index">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2>管理画面トップ</h2>
                    </div>
		            <div class="main_contents_inner">
                        <div class="admin_index_wrapper">
                            <a href="/html/admin/admin_items.php">商品管理画面へ</a>
                            <a href="/html/admin/admin_notice.php">お知らせ管理画面へ</a>
                            <a href="/html/admin/admin_customers.php">顧客管理画面へ</a>
                            <a href="/html/admin/admin_orders.php">購入履歴管理画面へ</a>
                            <a href="/html/admin/admin_sales.php">売上管理画面へ</a>
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