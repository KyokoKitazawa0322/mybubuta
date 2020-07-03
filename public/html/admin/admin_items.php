<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();
use \Config\Config;
    
$adminItems = new \Controllers\AdminItemsAction();
$adminItems->execute();
$items = $adminItems->getItems();
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
    $('.admin_update').click(function(){ 
        var itemCode = $(this).data("item");
        $('input#item_code').val(itemCode);
        $('form#updateForm').submit();
    });
});
    
    
$(function(){
    $('.admin_delete').click(function(){ 
        var itemCode = $(this).data("item");
        $('input#item_code').val(itemCode);
        $('form#deleteForm').submit();
    });
});
    
$(function(){
    $('#reset').click(function(){ 
        $('form#resetForm').submit();
    });
});
    
// --> 
</script>
</head>
<body class="admin" id="admin_items">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_items.php">商品管理画面</a></h2>
                    </div>
		            <div class="main_contents_inner">
                        <div class="admin_item_search_wrap">
                            <form method="POST" action="" id="search_form">
                                <div class="admin_item_search_row">
                                    <span>商品コード:</span>
                                    <input type="text" name="search_item_code" class="admin_search_item_code" value="<?=Config::h($adminItems->echoValue("item_code"))?>">
                                    <span>キーワード:</span>
                                    <input type="text" name="search_keyword" class="admin_search_item_keyword" value="<?=Config::h($adminItems->echoValue("keyword"))?>">
                                </div>
                                <div class="admin_item_search_row">
                                    <span>カテゴリ:</span>
                                    <select class="admin_search_item_category" name="search_category">
                                        <option value="">－</option>
                                        <?php foreach(Config::CATEGORY as $key=>$value):?>
                                            <option value="<?=$key?>" <?php $adminItems->checkSelectedCategory($key)?>><?=$key?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span>ステータス:</span>
                                    <select class="admin_search_item_status" name="search_status">
                                        <option value="">－</option>
                                        <option value="1" <?php $adminItems->checkSelectedStatus("1");?>>販売中</option>
                                        <option value="2" <?php $adminItems->checkSelectedStatus("2");?>>入荷待ち</option>
                                        <option value="3" <?php $adminItems->checkSelectedStatus("3");?>>販売終了</option>
                                        <option value="4" <?php $adminItems->checkSelectedStatus("4");?>>一時掲載停止</option>
                                        <option value="5" <?php $adminItems->checkSelectedStatus("5");?>>在庫切れ</option>
                                        <option value="6" <?php $adminItems->checkSelectedStatus("6");?>>販売前待機</option>
                                    </select>
                                </div>
                                <div class="admin_item_search_row">
                                    <span class="min_price_title">下限価格:</span>
                                    <input type="text" name="search_minprice" oninput="value = value.replace(/[^0-9]+/i,'');" class="admin_search_item_minprice"  value="<?=Config::h($adminItems->echoValue("min_price"))?>">
                                    <span class="tilde">～</span>
                                    <span class="min_price_title">上限価格:</span>
                                    <input type="text" name="search_maxprice" oninput="value = value.replace(/[^0-9]+/i,'');" class="admin_search_item_maxprice" value="<?=Config::h($adminItems->echoValue("max_price"))?>">
                                    <div class="admin_search_button_wrap">
                                        <button type="submit" form="search_form" class="btn_cmn_01 btn_design_02" name="cmd" value="search">検索</button>
                                        <a type="button" class="btn_cmn_01 btn_design_03" id="reset">リセット</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="link_wrap">
                            <a href="/html/admin/admin_item_register.php" class="btn_cmn_mid btn_design_02">商品登録画面へ</a>
                        </div>
                        <table class="admin_item_list_wrapper">
                            <tr>
                                <th>画像</th>
                                <th>カテゴリ</th>
                                <th>商品名</th>
                                <th>ステータス</th>
                                <th>商品コード</th>
                                <th>売価<br/>(税込)<br/><a class="sort" data-value="item_price_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="item_price_asc">▲</a></th>
                                <th>在庫<br/><a class="sort" data-value="item_stock_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="item_stock_asc">▲</a></th>
                                <th>販売数量<br/><a class="sort" data-value="item_sales_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="item_sales_asc">▲</a></th>
                                <th>登録日<br/><a class="sort" data-value="item_insert_date_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="item_insert_date_asc">▲</a></th>
                                <th>更新日<br/><a class="sort" data-value="item_updated_date_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="item_updated_date_asc">▲</a></th>
                                <th>変更</th>
                            </tr>
                            <?php if($items):?>
                                <?php foreach($items as $item): ?>
                                    <tr class="admin_item">
                                        <td class="admin_item_image">
                                            <a class="admin_item_link" href="/html/item_detail.php?item_code=<?=Config::h($item->getItemCode());?>">
                                                <img src="<?=$item->getItemImagePath();?>" alt="" />
                                            </a>
                                        </td>
                                        <td class="admin_item_category"><?=Config::h($item->getItemCategory());?></td>
                                        <td class="admin_item_name"><?=Config::h($item->getItemName());?></td>
                                        <td class="admin_item_status"><?=Config::h($item->getItemStatusAsString());?></td>
                                        <td class="admin_item_code"><?=Config::h($item->getItemCode());?></td>
                                        <td class="admin_item_price">&yen;<?=Config::h(number_format($item->getItemPriceWithTax()));?></td>
                                        <td class="admin_item_stock"><?=Config::h($item->getItemStock());?></td>
                                        <td class="admin_item_sales"><?=Config::h($item->getItemSales());?></td>
                                        <td class="admin_item_insert_date"><?=Config::h($item->getItemInsertDate());?></td>
                                        <td class="admin_item_updated_date">
                                            <?php if(!$item->getItemUpdatedDate()):?>
                                                <span>―</span>
                                            <?php else:?>
                                                <?=Config::h($item->getItemUpdatedDate());?>
                                            <?php endif;?>
                                        </td>
                                        <td class="admin_button_area">
                                            <input type="button" class="btn_cmn_01 btn_design_03 admin_delete" value="削除" data-item="<?=Config::h($item->getItemCode());?>">
                                            <input type="button" class="btn_cmn_01 btn_design_02 admin_update" value="更新" data-item="<?=Config::h($item->getItemCode());?>">
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                        </table>
                        <?php if(!$items):?>
                            <p class="admin_none_text">検索結果：なし</p>
                        <?php endif;?>
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
        <form method="POST" id="updateForm" action="/html/admin/admin_item_update.php">
            <input type="hidden" id="item_code" name="item_code" value>
            <input type="hidden" name="cmd" value="update">
        </form>
        <form method="POST" id="deleteForm" action="#">
            <input type="hidden" id="item_code" name="item_code" value>
            <input type="hidden" name="cmd" value="delete">
        </form>
        <form method="POST" id="resetForm" action="#">
            <input type="hidden" name="cmd" value="reset">
        </form>
    </div>
</body>
</html>