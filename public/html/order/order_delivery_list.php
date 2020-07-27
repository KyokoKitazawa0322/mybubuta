
<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_start();

use \Config\Config;

$orderDeliveryList = new \Controllers\OrderDeliveryListAction();
$orderDeliveryList->execute();
$customer = $orderDeliveryList->getCustomer();
$deliveries = $orderDeliveryList->getDelivery();
$delId = $orderDeliveryList->getDelId();
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
<script type = "text/javascript">
<!--
    $(function(){
        history.pushState(null, null, null);
        $(window).on("popstate", function (event) {
            window.location.replace('/html/order/order_confirm.php');
        });
    });

    // 住所登録編集(mypage_update.php)
    function updbaseAddr(){
        $("form#base-addr-update").submit();
    } 

    // 住所追加(mypage_delivery_add.php)
    function addAddr(){
        $("form#add_addr").submit();
    } 

    // 住所登録編集(mypage_delivery_add.php)
    function updAddr(addrSeq){
        var seq = addrSeq.getAttribute("data-value");
        document.getElementById("updId").value = seq;
        $("form#exist-addr-update").submit();
    } 

    //削除
    function deleteAddr(addrSeq){
        var seq = addrSeq.getAttribute("data-value");
        document.getElementById("deleteId").value = seq;
        $("form#exist-addr-delete").submit();
    }
// --> 
</script>
</head>

<body class="mypage" id="order_del_list">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="deliver_title">
                    <h2>配送先を選んでください</h2>
                </div>
                <div class="main_contents_inner">
                    <form action="/html/order/order_confirm.php" method="POST">
                        <h3 class="ttl_cmn">お客様会員住所</h3>
                        <div class="mypage_addr_box">
                            <div class="box_info">                           
                                <input name="def_addr" type="radio" id="def_addr" value="customer" <?php $orderDeliveryList->checkCustomer($customer);?>>
                                <label for="def_addr" class="input_radio_addr_01">
                                    <dl class="list_addr_info">
                                        <dt>名前 :</dt>
                                        <dd><?=Config::h($customer->getFullName());?></dd>
                                        <dt>郵便番号 :</dt>
                                        <dd><?=Config::h($customer->getPost());?></dd>
                                        <dt>電話番号 :</dt>
                                        <dd><?=Config::h($customer->getTel());?></dd>
                                        <dt>住所 :</dt>
                                        <dd><?=Config::h($customer->getAddress());?></dd>
                                    </dl>
                                </label>
                            </div>
                            <div class="update_reg_link_wrap">
                                <a href="javascript:void(0)" class="update_reg_link btn_cmn_mid btn_design_02" onclick="updbaseAddr()">会員住所を変更する</a>
                            </div>
                        </div>
                        <div class="add_reg_wrap">
                            <h3 class="ttl_cmn">配送先ご登録住所</h3>
                            <?php if($deliveries):?>
                                <?php $i=0; foreach($deliveries as $delivery): $i++;?>
                                <div class="mypage_addr_box">
                                    <div class="box_info">
                                        <input name="def_addr" type="radio" id="def_addr<?=$i?>" value="->getDeliveryId();?>" <?php $orderDeliveryList->checkDelivery($delivery);?>>
                                        <label for="def_addr<?=$i?>" class="input_radio_addr_01">
                                            <dl class="list_addr_info">
                                                <dt>名前 :</dt>
                                                <dd><?=Config::h($delivery->getFullName());?></dd>
                                                <dt>郵便番号 :</dt>
                                                <dd><?=Config::h($delivery->getPost());?></dd>
                                                <dt>電話番号 :</dt>
                                                <dd><?=Config::h($delivery->getTel());?></dd>
                                                <dt>住所 :</dt>
                                                <dd><?=Config::h($delivery->getAddress());?></dd>
                                            </dl>
                                        </label>
                                    </div>
                                    <div class="update_reg_link_wrap">
                                        <input type="button" class="btn_cmn_mid btn_design_02" value="編集する" onclick="updAddr(this)" data-value="<?=Config::h($delivery->getDeliveryId());?>"> 
                                        <input type="button" onclick="deleteAddr(this)" class="btn_cmn_mid btn_design_03" value="削除" data-value="<?=Config::h($delivery->getDeliveryId());?>"> 
                                    </div>
                                </div>
                                <?php endforeach;?>
                            <?php endif?>
                        </div>
                        <div class="cart_button_area">
                            <input type="submit" class="btn_cmn_l btn_design_01" value="配送先を変更する"/>
                            <input type="hidden" name="cmd" value="del_comp">
                        </div>
                        <div class="add_btn_wrap">
                            <a href="javascript:void(0)" class="btn_cmn_l btn_design_02" onclick="addAddr()">配送先を新しく追加する</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form method="POST" id="base-addr-update" action="/html/mypage/update/mypage_update.php">
        <input type="hidden" name="cmd" value="from_order">
    </form>
    <form method="GET" id="exist-addr-update" action="/html/mypage/delivery/mypage_delivery_entry.php">
        <input type="hidden" name="del_id" id="updId" value>
        <input type="hidden" name="cmd" value="from_order">
    </form>
    <form method="POST" id="add_addr" action="/html/mypage/delivery/mypage_delivery_add.php">
        <input type="hidden" name="cmd" value="from_order">
    </form>
    <form method="POST" id="exist-addr-delete" action="#">
        <input type="hidden" name="del_id" id="deleteId" value>
        <input type="hidden" name="cmd" value="delete">
    </form>
</div>
</body>
</html>