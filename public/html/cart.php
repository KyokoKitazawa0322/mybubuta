<?php
require_once (__DIR__ ."/../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

mb_internal_encoding("utf-8");

use \Config\Config;
$cart = new \Controllers\CartAction();
$cart->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>商品詳細｜洋服の通販サイト</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
<!--

$(function() {
    $("a#move_fav").click(function(){
        var item_num = $(this).data('num');
        $('#move_num').val(item_num);
        $("form#item_code").submit();
    });
});
    
$(function() {
$("select").change(function() {
    var price = [];
    var amount = [];
    for(var i = 0; i < $(".buy_itemu_menu").length; i++){
        var item_price = $(".buy_itemu_menu").eq(i).data("price");
        var item_select = $(".buy_itemu_menu").eq(i).next(".select_wrap").children("select").find("option:selected").data("num");
          price.push(item_price * item_select);
          amount.push(item_select);
    } 
    //合計金額
    var total = 0;
    for(var j = 0; j < price.length; j++){
    total += price[j];
    }
    //合計点数
    var totalAmount = 0;
    for(var j = 0; j < amount.length; j++){
    totalAmount += amount[j];
    }
    
    if(total>=<?= Config::POSTAGEFREEPRICE; ?>) {
    var postage = 0;
    var postageDis = '\xA5' + 0;
    }else{
    var postage = <?= Config::POSTAGE?>;
    var postageDis = '\xA5' + <?= Config::POSTAGE; ?>;
    }
    
    var tax = total * <?= Config::TAXRATE; ?>;
    var taxDis = '\xA5' + separate(tax);
    var taxIncludeTotal = total + tax;
    var taxIncludeTotalDis ='\xA5'+ separate(total+tax); 
    var total_paymentAll = '\xA5'+ separate(total+postage+tax);
    //POST送信用
    $(".total_amount").val(totalAmount);
    $(".tax").val(tax);
    $(".postage").val(postage);
    $(".total_payment").val(taxIncludeTotal);    
    //表示用
    $(".taxDis").val(taxDis);
    $(".total_paymentDis").val(taxIncludeTotalDis);
    $(".postageDis").val(postageDis);
    $(".total_price").val(total_paymentAll);

}); 
});     

    function separate(num){
    num = String(num);
    var len = num.length;
    if(len > 3){
        // 前半を引数に呼び出し + 後半3桁
        return separate(num.substring(0,len-3))+','+num.substring(len-3);
    } else {
        return num;
    }
}
    
$(function() {
    $("select").change(function() {
        for(var i = 0; i < $(".buy_itemu_menu").length; i++){
        var item_price = $(".buy_itemu_menu").eq(i).data("price");
        var item_select = $(".buy_itemu_menu").eq(i).next(".select_wrap").children("select").find("option:selected").data("num");
        var total = item_price * item_select;
        var tax = total * <?= Config::TAXRATE; ?>;
        var item_total = '\xA5'+ separate(item_price * item_select + tax) + "(税込)"; 
        $(".item_price").eq(i).val(item_total);
    };
});
});
// --> 
</script>
    
</head>
<body id="cart">
<div class="wrapper">
    <?php require_once(__DIR__.'/common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="cart_title">
                    <h2>
                        <img class="product_logo" src="/img/main_contents_title_cart.png" alt="カートの中">
                    </h2>
                </div>
                <div class="main_contents_inner">
                    <?php if(empty($_SESSION["cart"])): ?>
                        <div class="txt_wrapper">
                            <p class="none_txt">ショッピングカート内に商品はありません。</p>
                            <div class="back_btn_wrap">
                                <input class="btn_design_02 btn_cmn_l" type="button" value="お買い物を続ける" onclick="history.back()" /> 
                            </div>
                        </div>
                    <?php else :?>
                    <form action="/html/order/order_confirm.php" method="POST">
                    <div class="cart_item_box">                      
                    <?php if(isset($_SESSION["cart"])):?>
                        <?php $var = 0; $total_price = 0; $total_amount = 0; ?>
                        <?php foreach($_SESSION["cart"] as $cart):?>
                            <?php 
                                $var++;
                                $item_total_price = $cart['item_price_with_tax']*$cart['item_count'];
                                $total_price += $cart['item_price_with_tax']*$cart['item_count'];
                                $total_amount += $cart['item_count']; 
                            ?>
                            <div class="cart_item">
                                <div class="cart_item_img">
                                    <a href="/html/item_detail.php?item_code=<?=$cart["item_code"]?>">
                                        <img src="/img/items/<?= $cart["item_image"] ?>"/>
                                    </a>
                                </div>
                                <div class="cart_item_txt">
                                    <p><a class="product_link" href="/html/item_detail.php?item_code=<?= $cart["item_code"]; ?>"><?=$cart["item_name"]; ?></a></p>
                                    <dl class="buy_itemu_menu mod_order_info" data-price="<?= $cart['item_price']?>">
                                        <dt>価格:</dt>
                                        <dd>&yen;<?= number_format($cart["item_price_with_tax"]); ?>(税込)</dd>
                                    </dl>
                                    <div class="select_wrap">
                                        <select name="cart<?= $var?>" class="item_count">
                                        <?php for($i=1; $i<=10; $i++):?>
                                            <?php if($cart['item_count'] == $i):?>
                                                <option data-num="<?= $i?>" value="<?= $i?>" selected><?= $i?></option>
                                            <?php else:?>
                                                <option data-num="<?= $i?>" value="<?= $i?>"><?= $i?></option>
                                            <?php endif;?>
                                        <?php endfor;?>
                                        </select>
                                    </div>
                                    <span>個</span>
                                    <dl class="mod_order_info mod_order_total">
                                        <dt>小計:</dt>
                                        <dd>
                                            <input type="text" class="item_price" value="&yen;<?= number_format($item_total_price)?>(税込)" readonly><span></span>
                                        </dd>
                                    </dl>
                                    <div class="cart_btn_wrap">
                                        <a href="javascript:void(0);" id="move_fav" class="btn_cmn_mid btn_design_02" data-num="<?= $cart["item_code"]; ?>">あとで買う<br/>
                                            <span>(お気に入りへ)</span>
                                        </a>
                                        <a class="btn_cmn_01 btn_design_03" href="/html/cart.php?cmd=del&item_code=<?= $cart["item_code"]; ?>">削除</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                    </div>
                        <div class="box-shipping-sub">
                            <div class="payment_box">
                                <div class="payment_details">
                                    <dl class="mod_payment mod_payment_details">
                                        <dt>商品点数</dt>
                                        <dd><input name="total_amount" class="total_amount" type="text" value="<?= $total_amount?>" readonly>点</dd>
                                        <dt>商品代金合計<span>(税込)</span></dt>
                                        <dd><input class="total_paymentDis" type="text" value="&yen;<?= number_format($total_price)?>" readonly></dd>
                                        <dt>送料</dt>
                                        <dd><input class="postageDis" type="text" value="&yen;<?php if($total_price <= Config::POSTAGEFREEPRICE){echo Config::POSTAGE;}else{echo 0;}?>" readonly></dd>
                                        <dt>内消費税</dt>
                                        <dd><input type="text" class="taxDis" value="&yen;<?= number_format($total_price*Config::TAXRATE)?>" readonly></dd>
                                    </dl>
                                    <div class="payment_total">
                                        <dl class="mod_payment mod_payment_total">
                                            <dt>ご注文合計</dt>
                                            <dd><input class="total_price" type="text" value="&yen;<?php if($total_price <= Config::POSTAGEFREEPRICE){echo number_format($total_price + Config::POSTAGE);}else{echo number_format($total_price);}?>" readonly></dd>
                                        </dl>
                                    </div>
                                </div>              
                            </div>
                            <br/>
                            <div class="cart_button_area">
                                <input type="hidden" name="total_payment" class="total_payment" value="<?= $total_price?>">
                                <input type="hidden" name="postage" class="postage" value="<?php if($total_price <= Config::POSTAGEFREEPRICE){echo Config::POSTAGE;}else{echo 0;}?>">
                                <input type="hidden" name="tax" class="tax" value="<?= $total_price*Config::TAXRATE?>">
                                <input type="submit" class="btn_cmn_full btn_design_01" value="レジに進む" />
                                <input type="hidden" name="cmd" value="order_confirm">
                                <div class="back_button">
                                    <input class="btn_cmn_full btn_design_03" type="button" value="お買い物を続ける" onclick="history.back()" />   
                                </div>
                            </div>
                        </div>   
                    </form>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form action="#" method="POST" id="item_code">
        <input type="hidden" name="cmd" value="move_fav">
        <input type="hidden" name="item_code" id="move_num" value>
    </form>
</div>
</body>
</html>

