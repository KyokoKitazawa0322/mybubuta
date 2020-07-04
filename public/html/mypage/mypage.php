<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

$mypage = new \Controllers\MyPageAction();
$mypage->execute();
$noticeDto = $mypage->getNoticeDto();
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
</head>

<body class="mypage">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="mypage_title">
                    <h2>マイページ</h2>
                </div>
                <div class="main_contents_inner">
                    <h3 class="ttl_cmn">会員様へのお知らせ</h3>
                    <?php foreach($noticeDto as $notice):?>
                        <div class="notice_wrapper">
                            <div class="box_info">
                                <p class="notice_date"><?=$notice->getInsertDate();?></p>
                                <p class="notice_title"><?=$notice->getTitle();?></p>
                            </div>
                            <div class="detail_link_wrap">
                                <form method="POST" action="mypage_notice_detail.php">
                                    <input type="submit" class="btn_cmn_01 btn_design_02 btn_cmn_mid" value="お知らせを見る">
                                    <input type="hidden" name="notice_id" value="<?=$notice->getId();?>">
                                </form>
                            </div>
                        </div>
                    <?php endforeach;?>
                    <div class="notice_link_wrapper">
                        <a href="/html/mypage/mypage_notice.php" class="notice_link btn_cmn_l btn_design_01">お知らせ一覧を見る</a>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once(__DIR__.'/mypage_common.php');?>
        </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
