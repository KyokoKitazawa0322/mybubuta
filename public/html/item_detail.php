<?php
require_once (__DIR__ ."/../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

mb_internal_encoding("utf-8");

$itemDetail = new \Controllers\ItemDetailAction();
$itemDetail->execute();
$item = $itemDetail->getItem();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, in+itial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
</head>
<body id="item_detail">
<div class="wrapper">
    <?php require_once(__DIR__.'/common/header_common.php');?>
    <div class="container">
    <?php require_once(__DIR__.'/common/left_pane.php'); ?>
        <div class="main_wrapper">
            <div class="main_contents">  
                <div class="item_detail_title">
                    <h2>
                        <img class="product_logo" src="/img/main_contents_title_detail.png" alt="商品詳細">
                    </h2>
                </div>
                <div class="main_contents_inner">
                    <div class="item_name_wrap">
                        <h3 class="item_name-sp">
                            <?= $item->getItemName(); ?>
                        </h3>
                    </div>
                    <p class="photo">
                        <img src="/img/items/<?= $item->getItemImage(); ?>" />
                    </p>
                    <div class="text_side">
                        <h3 class="item_name">
                            <?= $item->getItemName(); ?>
                        </h3>
                        <p class="price">&yen;<?= $item->getItemPriceWithTax(); ?><span>(税込)</span>
                        </p>
                        <p class="item_detail_txt">
                            <?= $item->getItemDetail(); ?>
                        </p>
                        <div class="detail_form_wrap">
                            <form action="/html/cart.php" method="POST" class="item_num_form">
                                <div class="select_wrap">
                                    <select name="item_count" class="item_count_sl">
                                    <?php  for($i=1; $i<=10; $i++){
                                       echo "<option value={$i}>{$i}</option>";
                                    } ?>
                                    </select>
                                </div>
                                <span>個</span>
                                <div class="cart_btn_wrap">
                                    <input type="submit" class="btn_cmn_mid btn_design_01" value="カートにいれる" />
                                    <input type="hidden" name="cmd" value="add_cart" />
                                    <input type="hidden" name="item_code" value="<?= $item->getItemCode(); ?>" />
                                </div>
                            </form>
                            <div class="favorite_btn_wrap">
                             <form action="/html/mypage/mypage_favorite.php" method="GET">
                                <input type="submit" class="btn_cmn_mid btn_design_02" value="お気に入り保存" />
                                <input type="hidden" name="cmd" value="add_favorite" />
                                <input type="hidden" name="item_code" value="<?= $item->getItemCode(); ?>" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="back_btn_wrap">
                        <input type="button" class="btn_cmn_mid btn_design_03" value="前の画面へ戻る" onclick="history.back()" />    
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
