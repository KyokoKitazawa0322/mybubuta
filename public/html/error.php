<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, in+itial-scale=1">
<meta name="description" content="�ǎ��̃A�C�e������ɓ���t�@�b�V�����ʔ̃T�C�g�B�ԂԂ� BUBUTA�̓��f�B�[�X�t�@�b�V�����m���ʔ̃T�C�g�ł��B">
<title>�ԂԂ��@BUBUTA ���� | ���f�B�[�X�t�@�b�V�����ʔ̂̂ԂԂ��y�����z</title>
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
                <p>�ڑ��G���[���������܂����B</p>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
