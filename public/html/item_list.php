<?php
require_once (__DIR__ ."/../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

$itemList = new \Controllers\ItemListAction();
$itemList->execute();
$items = $itemList->getItems();
$topItems = $itemList->getTopItems(); 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<script>
<!--
$(function() {
    jQuery(document).ready(function($){
    $('.bunner-sp').bxSlider({
        auto: true,
        mode:'fade',
        speed: 1000,
        pause: 3000,
        controls: false,
        infiniteLoop: true,
        slideWidth: 320,
     });
    });
});
// --> 
</script>
</head>
<body id="item_list">
<div class="wrapper">
    <?php require_once(__DIR__.'/common/header_common.php');?>
    <div class="container">
    <?php if(!(isset($_GET["cmd"]) && $_GET['cmd'] == "do_search") && !isset($_GET["sortkey"])): ?>
        <div class="bunner_wrap_center">
            <div class="bunner-sp">
                <img src="/img/bunner01.jpg"/>
                <img src="/img/bunner02.jpg"/>
                <img src="/img/bunner03.jpg"/>
            </div>
        </div>
    <?php endif; ?>
    <?php require_once(__DIR__.'/common/left_pane.php'); ?>
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>
                    <img class="product_logo" src="/img/main_contents_title_products.png" alt="商品一覧">
                </h2>
                <div class="sort_item_wrapper">
                    <div class="sort_item_byorder">
                        <p>並び替え：</p>
                        <form name="sort_form" class="sort_form" action="#" method="GET">
                            <div class="select_wrap">
                                <select name="sortkey" class="sortkey" onchange="submit(this.form)">
                                    <option value="01" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="01"){echo "selected";} ?>>価格の安い順</option>
                                    <option value="02" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="02"){echo "selected";} ?>>価格の高い順</option>
                                    <option value="03" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="03" || !isset($_GET['sortkey'])){echo "selected";}?>>人気順</option>
                                    <option value="04" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="04" || !isset($_GET['sortkey'])){echo "selected";}?>>新着順</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="main_contents_inner">
                    <?php if(!(isset($_GET["cmd"]) && $_GET['cmd'] == "do_search") && !isset($_GET["sortkey"])): ?>
                    <ul class="item_list_rank">
                        <h3>人気ランキング</h3>
                        <?php $i=0; foreach($topItems as $item): $i++;?>
                        <li class="products <?php if($i=='5'){echo "rank05";}?>">
                            <div class="product_inner">
                                <span>No.<?= $i?></span>
                                <a class="product_link" href="/html/item_detail.php?item_code=<?= $item->getItemCode(); ?>">
                                    <img src="/img/items/<?= $item->getItemImage(); ?>" alt="" />
                                    <p class="item_name"><?= $item->getItemName(); ?></p>
                                    <p class="item_list_price">&yen;<?= number_format($item->getItemPriceWithTax()); ?></p>
                                </a>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <ul class="item_list_main">
                        <?php if($items): ?>
                            <?php foreach($items as $item): ?>
                                <li class="products">
                                    <div class="product_inner">
                                        <a class="product_link" href="/html/item_detail.php?item_code=<?= $item->getITemCode(); ?>">
                                            <img src="/img/items/<?= $item->getItemImage();?>" alt="" />
                                            <div class="item_txt_wrap">
                                                <p class="item_name"><?= $item->getItemName(); ?></p>
                                                <p class="item_list_price">&yen;<?= number_format($item->getItemPriceWithTax());?></p>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else:?>
                            <div class="txt_wrapper">
                                <p class="none_txt">該当する商品はありません。</p>
                            </div>
                        <?php endif;?>
                        </ul>
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