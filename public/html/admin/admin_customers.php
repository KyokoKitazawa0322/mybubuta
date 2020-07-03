<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config;

$AdminCustomers = new \Controllers\AdminCustomersAction();
$AdminCustomers->execute();
$customers = $AdminCustomers->getCustomers();
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
    $('.admin_detail').click(function(){ 
        var customerId = $(this).data("value");
        $('input#customer_id').val(customerId);
        $('form#detailForm').submit();
    });
});
    
// --> 
</script>
</head>
<body class="admin" id="admin_customers">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2>顧客管理画面</h2>
                    </div>
		            <div class="main_contents_inner">
                        <table class="admin_customer_list_wrapper">
                            <tr>
                                <th>顧客ID</th>
                                <th>氏名</th>
                                <th>氏名(カナ)</th>
                                <th>郵便番号</th>
                                <th>住所</th>
                                <th>電話番号</th>
                                <th>メールアドレス</th>
                                <th>登録日<br/><a class="sort" data-value="sortby_insert_date_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="sortby_insert_date_asc">▲</a></th>
                                <th></th>
                            </tr>
                            <?php foreach($customers as $customer): ?>
                                <tr class="admin_customer">
                                    <td class="admin_customer_id"><?=Config::h($customer->getCustomerId());?></td>
                                    <td class="admin_customer_name"><?=Config::h($customer->getFullName());?></td>
                                    <td class="admin_customer_ruby_name"><?=Config::h($customer->getFullRubyName());?></td>
                                    <td class="admin_customer_zipcode"><?=Config::h($customer->getZipCode());?></td>
                                    <td class="admin_customer_address"><?=Config::h($customer->getAddress());?></td>
                                    <td class="admin_customer_tel"><?=Config::h($customer->getTel());?></td>
                                    <td class="admin_customer_mail"><?=Config::h($customer->getMail());?></td>
                                    <td class="admin_item_isnert_date"><?=Config::h($customer->getCustomerInsertDate());?></td>
                                    <td class="admin_button_area">
                                        <input type="button" class="btn_cmn_01 btn_design_02 admin_detail" value="詳細" data-value="<?=Config::h($customer->getCustomerId());?>">
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
        <form method="POST" id="detailForm" action="/html/admin/admin_customer_detail.php">
            <input type="hidden" id="customer_id" name="customer_id" value>
            <input type="hidden" name="cmd" value="detail">
        </form>
    </div>
</body>
</html>