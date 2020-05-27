<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, in+itial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body id="item_detail">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php');?>
    <div class="container">
    <?php if(!(isset($_GET["cmd"]) && $_GET['cmd'] == "do_search") && !isset($_GET["sortkey"])): ?>
        <div class="bunner_wrap_center">
            <div class="bunner-sp">
                <img src="/img/bunner01.jpg"/>
                <img src="/img/bunner02.jpg"/>
                <img src="/img/bunner03.jpg"/>
            </div>
        </div>
    <?php endif; ?>
    <?php require_once(__DIR__.'/common/left_pane.php'); ?>
        <div class="main_wrapper">
            <div class="main_contents">
                <p>接続エラーが発生しました。</p>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
