<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_start();

use \Config\Config;

$adminNotice = new \Controllers\AdminNoticeAction();
$adminNotice->execute();
$notices = $adminNotice->getNotices();
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
    $('.notice_detail').click(function(){ 
        var id = $(this).data("value");
        $('input#notice_id').val(id);
        $('form#detailForm').submit();
    });
});
    
$(function(){
    $('.notice_delete').click(function(){ 
        var id = $(this).data("value");
        $('input#notice_id').val(id);
        $('form#deleteForm').submit();
    });
});
    
// --> 
</script>
</head>
<body class="admin" id="admin_notice">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2>お知らせ管理画面</h2>
                    </div>
		            <div class="main_contents_inner">
                        <div class="link_wrap">
                            <a href="/html/admin/admin_notice_register.php" class="btn_cmn_mid btn_design_02">お知らせ登録画面へ</a>
                        </div>
                        <table class="admin_notice_list_wrapper">
                            <tr>      
                                <th>ID<br/><a class="sort" data-value="sortby_id_desc">▼</a>&nbsp;&nbsp;<a class="sort" data-value="sortby_id_asc">▲</a></th>
                                <th>件名</th>
                                <th>登録日</th>
                                <th>変更</th>
                            </tr>
                            <?php foreach($notices as $notice): ?>
                                <tr class="admin_item">
                                    <td class="admin_notice_id"><?=Config::h($notice->getId());?></td>
                                    <td class="admin_notice_title"><?=Config::h($notice->getTitle());?></td>
                                    <td class="admin_notice_isnert_date"><?=Config::h($notice->getInsertDate());?></td>
                                    <td class="admin_button_area">
                                        <input type="button" class="btn_cmn_01 btn_design_02 notice_detail" value="詳細" data-value="<?=Config::h($notice->getId());?>">
                                        <input type="button" class="btn_cmn_01 btn_design_03 notice_delete" value="削除" data-value="<?=Config::h($notice->getId());?>">
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
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
        <form method="POST" id="detailForm" action="/html/admin/admin_notice_detail.php">
            <input type="hidden" id="notice_id" name="notice_id" value>
            <input type="hidden" name="cmd" value="notice_detail">
        </form>
        <form method="POST" id="deleteForm" action="#">
            <input type="hidden" id="notice_id" name="notice_id" value>
            <input type="hidden" name="cmd" value="delete">
        </form>
    </div>
</body>
</html>