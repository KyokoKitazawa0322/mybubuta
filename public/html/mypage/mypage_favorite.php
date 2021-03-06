<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$favorite = new \Controllers\MyPageFavoriteAction();
$favorite->execute();
$favoriteItems = $favorite->getFavoriteDto();
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

$(function() {
    $("input#cartIn").click(function(){
        var item_code = $(this).data('value');
        $('#itemId').val(item_code);
        $("form#cartInForm").submit();
    });
});
    
$(function() {
    $("input#delete").click(function(){
        var item_code = $(this).data('item');
        $('#deleteItemId').val(item_code);
        $("form#deleteForm").submit();
    });
});
// --> 
</script>
</head>

<body class="mypage" id="favorite">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="favorite_title">
                    <h2>お気に入り商品</h2>
                </div>
                <div class="main_contents_inner">
                    <?php if($favoriteItems): ?>
                        <ul class="fav_list">
                            <?php foreach($favoriteItems as $item): ?>
                                <li class="products">
                                    <div class="product_inner">
                                        <a class="product_link" <?php if($favorite->checkItemStatus($item->getItemStatus())):?>href="/html/item_detail.php?item_code=<?=Config::h($item->getItemCode());?>"<?php endif;?>>
                                            <img src="<?=$item->getItemImagePath();?>" alt="" />
                                            <div class="item_txt_wrap">
                                                <p class="item_name"><?=Config::h($item->getItemName());?></p>
                                                <p class="item_list_price">&yen;<?=Config::h(number_format($item->getItemPriceWithTax()));?></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="fav_btn_area">
                                        <?php if($item->getItemStatus()=="1"):?>
                                            <div class="fav_btn_wrap">
                                                <input type="button" class="btn_cmn_mid btn_design_02" value="カートにいれる" id="cartIn" data-value="<?=Config::h($item->getItemCode());?>">
                                            </div>
                                        <?php elseif($item->getItemStatus()=="2"):?>
                                            <p class="status_text">この商品は現在、入荷待ちです。</p>
                                        <?php elseif($item->getItemStatus()=="3"):?>
                                            <p class="status_text">この商品は販売終了しました。</p>
                                        <?php elseif($item->getItemStatus()=="4"):?>
                                            <p class="status_text">この商品は現在、一時掲載を停止してます。</p>
                                        <?php elseif($item->getItemStatus()=="5"):?>
                                            <p class="status_text">この商品は現在、品切れ中です(入荷未定)。</p>
                                        <?php endif;?>
                                        <div class="fav_btn_wrap">
                                            <input type="button" class="btn_cmn_01 btn_design_03" value="削除" id="delete" data-item="<?=Config::h($item->getItemCode());?>">
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    <?php else:?>
                        <div class="txt_wrapper">
                            <p class="none_txt">お気に入りに登録されている商品はありません。</p>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
     <?php require_once(__DIR__.'/mypage_common.php');?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form method="POST" id="cartInForm" action="#">
        <input type="hidden" name="cmd" value="add_cart">
        <input type="hidden" name="item_code" id="itemId" value>
    </form>
    <form method="POST" id="deleteForm" action="/html/mypage/mypage_favorite.php">
        <input type="hidden" name="cmd" value="delete">
        <input type="hidden" name="item_code" id="deleteItemId" value>
    </form>
</div>
</body>
</html>
