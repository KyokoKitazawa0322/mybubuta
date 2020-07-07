<?php
require_once (__DIR__ ."/../../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;
use \Models\CsrfValidator;

$myPageDeliveryEntryConfirm = new \Controllers\MyPageDeliveryEntryConfirmAction();
$myPageDeliveryEntryConfirm->execute();
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

<body class="mypage" id="mypage_delivery_entry_confirm">
<div class="wrapper">
    <?php require_once(__DIR__.'/../../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>配送先の登録</h2>
                <div class="register_wrapper">
                    <div class="register_form">
                        <div class="register_field name_field">
                            <div class="register_form_row">
                                <p class="register_form_title">氏名</p>
                                <p class="name_label">姓:</p>
                                <div class="name_input_wrapper">
                                    <p><?=Config::h($_SESSION['del_update']['last_name']);?></p>
                                </div>
                                <p class="name_label">名:</p>
                                <div class="name_input_wrapper">
                                    <p><?=Config::h($_SESSION['del_update']['first_name']);?></p>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">フリガナ(カタカナ)</p>
                                <p class="name_label">セイ:</p>
                                <div class="name_input_wrapper">
                                    <p><?=Config::h($_SESSION['del_update']['ruby_last_name']);?></p>
                                </div>
                                <p class="name_label">メイ:</p>
                                <div class="name_input_wrapper">
                                    <p><?=Config::h($_SESSION['del_update']['ruby_first_name']);?></p>
                                </div>
                            </div>
                        </div>  
                        <div class="register_field">
                            <div class="register_form_row">
                                <p class="register_form_title">郵便番号</p>
                                <div class="addr01_input_wrapper">
                                    <p><?=Config::h($_SESSION['del_update']['zip_code_01']);?></p>
                                </div>
                                <span class="txt_dash">－</span>
                                <div class="addr01_input_wrapper">
                                    <p><?=Config::h($_SESSION['del_update']['zip_code_02']);?></p>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">都道府県</p>
                                <div class="add_list_wrapper">
                                   <p><?=Config::h($_SESSION['del_update']['prefecture']);?></p>
                                </div>		
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">市区町村</p>
                                <p><?=Config::h($_SESSION['del_update']['city']);?></p>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">番地</p>
                                <p><?=Config::h($_SESSION['del_update']['block_number']);?></p>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">建物名</p>
                                <p><?=Config::h($_SESSION['del_update']['building_name']);?></p>
                            </div>
                        </div>                     
                        <div class="register_field tel_field">
                            <div class="register_form_row">
                                <p class="register_form_title">電話番号</p>
                                <p><?=Config::h($_SESSION['del_update']['tel']);?></p>
                            </div>
                        </div>
                        <div class="confirm_button_wrapper">
                            <div class="confirm_button_inner">
                                <input class="btn_cmn_mid btn_design_03" type="button" onClick="location.href='/html/mypage/delivery/mypage_delivery_entry.php'" value="前の画面に戻る"/>
                            </div>
                            <form action="/html/mypage/delivery/mypage_delivery_complete.php" method="POST">
                                <div class="confirm_button_inner">
                                    <input class="register_button btn_design_01" type="submit" value="この内容で登録する"/>
                                </div>
                                <input type="hidden" name="token_del_entry_complete" value="<?=CsrfValidator::maketoken("token_del_entry_complete")?>">
                            </form>
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

