<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;

$myPageUpdate = new \Controllers\MyPageUpdateAction();
$myPageUpdate->execute();
$customer = $myPageUpdate->getCustomerDto();
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
<body class="mypage update">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php');?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>登録内容の確認・変更</h2>
                <div class="register_wrapper">
                    <form method="POST" action="#" class="register_form">
                        <div class="register_field name_field">
                            <div class="register_form_row">
                                <p class="register_form_title">氏名</p>
                                <p class="name_label">姓</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($myPageUpdate->getLastNameError()){echo "error_box";}?>" type="text" maxlength="20" name="last_name" value="<?=Config::h($myPageUpdate->echoValue("last_name", $customer->getLastName()))?>"/>
                                </div>
                                <p class="name_label">名</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($myPageUpdate->getFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="first_name" value="<?=Config::h($myPageUpdate->echoValue("first_name", $customer->getFirstName()))?>"/>
                                    <?php if($myPageUpdate->getLastNameError()):?>
                                        <p class="error_txt error_cmn"><?=$myPageUpdate->getFirstNameError();?></p>
                                    <?php endif;?>
                                    <?php if($myPageUpdate->getFirstNameError()):?>
                                        <p class="error_txt error_cmn clear"><?=$myPageUpdate->getFirstNameError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">フリガナ(カタカナ)</p>
                                <p class="name_label">セイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($myPageUpdate->getRubyLastNameError()){echo "error_box";}?>" type="text" maxlength="20"  name="ruby_last_name" value="<?=Config::h($myPageUpdate->echoValue("ruby_last_name", $customer->getRubyLastName()))?>"/>
                                </div>
                                <p class="name_label">メイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($myPageUpdate->getRubyFirstNameError()){echo "error_box";}?>"  type="text" maxlength="20" name="ruby_first_name"  value="<?=Config::h($myPageUpdate->echoValue("ruby_first_name", $customer->getRubyFirstName()))?>"/>
                                </div>
                                <?php if($myPageUpdate->getRubyLastNameError()):?>
                                    <p class="error_txt error_cmn"><?=$myPageUpdate->getRubyLastNameError();?></p>
                                <?php endif;?>
                                <?php if($myPageUpdate->getRubyFirstNameError()):?>
                                    <p class="error_txt error_cmn clear"><?=$myPageUpdate->getRubyFirstNameError();?></p>
                                <?php endif;?>
                            </div>
                        </div>                              
                        <div class="register_field">
                            <div class="register_form_row">
                                <p class="register_form_title">郵便番号</p>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if($myPageUpdate->getZipCode01Error()){echo "error_box";}?>" type="tel" name="zip_code_01" maxlength="3" oninput="value = value.replace(/[^0-9]+/i,'');" id="add01" value="<?=Config::h($myPageUpdate->echoValue("zip_code_01", $customer->getZipCode01()))?>"/>
                                </div>
                                <span class="txt_dash">―</span>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if($myPageUpdate->getZipCode02Error()){echo "error_box";}?>" type="tel" name="zip_code_02" maxlength="4" oninput="value = value.replace(/[^0-9]+/i,'');" id="add02" value="<?=Config::h($myPageUpdate->echoValue("zip_code_02", $customer->getZipCode02()))?>"/>
                                </div>
                                <?php if($myPageUpdate->getZipCode01Error()){ ?>
                                    <p class="error_txt error_zip"><?=$myPageUpdate->getZipCode01Error();?></p>
                                <?php } ?>
                                <?php if($myPageUpdate->getZipCode02Error()){ ?>
                                    <p class="error_txt error_zip clear"><?=$myPageUpdate->getZipCode02Error();?></p>
                                <?php } ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">都道府県</p>
                                <div class="add_list_wrapper">
                                    <select class="add_list <?php if($myPageUpdate->getPrefectureError()){echo "error_box";}?>" name="prefecture">
                                        <option value="">都道府県を選択して下さい</option>
                                        <?php foreach(Config::PREFECTURES as $kenmei):?>
                                            <?php if($myPageUpdate->checkSelectedPrefecture($kenmei, $customer->getPrefecture())):?>
                                                <option value="<?=$kenmei?>"selected><?=$kenmei?></option>
                                            <?php else:?>
                                                <option value="<?=$kenmei?>"><?=$kenmei?></option>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <?php if($myPageUpdate->getPrefectureError()):?>
                                    <p class="error_txt memo"><?=$myPageUpdate->getPrefectureError();?></p>
                                <?php endif;?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">市区町村</p>
                                <input class="form_input_item <?php if($myPageUpdate->getCityError()){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="city" value="<?=Config::h($myPageUpdate->echoValue("city", $customer->getCity()))?>"/>
                                <?php if($myPageUpdate->getCityError()):?>
                                    <p class="error_txt memo"><?=$myPageUpdate->getCityError();?></p>
                                <?php endif;?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">番地</p>
                                <input class="form_input_item <?php if($myPageUpdate->getBlockNumberError()){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="block_number" value="<?=Config::h($myPageUpdate->echoValue("block_number", $customer->getBlockNumber()))?>"/>
                                <div class="memo_wrapper">
                                    <p class="memo">※番地漏れがないようにご注意下さい。(例)○△1-19-23</p>
                                    <?php if($myPageUpdate->getBlockNumberError()):?>
                                        <p class="error_txt memo"><?=$myPageUpdate->getBlockNumberError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">建物名</p>
                                <input class="form_input_item" type="text" maxlength="100" id="add06" name="building_name" value="<?=Config::h($myPageUpdate->echoValue("building_name", $customer->getBuildingName()))?>"/>
                                <div class="memo_wrapper">
                                    <p class="memo">※部屋番号まで記載して下さい。(例)○△マンション205</p>

                                </div>
                            </div>
                        </div>
                        <div class="register_field tel_field">
                            <div class="register_form_row">
                                <p class="register_form_title">電話番号</p>
                                <input class="form_input_item <?php if($myPageUpdate->getTelError()){echo "error_box";}?>" name="tel" type="tel" maxlength="11" oninput="value = value.replace(/[^0-9]+/i,'');" id="tel" value="<?=Config::h($myPageUpdate->echoValue("tel", $customer->getTel()))?>"/>
                                <div class="memo_wrapper">
                                    <p class="memo">※ハイフン(-)なし</p>
                                    <?php if($myPageUpdate->getTelError()):?>
                                        <p class="error_txt memo"><?=$myPageUpdate->getTelError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="register_field mail_field">
                            <div class="register_form_row">
                                <p class="register_form_title">メール</p>
                                <input class="form_input_item <?php if($myPageUpdate->getMailError()){echo "error_box";}?>" type="text" maxlength="100" name="mail" id="mail"  value="<?=Config::h($myPageUpdate->echoValue("mail", $customer->getMail()))?>"/>
                                <div class="memo_wrapper">
                                    <p class="memo">※お間違いがないか必ずご確認下さい。</p>
                                    <?php if($myPageUpdate->getMailError()):?>
                                        <p class="error_txt memo"><?=$myPageUpdate->getMailError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="register_field pass_field">
                            <p class="pass_field_text">※パスワードの変更がある場合のみ、下記の項目を入力してください。</p>
                            <div class="register_form_row">
                                <p class="register_form_title">現在のパスワード</p>
                                <input class="form_input_item <?php if($myPageUpdate->getOldPasswordError()){echo "error_box";}?>" type="password" placeholder="" name="oldPassword" maxlength="20" id="oldPassword">
                                <div class="memo_wrapper">
                                    <p class="memo">※半角英数字の組み合わせ8〜20文字</p>
                                    <?php if($myPageUpdate->getOldPasswordError()):?>
                                        <p class="error_txt memo"><?=$myPageUpdate->getOldPasswordError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">新しいパスワード</p>
                                <input class="form_input_item <?php if($myPageUpdate->getPasswordError()){echo "error_box";}?>" type="password" placeholder="" name="password" maxlength="20" id="password">
                                <div class="memo_wrapper">
                                    <p class="memo">※半角英数字の組み合わせ8〜20文字</p>
                                    <?php if($myPageUpdate->getPasswordError()):?>
                                        <p class="error_txt memo"><?=$myPageUpdate->getPasswordError();?></p>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title new_password_conf_title">新しいパスワード<br/>(再確認)</p>
                                <input class="form_input_item <?php if($myPageUpdate->getPasswordConfirmError()){echo "error_box";}?>" type="password" placeholder="" name="password_confirm" maxlength="20" id="confirm">
                                <?php if($myPageUpdate->getPasswordConfirmError()):?>
                                    <p class="error_txt memo"><?=$myPageUpdate->getPasswordConfirmError();?></p>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="register_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="submit" value="変更内容を確認する">
                            <input type="hidden" name="cmd" value="confirm">
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

