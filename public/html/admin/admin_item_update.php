<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config;
use \Models\CsrfValidator;

$adminItemUpdate = new \Controllers\AdminItemUpdateAction();
$adminItemUpdate->execute();
$item = $adminItemUpdate->getItemDto();
$errorMessage = $adminItemUpdate->getErrorMessage();
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
    history.pushState(null, null, null);
    $(window).on("popstate", function (event) {
        window.location.replace('/html/admin/admin_items.php');
    });
});
    
$(function(){
    var errorMessage = "<?=$errorMessage?>";
    if(errorMessage !== "none"){
       alert(errorMessage);
    }
});
 
    
$(function(){
    $('#update_confirm').click(function(){ 
        if(confirm("商品情報を更新しますか?")){
            var password = prompt("パスワードを入力してください");
            $('#update_password').val(password);
            $('form#itemDataForm').submit();
        }else{
            alert('キャンセルされました');
            e.preventDefault();
        }
    });
});
        
$(function(){
    $('#image_update_confirm').click(function(){ 
        if(confirm("商品画像を更新しますか?")){
            var password = prompt("パスワードを入力してください");
            $('#image_update_password').val(password);
            $('form#ImageUpDataForm').submit();
        }else{
            alert('キャンセルされました');
            e.preventDefault();
        }
    });
});
        
$(function(){
    $('#delete_confirm').click(function(){ 
        if(confirm("この商品を削除しますか?")){
            var password = prompt("パスワードを入力してください");
            $('#delete_password').val(password);
            $('form#itemDeleteForm').submit();
        }else{
            alert('キャンセルされました');
            e.preventDefault();
        }
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
                        <a href="/html/admin/admin_items.php" class="admin_link">商品一覧へ戻る</a>
                        <table class="admin_item_list_wrapper">
                            <tr>
                                <th>画像</th>
                                <td class="admin_item_image">
                                    <img src="<?=Config::h($item->getItemImagePath())?>" alt="" />
                                    <form method="post" action="#" enctype="multipart/form-data" id="imageUpdateForm">
                                        <input type="hidden" name="max_file_size" value="5000000"/>
                                        <input class="item_image_upfile" type="file" name="image" size="50"/>
                                        <input type="hidden" name="cmd" value="upload_file"/>
                                        <input type="hidden" name="item_code" value="<?=Config::h($adminItemUpdate->echoValue("item_code", $item->getItemCode()))?>"/>
                                        <input type="hidden" name="password" id="image_update_password" value>
                                        <input type="submit" value="画像を更新する" id="image_update_confirm"/>
                                        <?php if($adminItemUpdate->getItemImageError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemImageError();?></p>
                                        <?php endif;?>
                                    </form>
                                </td>
                            </tr>
                            <form method="post" action="#" id="itemDataForm" enctype="multipart/form-data">
                                <tr>
                                    <th>商品名(全角)</th>
                                    <td class="admin_item_name">
                                        <p>30文字以内</p>
                                        <input type="text" name="item_name" value="<?=Config::h($adminItemUpdate->echoValue("item_name", $item->getItemName()))?>" maxlength="30"/>
                                        <?php if($adminItemUpdate->getItemNameError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemNameError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品コード<br/></th>
                                    <td class="admin_item_code">
                                        <p><?=Config::h($adminItemUpdate->echoValue("item_code", $item->getItemCode()))?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>売価(半角数字)<br/></th>
                                    <td class="admin_item_price">
                                        <p>上限額:999,999円</p>
                                        <input type="text" maxlength="6" oninput="value = value.replace(/[^0-9]+/i,'');" name="item_price" value="<?=Config::h($adminItemUpdate->echoValue("item_price", $item->getItemPrice()))?>"/>&nbsp;円(税抜き)
                                        <?php if($adminItemUpdate->getItemPriceError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemPriceError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>在庫(半角数字)<br/></th>
                                    <td class="admin_item_stock">
                                        <p>上限:999,999個</p>
                                        <input type="text" maxlength="6" oninput="value = value.replace(/[^0-9]+/i,'');" name="item_stock" value="<?=Config::h($adminItemUpdate->echoValue("item_stock", $item->getItemStock()))?>"/>&nbsp;個
                                        <?php if($adminItemUpdate->getItemStockError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemStockError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                               <tr>
                                    <th>ステータス<br/></th>
                                    <td class="admin_item_status">
                                        <input type="radio" name="item_status" id="status_1" value="1" <?=$adminItemUpdate->checkSelectedStatus("1", $item->getItemStatus())?>>
                                        <label for="status_1">販売中</label>
                                        
                                        <input type="radio" name="item_status" id="status_2" value="2" <?=$adminItemUpdate->checkSelectedStatus("2", $item->getItemStatus())?>>
                                        <label for="status_2">入荷待ち</label>
                                        
                                        <input type="radio" name="item_status" id="status_3" value="3" <?=$adminItemUpdate->checkSelectedStatus("3", $item->getItemStatus())?>>
                                        <label for="status_3">販売終了</label>
                                        
                                        <input type="radio" name="item_status" id="status_4" value="4" <?=$adminItemUpdate->checkSelectedStatus("4", $item->getItemStatus())?>>
                                        <label for="status_4">一時掲載停止</label>
                                        
                                        <input type="radio" name="item_status" id="status_5" value="5" <?=$adminItemUpdate->checkSelectedStatus("5", $item->getItemStatus())?>>
                                        <label for="status_5">在庫切れ</label>
                                        
                                        <input type="radio" name="item_status" id="status_6" value="6" <?=$adminItemUpdate->checkSelectedStatus("6", $item->getItemStatus())?>>
                                        <label for="status_6">販売前待機中</label>
                                        <?php if($adminItemUpdate->getItemStatusError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemStatusError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>説明文<br/></th>
                                    <td class="admin_item_detail">
                                        <p>500文字以内</p>
                                        <textarea rows="4" wrap="soft" name="item_detail" maxlength="500"><?=Config::h($adminItemUpdate->echoValue("item_detail", $item->getItemDetail()))?></textarea>
                                        <?php if($adminItemUpdate->getItemDetailError()):?>
                                            <p class="error_txt"><?=$adminItemUpdate->getItemDetailError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <input type="hidden" name="item_code" value="<?=Config::h($item->getItemCode());?>"/>
                                <input type="hidden" name="cmd" value="update_confirm">
                                <input type="hidden" name="item_code" value="<?=Config::h($item->getItemCode());?>"/>
                                <input type="hidden" name="token" value="<?=CsrfValidator::generate()?>">
                                <input type="hidden" name="password" id="update_password" value>
                            </form>
                        </table>
                        <div class="update_btn_wrap">
                            <input type="button" class="btn_cmn_l btn_design_01" id="update_confirm" value="更新する">
                        </div>
                        <div class="update_btn_wrap">
                            <form method="post" action="#" id="itemDeleteForm">
                                <input type="hidden" name="item_code" value="<?=Config::h($item->getItemCode());?>"/>
                                <input type="button" class="btn_cmn_l btn_design_03" id="delete_confirm" value="削除する">
                                <input type="hidden" name="cmd" value="delete_confirm"/>
                                <input type="hidden" name="token" value="<?=CsrfValidator::generate()?>">
                                <input type="hidden" name="password" id="delete_password" value>
                            </form>
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