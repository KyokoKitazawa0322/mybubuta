<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config; 
use \Models\CsrfValidator;

$adminItemRegister = new \Controllers\AdminItemRegisterAction();
$adminItemRegister->execute();
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
    $('#register_btn').click(function(){ 
        $('form#itemDataForm').submit();
    });
});
// --> 
</script>
</head>
<body class="admin" id="admin_item_register">
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
                            <form method="post" action="#" id="itemDataForm" enctype="multipart/form-data">
                                <tr>
                                    <th>画像</th>
                                    <td class="admin_item_image">
                                        <input type="hidden" name="max_file_size" value="5000000"/>
                                        <input class="item_image_upfile" type="file" name="image" size="50"/>
                                        <?php if($adminItemRegister->getItemImageError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemImageError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品名</th>
                                    <td class="admin_item_name">
                                        <input type="text" name="item_name" value="<?=Config::h($adminItemRegister->echoValue("item_name"))?>"/>
                                        <?php if($adminItemRegister->getItemNameError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemNameError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品コード<br/></th>
                                    <td class="admin_item_code">
                                        <input type="text" name="item_code" value="<?=Config::h($adminItemRegister->echoValue("item_code"))?>"/>
                                        <?php if($adminItemRegister->getItemCodeError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemCodeError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>売価<br/></th>
                                    <td class="admin_item_price">
                                        <input type="text" name="item_price" maxlength="6" oninput="value = value.replace(/[^0-9]+/i,'');" value="<?=Config::h($adminItemRegister->echoValue("item_price"))?>"/>&nbsp;円(税抜き)
                                        <?php if($adminItemRegister->getItemPriceError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemPriceError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品カテゴリー<br/></th>
                                    <td class="admin_item_category">
                                        <?php foreach(Config::CATEGORY as $key=>$value):?>
                                            <input type="radio" name="item_category" id="<?=$key?>" value="<?=$key?>" <?=$adminItemRegister->checkSelectedCategory($key)?>>
                                            <label for="<?=$key?>"><?=$value?></label>
                                        <?php endforeach;?>
                                        <?php if($adminItemRegister->getItemCategoryError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemCategoryError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>在庫<br/></th>
                                    <td class="admin_item_stock">
                                        <input type="text" name="item_stock" maxlength="10" oninput="value = value.replace(/[^0-9]+/i,'');" value="<?=Config::h($adminItemRegister->echoValue("item_stock"))?>"/>&nbsp;個
                                        <?php if($adminItemRegister->getItemStockError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemStockError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                               <tr>
                                    <th>ステータス<br/></th>
                                    <td class="admin_item_status">
                                        <input type="radio" name="item_status" id="status_1" value="1" <?=$adminItemRegister->checkSelectedStatus("1")?>>
                                        <label for="status_1">販売中</label>
                                        <input type="radio" name="item_status" id="status_2" value="2" <?=$adminItemRegister->checkSelectedStatus("2")?>>
                                        <label for="status_2">入荷待ち</label>
                                        <input type="radio" name="item_status" id="status_3" value="3" <?=$adminItemRegister->checkSelectedStatus("3")?>>
                                        <label for="status_3">販売前待機中</label>
                                        <?php if($adminItemRegister->getItemStatusError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemStatusError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>説明文<br/></th>
                                    <td class="admin_item_detail">
                                        <textarea rows="4" wrap="soft" name="item_detail"><?=Config::h($adminItemRegister->echoValue("item_detail"))?></textarea>
                                        <?php if($adminItemRegister->getItemDetailError()):?>
                                            <p class="error_txt"><?=$adminItemRegister->getItemDetailError();?></p>
                                        <?php endif;?>
                                    </td>
                                    <input type="hidden" name="cmd" value="admin_item_register">
                                    <input type="hidden" name="token" value="<?=CsrfValidator::generate()?>">
                                </tr>
                            </form>
                        </table>
                        <div class="register_btn_wrap">
                            <input type="button" id="register_btn" class="btn_cmn_l btn_design_01" value="登録する">
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