<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config; 
use \Models\CsrfValidator;

$adminNoticeRegister = new \Controllers\AdminNoticeRegisterAction();
$adminNoticeRegister->execute();
$errorMessage = $adminNoticeRegister->getErrorMessage();
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
    var errorMessage = "<?=$errorMessage?>";
    if(errorMessage !== "none"){
       alert(errorMessage);
    }
});
    
$(function(){
    $('#register_btn').click(function(){ 
        if(confirm("更新しますか?")){
            var password = prompt("パスワードを入力してください");
            $('#password').val(password);
            $('form#NoticeDataForm').submit();
            
        }else{
            alert('キャンセルされました');
            e.preventDefault();
        }
    });    
});
// --> 
</script>
</head>
<body class="admin" id="admin_notice_register">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_notice.php">お知らせ管理画面</a></h2>
                    </div>
		            <div class="main_contents_inner">
                        <a href="/html/admin/admin_notice.php" class="admin_link">お知らせ一覧へ戻る</a>
                        <table class="admin_notice_list_wrapper">
                            <form method="post" action="#" id="NoticeDataForm">
                                <tr>
                                    <th>件名<br/></th>
                                    <td class="admin_notice_title">
                                        <p>50文字以内</p>
                                        <input type="text" name="title" maxlength=50 value="<?=Config::h($adminNoticeRegister->echoValue("title"))?>" />
                                        <?php if($adminNoticeRegister->getTitleError()):?>
                                            <p class="error_txt"><?=$adminNoticeRegister->getTitleError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>本文<br/></th>
                                    <td class="admin_notice_main_text">
                                        <p>1000文字以内</p>
                                        <textarea rows="15" wrap="soft" name="main_text" maxlength=1000><?=Config::h($adminNoticeRegister->echoValue("title"))?></textarea>
                                        <?php if($adminNoticeRegister->getMailTextError()):?>
                                            <p class="error_txt"><?=$adminNoticeRegister->getMailTextError();?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <input type="hidden" name="cmd" value="admin_notice_register">
                                <input type="hidden" name="token" value="<?=CsrfValidator::generate()?>">
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