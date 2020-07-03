<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_start();

use \Config\Config;

$adminNoticeDetail = new \Controllers\AdminNoticeDetailAction();
$adminNoticeDetail->execute();
$notice = $adminNoticeDetail->getNotice();
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
// --> 
</script>
</head>
<body class="admin" id="admin_notice_detail">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_notice.php">お知らせ管理画面</a></h2>
                    </div>
		            <div class="main_contents_inner">
                        <table class="admin_notice_list_wrapper">
                            <tr>      
                                <th>ID</th>
                                <td><?=Config::h($notice->getId());?></td>
                            </tr>
                            <tr>
                                <th>件名</th>
                                <td><?=Config::h($notice->getTitle());?></td>
                            </tr>
                            <tr>
                                <th>本文</th>
                                <td><?=$notice->getMainText();?></td>
                            </tr>
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
    </div>
</body>
</html>