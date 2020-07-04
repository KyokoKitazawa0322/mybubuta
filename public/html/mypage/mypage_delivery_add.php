<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$myPageDeliveryAdd = new \Controllers\MyPageDeliveryAddAction();
$myPageDeliveryAdd->execute();
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

<body class="mypage" id="mypage_delivery_add">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>配送先の登録</h2>
                <div class="register_wrapper">
                    <form method="POST" action="#" class="register_form">
                    <div class="register_field name_field">
                        <div class="register_form_row">
                            <p class="register_form_title">氏名</p>
                            <p class="name_label">姓</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryAdd->getLastNameError()){echo "error_box";}?>" type="text" maxlength="20" name="last_name" value="<?=Config::h($myPageDeliveryAdd->echoValue("last_name"))?>">
                            </div>
                            <p class="name_label">名</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryAdd->getFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="first_name" value="<?=Config::h($myPageDeliveryAdd->echoValue("first_name"))?>">
                            </div>
                            <?php if($myPageDeliveryAdd->getLastNameError()):?>
                                <p class="error_txt error_cmn"><?=$myPageDeliveryAdd->getLastNameError();?></p>
                            <?php endif;?>
                            <?php if($myPageDeliveryAdd->getFirstNameError()):?>
                                <p class="error_txt error_cmn"><?=$myPageDeliveryAdd->getFirstNameError();?></p>
                            <?php endif;?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">フリガナ(カタカナ)</p>
                            <p class="name_label">セイ</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryAdd->getRubyLastNameError()){echo "error_box";}?>" type="text" maxlength="20"  name="ruby_last_name" value="<?=Config::h($myPageDeliveryAdd->echoValue("ruby_last_name"))?>">
                            </div>
                            <p class="name_label">メイ</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryAdd->getRubyLastNameError()){echo "error_box";}?>" type="text" maxlength="20"  name="ruby_first_name" value="<?=Config::h($myPageDeliveryAdd->echoValue("ruby_first_name"))?>">
                            </div>
                            <?php if($myPageDeliveryAdd->getRubyLastNameError()):?>
                                <p class="error_txt error_cmn"><?=$myPageDeliveryAdd->getRubyLastNameError();?></p>
                            <?php endif;?>
                            <?php if($myPageDeliveryAdd->getRubyFirstNameError()):?>
                                <p class="error_txt error_cmn"><?=$myPageDeliveryAdd->getRubyFirstNameError();?></p>
                            <?php endif;?>
                        </div>
                    </div>                                    
                    <div class="register_field">
                        <div class="register_form_row">
                            <p class="register_form_title">郵便番号</p>
                            <div class="addr01_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryAdd->getZipCode01Error()){echo "error_box";}?>" type="tel" name="zip_code_01" maxlength="3" oninput="value = value.replace(/[^0-9]+/i,'');" id="add01" value="<?=Config::h($myPageDeliveryAdd->echoValue("zip_code_01"))?>">
                            </div>
                            <span class="txt_dash">―</span>
                            <div class="addr01_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryAdd->getZipCode01Error()){echo "error_box";}?>" type="tel" name="zip_code_02" maxlength="4" oninput="value = value.replace(/[^0-9]+/i,'');" id="add02" value="<?=Config::h($myPageDeliveryAdd->echoValue("zip_code_02"))?>">
                            </div>
                            <?php if($myPageDeliveryAdd->getZipCode01Error()):?>
                                <p class="error_txt memo zip_memo"><?=$myPageDeliveryAdd->getZipCode01Error();?></p>
                            <?php endif;?>
                            <?php if($myPageDeliveryAdd->getZipCode01Error()):?>
                                <p class="error_txt memo"><?=$myPageDeliveryAdd->getZipCode01Error();?></p>
                            <?php endif;?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">都道府県</p>
                            <div class="add_list_wrapper">
                                    <select class="add_list <?php if($myPageDeliveryAdd->getPrefectureError()){echo "error_box";}?>" id="add03" name="prefecture">
                                        <option value="">都道府県を選択して下さい</option>
                                        <?php foreach(Config::PREFECTURES as $kenmei):?>
                                            <?php if($myPageDeliveryAdd->checkSelectedPrefecture($kenmei)):?>
                                                <option  value="<?=$kenmei?>"selected><?=$kenmei?></option>
                                            <?php else:?>
                                            <option  value="<?=$kenmei?>"><?=$kenmei?></option>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                            </div>
                            <?php if($myPageDeliveryAdd->getPrefectureError()):?>
                                <p class="error_txt memo"><?php echo Config::h($myPageDeliveryAdd->getPrefectureError())?></p>
                            <?php endif;?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">市区町村*</p>
                            <input class="form_input_item <?php if($myPageDeliveryAdd->getCityError()){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="city"  value="<?=Config::h($myPageDeliveryAdd->echoValue("city"))?>">
                            <?php if($myPageDeliveryAdd->getCityError()):?>
                                <p class="error_txt memo"><?=$myPageDeliveryAdd->getCityError();?></p>
                            <?php endif;?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">番地*</p>
                            <input class="form_input_item <?php if($myPageDeliveryAdd->getBlockNumberError()){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="block_number" value="<?=Config::h($myPageDeliveryAdd->echoValue("block_number"))?>">
                            <div class="memo_wrapper">
                                <p class="memo">※番地漏れがないようにご注意下さい。(例)○△1-19-23</p>	
                                <?php if($myPageDeliveryAdd->getBlockNumberError()):?>
                                    <p class="error_txt memo"><?=$myPageDeliveryAdd->getBlockNumberError();?></p>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">建物名</p>
                            <input class="form_input_item" type="text" maxlength="100" id="add06" name="building_name" value="<?=Config::h($myPageDeliveryAdd->echoValue("building_name"))?>">
                            <div class="memo_wrapper">
                                <p class="memo">※部屋番号まで記載して下さい。(例)○△マンション205</p>

                            </div>
                        </div>
                    </div>
                    <div class="register_field tel_field">
                        <div class="register_form_row">
                            <p class="register_form_title">電話番号</p>
                            <input class="form_input_item <?php if($myPageDeliveryAdd->getTelError()){echo "error_box";}?>" name="tel" type="tel" maxlength="11" oninput="value = value.replace(/[^0-9]+/i,'');" id="tel" value="<?=Config::h($myPageDeliveryAdd->echoValue("tel"))?>">
                            <div class="memo_wrapper">
                                <p class="memo">※ハイフン(-)なし</p>
                                <?php if($myPageDeliveryAdd->getTelError()):?>
                                    <p class="error_txt memo"><?=$myPageDeliveryAdd->getTelError();?></p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="register_button_wrapper">
                        <input class="btn_cmn_l btn_design_01" type="submit" value="配送先を保存する">
                        <input type="hidden" name="cmd" value="add">
                    </div> 
                </form>
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

