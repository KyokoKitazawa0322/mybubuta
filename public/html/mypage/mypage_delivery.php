<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

$myPageDelivery = new \Controllers\MyPageDeliveryAction();
$myPageDelivery->execute();
$customerDto = $myPageDelivery->getCustomerDto();
$deliveryDto = $myPageDelivery->getDeliveryDto();
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
<script type="text/javascript">
<!--
    //いつもの配送先を設定
$(function(){
$('input[name="def_addr"]').click(function(){
	var seq = $(this).data("value");
	$("#updId").val(seq);
	$("form#addr-update").submit();
});
});
    
$(function(){
$('form#addr-update').submit(function(){
    var scroll_top = $(window).scrollTop();  
    $('input.st',this).prop('value',scroll_top); 
});
});

window.onload = function(){
$(window).scrollTop(<?php echo @$_REQUEST['scroll_top']; ?>);
}
    	// 住所登録編集
function updAddr(addrSeq){
	var seq = addrSeq.getAttribute("data-value");
	document.getElementById("updId02").value = seq;
	$("form#exist_addr_update").submit();
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

<body class="mypage" id="mypage_deliver">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="deliver_title">
                    <h2>配送先の登録・確認</h2>
                </div>
                <div class="main_contents_inner">
                    <h3 class="ttl_cmn">お客様会員住所</h3>
                    <div class="mypage_addr_box">
                        <div class="box_info">
                            <input name="def_addr" type="radio" id="def_addr" data-value="def" <?php if($customerDto->getDelFlag() == 0){ echo 'checked="checked"';}?>>
                            <label for="def_addr" class="input_radio_addr_01">
                                <span class="txt_label">いつもの配送先</span>
                            </label>
                            <dl class="list_addr_info">
                                <dt>名前 :</dt>
                                <dd><?= $customerDto->getFullName();?></dd>
                                <dt>郵便番号 :</dt>
                                <dd><?= $customerDto->getPost();?></dd>
                                <dt>電話番号 :</dt>
                                <dd><?= $customerDto->getTel();?></dd>
                                <dt>住所 :</dt>
                                <dd><?= $customerDto->getAddress();?></dd>
                            </dl>
                        </div>
                        <div class="update_reg_link_wrap">
                            <a href="/html/mypage/mypage_update.php" class="btn_cmn_mid btn_design_02">会員住所を変更する</a>
                        </div>
                    </div>
                    <div class="add_reg_wrap">
                        <h3 class="ttl_cmn">配送先ご登録住所</h3>   
                        <?php if($deliveryDto): $i=0;?>
                            <?php foreach($deliveryDto as $delivery): $i++;?>
                                <div class="mypage_addr_box">
                                    <div class="box_info">
                                        <input name="def_addr" type="radio" id="reg_addr<?= $i?>" data-value="<?= $delivery->getDeliveryId();?>" <?php if($delivery->getDelFlag() == 0){ echo 'checked="checked"';}?>>
                                        <label for= "reg_addr<?= $i?>" class="input_radio_addr_01">
                                            <span class="txt_label">いつもの配送先</span>
                                        </label>
                                        <dl class="list_addr_info">
                                            <dt>名前 :</dt>
                                            <dd><?= $delivery->getFullName();?></dd>
                                            <dt>郵便番号 :</dt>
                                            <dd><?= $delivery->getPost();?></dd>
                                            <dt>電話番号 :</dt>
                                            <dd><?= $delivery->getTel();?></dd>
                                            <dt>住所 :</dt>
                                            <dd><?= $delivery->getAddress();?></dd>
                                        </dl>
                                    </div>
                                    <div class="update_reg_link_wrap">   
                                        <input type="button" class="btn_cmn_mid btn_design_02" value="編集する" data-value="<?= $delivery->getDeliveryId();?>" onclick="updAddr(this)"> 
                                        <input type="button" data-value="<?= $delivery->getDeliveryId();?>" class="btn_cmn_mid btn_cmn_01 btn_design_03" value="削除" onclick="deleteAddr(this)"> 
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php else:?>
                            <div class="txt_wrapper">
                                <p>登録はありません。</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="add_reg_link_wrap">
                        <a href="/html/mypage/mypage_delivery_add.php" class="add_reg_link btn_cmn_l btn_design_01">配送先を新しく追加する</a>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once(__DIR__.'/mypage_common.php');?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form method="POST" id="addr-update" action="#">
        <input type="hidden" name="del_id" id="updId" value>
        <input type="hidden" name="set" value="">
        <input type="hidden" name="scroll_top" value="" class="st">
    </form>
    <form method="POST" id="exist_addr_update" action="mypage_delivery_entry.php">
        <input type="hidden" name="del_update" value="">
        <input type="hidden" name="del_id" id="updId02" value>
    </form>
    <form method="POST" id="exist-addr-delete" action="#">
        <input type="hidden" name="del_id" id="deleteId" value>
        <input type="hidden" name="del_item" value="">
    </form>
</div>
</body>
</html>