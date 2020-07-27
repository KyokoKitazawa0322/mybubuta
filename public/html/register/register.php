<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$register = new \Controllers\RegisterAction();
$register->execute();
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
<body id="register">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="register_title">
                    <h2>
                        <img class="register_logo" src="/img/main_contents_title_register.png" alt="新規会員登録">
                    </h2>
                </div>
                <div class="register_wrapper">
					<div class="register_subtitle_wrapper">
						<h3>お客様情報入力</h3>
					</div>
                    <form method="POST" action="#" class="register_form">
                        <div class="register_field name_field">
                            <div class="register_form_row">
                                <p class="register_form_title">氏名*</p>
                                <p class="name_label">姓</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getLastNameError()){echo "error_box";}?>" type="text" maxlength="20" name="last_name" value="<?=Config::h($register->echoValue("last_name"))?>">
                                </div>
                                <p class="name_label">名</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="first_name" value="<?=Config::h($register->echoValue("first_name"))?>">
                                </div>
                                <?php if($register->getLastNameError()):?>
                                    <p class="error_txt error_cmn"><?=$register->getLastNameError();?></p>
                                <?php endif;?>
                                <?php if($register->getFirstNameError()):?>
                                    <p class="error_txt error_cmn"><?=$register->getFirstNameError();?></p>
                                <?php endif;?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">氏名(全角カナ)*</p>
                                <p class="name_label">セイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getRubyLastNameError()){echo "error_box";}?>" type="text" maxlength="20"  name="ruby_last_name" value="<?=Config::h($register->echoValue("ruby_last_name"))?>">
                                </div>
                                <p class="name_label">メイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getRubyFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="ruby_first_name" value="<?=Config::h($register->echoValue("ruby_first_name"))?>">
                                </div>
                                <?php if($register->getRubyLastNameError()):?>
                                    <p class="error_txt error_cmn"><?=$register->getRubyLastNameError();?></p>
                                <?php endif;?>
                                <?php if($register->getRubyFirstNameError()):?>
                                    <p class="error_txt error_cmn"><?=$register->getRubyFirstNameError();?></p>
                                <?php endif;?>
                            </div>
                        </div>                                         
                        <div class="register_field">
                            <div class="register_form_row">
                                <p class="register_form_title">郵便番号(半角数字)*</p>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if($register->getZipCode01Error()){echo "error_box";}?>" type="tel" name="zip_code_01" maxlength="3" oninput="value = value.replace(/[^0-9]+/i,'');" id="add01" value="<?=Config::h($register->echoValue("zip_code_01"))?>">
                                </div>
                                <span class="txt_dash">―</span>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if($register->getZipCode02Error()){echo "error_box";}?>" type="tel" name="zip_code_02" maxlength="4" oninput="value = value.replace(/[^0-9]+/i,'');" id="add02" value="<?=Config::h($register->echoValue("zip_code_02"))?>">
                                </div>
                                <?php if($register->getZipCode01Error()):?>
                                    <p class="error_txt memo zip_memo"><?=$register->getZipCode01Error();?></p>
                                <?php endif;?>
                                <?php if($register->getZipCode02Error()):?>
                                    <p class="error_txt memo"><?=$register->getZipCode02Error();?></p>
                                <?php endif;?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">都道府県*</p>
                                <div class="add_list_wrapper">
                                    <select class="add_list <?php if($register->getPrefectureError()){echo "error_box";}?>" id="add03" name="prefecture">
                                        <option value="">都道府県を選択して下さい</option>
                                        <?php foreach(Config::PREFECTURES as $kenmei):?>
                                            <?php if($register->checkSelectedPrefecture($kenmei)):?>
                                                <option  value="<?=$kenmei?>"selected><?=$kenmei?></option>
                                            <?php else:?>
                                            <option  value="<?=$kenmei?>"><?=$kenmei?></option>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                </div>		
                            <?php if($register->getPrefectureError()):?>
                                <p class="error_txt memo"><?=$register->getPrefectureError();?></p>
                            <?php endif;?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">市区町村(全角)*</p>
                                <input class="form_input_item <?php if($register->getCityError()){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="city" value="<?=Config::h($register->echoValue("city"))?>">
                                <?php if($register->getCityError()):?>
                                    <p class="error_txt memo"><?=$register->getCityError();?></p>
                                <?php endif;?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">番地(全角)*</p>
                                <input class="form_input_item <?php if($register->getBlockNumberError()){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="block_number" value="<?=Config::h($register->echoValue("block_number"))?>">
                                <div class="memo_wrapper">
                                    <p class="memo">(例)○△１－１９－６２</p>	
                                    <?php if($register->getBlockNumberError()):?>
                                        <p class="error_txt memo"><?=$register->getBlockNumberError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">建物名等(全角)</p>
                                <input class="form_input_item" type="text" maxlength="100" name="building_name" id="add06" value="<?=Config::h($register->echoValue("building_name"))?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※部屋番号まで記載して下さい。(例)○△マンション２０５</p>
                                    <?php if($register->getBuildingNameError()):?>
                                        <p class="error_txt memo"><?=$register->getBuildingNameError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="register_field tel_field">
                            <div class="register_form_row">
                                <p class="register_form_title">電話番号*</p>
                                <input class="form_input_item <?php if($register->getTelError()){echo "error_box";}?>" name="tel" type="tel" maxlength="11" id="tel" oninput="value = value.replace(/[^0-9]+/i,'');" value="<?=Config::h($register->echoValue("tel"))?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※ハイフン(-)なし</p>
                                    <?php if($register->getTelError()):?>
                                        <p class="error_txt memo"><?=$register->getTelError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="register_field mail_field">
                            <div class="register_form_row">
                                <p class="register_form_title">メール*</p>
                                <input class="form_input_item <?php if($register->getMailError()){echo "error_box";}?>" type="text" maxlength="100" name="mail" id="mail" value="<?=Config::h($register->echoValue("mail"))?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※お間違いがないか必ずご確認下さい。</p>
                                    <?php if($register->getMailError()):?>
                                        <p class="error_txt memo"><?=$register->getMailError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="register_field pass_field">
                            <div class="register_form_row">
                                <p class="register_form_title">パスワード*</p>
                                <input class="form_input_item <?php if($register->getPasswordError()){echo "error_box";}?>" type="password" placeholder="" name="password" id="password" maxlength="20">
                                <div class="memo_wrapper">
                                    <p class="memo">※半角英数字の組み合わせ8〜20文字</p>
                                    <?php if($register->getPasswordError()):?>
                                        <p class="error_txt memo"><?=$register->getPasswordError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">パスワード(再確認)*</p>
                                <input class="form_input_item <?php if($register->getPasswordConfirmError()){echo "error_box";}?>" type="password" placeholder="" name="passwordConfirm" id="confirm" maxlength="20">
                                <?php if($register->getPasswordConfirmError()):?>
                                    <p class="error_txt memo"><?=$register->getPasswordConfirmError();?></p>
                                <?php endif;?>
                            </div>
                        </div>
     <!------------------------------------------------------------
                              会員登録ボタン
	 -------------------------------------------------------------->
                        <div class="register_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="submit" value="会員登録をする">
                            <input type="hidden" name="cmd" value="confirm">
                      </div> 
                    </form>
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
