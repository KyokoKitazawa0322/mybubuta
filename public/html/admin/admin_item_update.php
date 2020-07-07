<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config;
use \Models\CsrfValidator;

$adminItemUpdate = new \Controllers\AdminItemUpdateAction();
$adminItemUpdate->execute();
$item = $adminItemUpdate->getItemDto();
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
    $('#update_confirm').click(function(){ 
        $('form#itemDataForm').submit();
    });
});
    
$(function(){
    history.pushState(null, null, null);
    $(window).on("popstate", function (event) {
        window.location.replace('/html/admin/admin_items.php');
    });
});

// --> 
</script>
</head>
<body class="admin" id="admin_item_update">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_items.php">商品管理画面</a></h2>
                    </div>
		            <div class="main_contents_inner">
                        <table class="admin_item_list_wrapper">
                            <tr>
                                <th>画像</th>
                                <td class="admin_item_image">
                                    <img src="<?=Config::h($item->getItemImagePath())?>" alt="" />
                                    <form method="post" action="#" enctype="multipart/form-data">
                                        <input type="hidden" name="max_file_size" value="5000000"/>
                                        <input class="item_image_upfile" type="file" name="image" size="50"/>
                                        <input type="hidden" name="cmd" value="upload_file"/>
                                        <input type="hidden" name="item_code" value="<?=Config::h($adminItemUpdate->echoValue("item_code", $item->getItemCode()))?>"/>
                                        <input type="submit" value="画像を更新する"/>
                                    </form>
                                </td>
                            </tr>
                            <form method="post" action="#" id="itemDataForm" enctype="multipart/form-data">
                                <tr>
                                    <th>商品名</th>
                                    <td class="admin_item_name">
                                        <input type="text" name="item_name" value="<?=Config::h($adminItemUpdate->echoValue("item_name", $item->getItemName()))?>"/>
                                        <?php if($adminItemUpdate->getItemNameError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemNameError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品コード<br/></th>
                                    <td class="admin_item_code">
                                        <input type="text" name="update_item_code" value="<?=Config::h($adminItemUpdate->echoValue("item_code", $item->getItemCode()))?>"/>
                                        <?php if($adminItemUpdate->getItemCodeError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemCodeError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>売価<br/></th>
                                    <td class="admin_item_price">
                                        <input type="text" maxlength="6" oninput="value = value.replace(/[^0-9]+/i,'');" name="item_price" value="<?=Config::h($adminItemUpdate->echoValue("item_price", $item->getItemPrice()))?>"/>&nbsp;円(税抜き)
                                        <?php if($adminItemUpdate->getItemPriceError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemPriceError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>在庫<br/></th>
                                    <td class="admin_item_stock">
                                        <input type="text" maxlength="10" oninput="value = value.replace(/[^0-9]+/i,'');" name="item_stock" value="<?=Config::h($adminItemUpdate->echoValue("item_stock", $item->getItemStock()))?>"/>&nbsp;個
                                        <?php if($adminItemUpdate->getItemStockError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemStockError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                               <tr>
                                    <th>ステータス<br/></th>
                                    <td class="admin_item_status">
                                        <input type="radio" name="item_status" id="status_1" value="1" <?=$adminItemUpdate->checkSelectedStatus("1")?>>
                                        <label for="status_1">販売中</label>
                                        
                                        <input type="radio" name="item_status" id="status_2" value="2" <?=$adminItemUpdate->checkSelectedStatus("2")?>>
                                        <label for="status_2">入荷待ち</label>
                                        
                                        <input type="radio" name="item_status" id="status_3" value="3" <?=$adminItemUpdate->checkSelectedStatus("3")?>>
                                        <label for="status_3">販売終了</label>
                                        
                                        <input type="radio" name="item_status" id="status_4" value="4" <?=$adminItemUpdate->checkSelectedStatus("4")?>>
                                        <label for="status_4">一時掲載停止</label>
                                        
                                        <input type="radio" name="item_status" id="status_5" value="5" <?=$adminItemUpdate->checkSelectedStatus("5")?>>
                                        <label for="status_5">在庫切れ</label>
                                        
                                        <input type="radio" name="item_status" id="status_6" value="6" <?=$adminItemUpdate->checkSelectedStatus("6")?>>
                                        <label for="status_6">販売前待機中</label>
                                        <?php if($adminItemUpdate->getItemStatusError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemStatusError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>説明文<br/></th>
                                    <td class="admin_item_detail">
                                        <textarea rows="4" wrap="soft" name="item_detail"><?=Config::h($adminItemUpdate->echoValue("item_detail", $item->getItemDetail()))?></textarea>
                                        <?php if($adminItemUpdate->getItemDetailError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemDetailError();?></p>
                                        <?php endif;?>
                                    </td>
                                    <input type="hidden" name="item_code" value="<?=Config::h($item->getItemCode());?>"/>
                                    <input type="hidden" name="cmd" value="update_confirm">
                                </tr>
                                <input type="hidden" name="item_code" value="<?=Config::h($item->getItemCode());?>"/>
                                <input type="hidden" name="token" value="<?=CsrfValidator::generate()?>">
                            </form>
                        </table>
                        <div class="update_btn_wrap">
                            <input type="button" id="update_confirm" class="btn_cmn_l btn_design_01" value="更新する">
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