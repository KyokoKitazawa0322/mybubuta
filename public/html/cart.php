<?php
require_once (__DIR__ ."/../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

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
        var quantity = [];
        var tax = [];
        for(var i = 0; i < $(".buy_itemu_menu").length; i++){
            var item_price = $(".buy_itemu_menu").eq(i).data("price");
            var item_tax = $(".buy_itemu_menu").eq(i).data("tax");
            var item_select = $(".buy_itemu_menu").eq(i).next(".select_wrap").children("select").find("option:selected").data("num");
            //商品毎合計金額
            var item_total = '\xA5'+ separate(item_price * item_select) + "(税込)"; 
            $(".item_price").eq(i).html(item_total);
            //全商品分加算(合計金額/消費税額/商品点数) 
            price.push(item_price * item_select);
            quantity.push(item_select);
            tax.push(item_tax * item_select);
        } 
        
        //合計金額
        var totalAmount = 0;
        for(var i = 0; i < price.length; i++){
            totalAmount += price[i];
        }
        //合計点数
        var totalQuantity = 0;
        for(var i = 0; i < quantity.length; i++){
            totalQuantity += quantity[i];
        }
        //合計消費税
        var totalTax = 0;
        for(var i = 0; i < tax.length; i++){
            totalTax += tax[i];
        }
        var postageFreePrice = <?= Config::POSTAGEFREEPRICE?>;
        var message = document.getElementById("postage_message");
        if(totalAmount >= postageFreePrice){
            var postage = 0;
            var postageDis = '\xA5' + 0;
            message.style.display = 'none';
        }else{
            var postage = <?= Config::POSTAGE?>;
            var postageDis = '\xA5' + <?= Config::POSTAGE?>;
            var difference = postageFreePrice - totalAmount;
            message.style.display = 'block';
            message.innerHTML = "あと"+difference+"円のご購入で送料無料";
        }
        
        var totalQuantityDis = totalQuantity+"点";
        var totalTaxDis = '\xA5' + separate(totalTax);
        var taxIncludeTotalDis ='\xA5'+ separate(totalAmount); 
        var totalAmountDis = '\xA5'+ separate(totalAmount + postage);

        $("#total_quantity").html(totalQuantityDis);
        $("#total_tax").html(totalTaxDis);
        $("#total_amount").html(taxIncludeTotalDis);
        $("#postage").html(postageDis);
        $("#total_price").html(totalAmountDis);

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
                        <?php $var = 0; $total_amount = 0; $total_quantity = 0; $total_tax = 0;?>
                        <?php foreach($_SESSION["cart"] as $cart):?>
                            <?php 
                                $var++;
                                $item_total_amount = $cart['item_price_with_tax']*$cart['item_quantity'];
                                $total_amount += $cart['item_price_with_tax']*$cart['item_quantity'];
                                $total_tax += $cart['item_tax']*$cart['item_quantity'];
                                $total_quantity += $cart['item_quantity']; 
                            ?>
                            <div class="cart_item">
                                <div class="cart_item_img">
                                    <a href="/html/item_detail.php?item_code=<?=$cart["item_code"]?>">
                                        <img src="/img/items/<?= $cart["item_image"] ?>"/>
                                    </a>
                                </div>
                                <div class="cart_item_txt">
                                    <p><a class="product_link" href="/html/item_detail.php?item_code=<?= $cart["item_code"]; ?>"><?=$cart["item_name"]; ?></a></p>
                                    <dl class="buy_itemu_menu mod_order_info" data-price="<?= $cart['item_price_with_tax']?>" data-tax="<?= $cart['item_tax']?>">
                                        <dt>価格:</dt>
                                        <dd>&yen;<?= number_format($cart["item_price_with_tax"]); ?>(税込)</dd>
                                    </dl>
                                    <div class="select_wrap">
                                        <select name="cart<?= $var?>" class="item_quantity">
                                        <?php for($i=1; $i<=10; $i++):?>
                                            <?php if($cart['item_quantity'] == $i):?>
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
                                        <dd class="item_price">&yen;<?= number_format($item_total_amount)?>(税込)</dd>
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
                                <p id="postage_message">
                                   <?php if($total_amount <= Config::POSTAGEFREEPRICE){echo "あと".(Config::POSTAGEFREEPRICE - $total_amount)."円のご購入で送料無料";}?>
                                </p>
                                <div class="payment_details">
                                    <dl class="mod_payment mod_payment_details">
                                        <dt>商品点数</dt>
                                        <dd id="total_quantity"><?= $total_quantity?>点</dd>
                                        <dt>商品代金合計<span>(税込)</span></dt>
                                        <dd id="total_amount">&yen;<?= number_format($total_amount)?></dd>
                                        <dt>送料</dt>
                                        <dd id="postage">&yen;<?php if($total_amount <= Config::POSTAGEFREEPRICE){echo Config::POSTAGE;}else{echo 0;}?></dd>
                                        <dt>内消費税</dt>
                                        <dd id="total_tax">&yen;<?= number_format($total_tax);?></dd>
                                    </dl>
                                    <dl class="mod_payment mod_payment_total">
                                        <dt>ご注文合計</dt>
                                        <dd id="total_price">&yen;<?php if($total_amount <= Config::POSTAGEFREEPRICE){echo number_format($total_amount + Config::POSTAGE);}else{echo number_format($total_amount);}?></dd>
                                    </dl>
                                </div>              
                            </div>
                            <br/>
                            <div class="cart_button_area">
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

