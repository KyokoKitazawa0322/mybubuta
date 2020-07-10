<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config;

$adminCustomerDetail = new \Controllers\AdminCustomerDetailAction();
$adminCustomerDetail->execute();
$customer = $adminCustomerDetail->getCustomer();
$customerDeliveries = $adminCustomerDetail->getCustomerDelivery();
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
    $('#admin_order_history').click(function(){ 
        var customerId = $(this).data("value");
        $('input#customer_id').val(customerId);
        $('form#orderHistoryForm').submit();
    });
});
   
$(function(){
    history.pushState(null, null, null);
    $(window).on("popstate", function (event) {
        window.location.replace('/html/admin/admin_customers.php');
    });
});
// --> 
</script>
</head>
<body class="admin" id="admin_customer_detail">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2>顧客管理画面</h2>
                    </div>
		            <div class="main_contents_inner">
                        <div class="link_wrap">
                            <input type="button" id="admin_order_history" class="btn_cmn_mid btn_design_02" data-value="<?=Config::h($customer->getCustomerId());?>" value="購入履歴へ"/>
                        </div>
                        <?php if($customer->getDeliveryFlag()):?>
                            <span class="default_info">いつもの配送先</span>
                        <?php endif;?>
                        <table class="admin_customer_list_wrapper">
                            <caption>基本情報</caption>
                            <tr>
                                <th>顧客ID</th>
                                <td class="admin_customer_id"><?=Config::h($customer->getCustomerId());?></td>
                            </tr>
                            <tr>
                                <th>氏名</th>
                                <td class="admin_customer_name"><?=Config::h($customer->getFullName());?></td>
                            </tr>
                            <tr>
                                <th>氏名(カナ)</th>
                                <td class="admin_customer_ruby_name"><?=Config::h($customer->getFullRubyName());?></td>
                            </tr>
                            <tr>
                                <th>郵便番号</th>
                                <td class="admin_customer_zipcode"><?=Config::h($customer->getZipCode());?></td>
                            </tr>
                            <tr>
                                <th>住所</th>
                                <td class="admin_customer_address"><?=Config::h($customer->getAddress());?></td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td class="admin_customer_tel"><?=Config::h($customer->getTel());?></td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td class="admin_customer_mail"><?=Config::h($customer->getMail());?></td>
                            </tr>
                            <tr>
                                <th>登録日</th>
                                <td class="admin_item_isnert_date"><?=Config::h($customer->getCustomerInsertDate());?></td>
                            </tr>
                        </table>
                        <?php if($customerDeliveries):?>
                            <?php foreach($customerDeliveries as $customerDelivery):?>
                                <?php $i=0; $i++;?>
                                <table class="admin_customer_list_wrapper">
                                    <?php if($customerDelivery->getDeliveryFlag()):?>
                                        <span class="default_info">いつもの配送先</span>
                                    <?php endif;?>
                                    <caption>配送先情報-(<?=$i?>)</caption>
                                    <tr>
                                        <th>配送先ID</th>
                                        <td class="admin_customer_id"><?=Config::h($customerDelivery->getDeliveryId());?></td>
                                    </tr>
                                    <tr>
                                        <th>氏名</th>
                                        <td class="admin_customer_name"><?=Config::h($customerDelivery->getFullName());?></td>
                                    </tr>
                                    <tr>
                                        <th>氏名(カナ)</th>
                                        <td class="admin_customer_ruby_name"><?=Config::h($customerDelivery->getFullRubyName());?></td>
                                    </tr>
                                    <tr>
                                        <th>郵便番号</th>
                                        <td class="admin_customer_zipcode"><?=Config::h($customerDelivery->getZipCode());?></td>
                                    </tr>
                                    <tr>
                                        <th>住所</th>
                                        <td class="admin_customer_address"><?=Config::h($customerDelivery->getAddress());?></td>
                                    </tr>
                                    <tr>
                                        <th>電話番号</th>
                                        <td class="admin_customer_tel"><?=Config::h($customerDelivery->getTel());?></td>
                                    </tr>
                                    <tr>
                                        <th>登録日</th>
                                        <td class="admin_item_isnert_date"><?=Config::h($customerDelivery->getDeliveryInsertDate());?></td>
                                    </tr>
                                </table>
                            <?php endforeach;?>
                        <?php else:?>
                            <p>配送先情報なし</p>
                        <?php endif;?>
		            </div>
		        </div>
		    </div>
		</div>
		<div id="footer">
		    <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
		</div>
        <form method="POST" id="orderHistoryForm" action="/html/admin/admin_customer_order_history.php">
            <input type="hidden" id="customer_id" name="customer_id" value>
            <input type="hidden" name="cmd" value="order_history">
        </form>
    </div>
</body>
</html>