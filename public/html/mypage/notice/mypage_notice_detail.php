<?php
require_once (__DIR__ ."/../../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$mypageNoticeDetail = new \Controllers\MyPageNoticeDetailAction();
$mypageNoticeDetail->execute();
$noticeDto = $mypageNoticeDetail->getNoticeDto();
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

<body class="mypage" id="mypage_notice">
<div class="wrapper">
    <?php require_once(__DIR__.'/../../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="mypage_title">
                    <h2>マイページ</h2>
                </div>
                <div class="main_contents_inner">
                    <h3 class="ttl_cmn">会員様へのお知らせ</h3>
                    <div class="notice_wrapper">
                        <div class="box_info">
                            <p class="notice_date"><?=Config::h($noticeDto->getInsertDate());?></p>
                            <h4><?=Config::h($noticeDto->getTitle());?></h4>
                            <p class="notice_text"><?=$noticeDto->getMainText();?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once(__DIR__.'/../mypage_common.php');?>
        </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
