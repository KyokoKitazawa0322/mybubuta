<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

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
                                    <input class="form_input_item <?php if($register->getLastNameError()){echo "error_box";}?>" type="text" maxlength="20" name="last_name" value="<?php if(isset($_SESSION['register']['last_name'])){echo h($_SESSION['register']['last_name']);}?>">
                                </div>
                                <p class="name_label">名</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="first_name" value="<?php if(isset($_SESSION['register']['first_name'])){echo h($_SESSION['register']['first_name']);}?>">
                                </div>
                                <?php if($register->getLastNameError()):?>
                                    <p class="error_txt error_cmn"><?= $register->getLastNameError();?></p>
                                <?php endif; ?>
                                <?php if($register->getFirstNameError()):?>
                                    <p class="error_txt error_cmn"><?= $register->getFirstNameError();?></p>
                                <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">氏名(全角カナ)*</p>
                                <p class="name_label">セイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getRubyLastNameError()){echo "error_box";}?>" type="text" maxlength="20"  name="ruby_last_name" value="<?php if(isset($_SESSION['register']['ruby_last_name'])){echo h($_SESSION['register']['ruby_last_name']);}?>">
                                </div>
                                <p class="name_label">メイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if($register->getRubyFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="ruby_first_name" value="<?php if(isset($_SESSION['register']['ruby_first_name'])){echo h($_SESSION['register']['ruby_first_name']);}?>">
                                </div>
                                <?php if($register->getRubyLastNameError()):?>
                                    <p class="error_txt error_cmn"><?= $register->getRubyLastNameError(); ?></p>
                                <?php endif; ?>
                                <?php if($register->getRubyFirstNameError()):?>
                                    <p class="error_txt error_cmn"><?= $register->getRubyFirstNameError(); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>                                         
                        <div class="register_field">
                            <div class="register_form_row">
                                <p class="register_form_title">郵便番号*</p>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if($register->getAddress01Error()){echo "error_box";}?>" type="tel" name="address01" maxlength="3" id="add01" value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['address01']);}?>">
                                </div>
                                <span class="txt_dash">―</span>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if($register->getAddress02Error()){echo "error_box";}?>" type="tel" name="address02" maxlength="4" id="add02" value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['address02']);}?>">
                                </div>
                                <?php if($register->getAddress01Error()):?>
                                    <p class="error_txt memo zip_memo"><?= $register->getaddress01Error(); ?></p>
                                <?php endif; ?>
                                <?php if($register->getAddress02Error()):?>
                                    <p class="error_txt memo"><?= $register->getaddress02Error(); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">都道府県*</p>
                                <div class="add_list_wrapper">
                                    <select class="add_list <?php if($register->getAddress03Error()){echo "error_box";}?>" id="add03" name="address03">
                                        <option value="">都道府県を選択して下さい</option>
                                        <?php foreach(Config::PREFECTURES as $kenmei):?>
                                            <?php if(isset($_SESSION['register']) && $_SESSION['register']['address03'] == $kenmei):?>
                                                <option  value="<?=$kenmei?>"selected><?=$kenmei?></option>
                                            <?php else:?>
                                            <option  value="<?=$kenmei?>"><?=$kenmei?></option>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                </div>		
                            <?php if($register->getAddress03Error()):?>
                                <p class="error_txt memo"><?= $register->getaddress03Error(); ?></p>
                            <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">市区町村*</p>
                                <input class="form_input_item <?php if($register->getAddress04Error()){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="address04"  value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['address04']);}?>">
                                <?php if($register->getAddress04Error()):?>
                                    <p class="error_txt memo"><?= $register->getaddress04Error(); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">番地*</p>
                                <input class="form_input_item <?php if($register->getAddress05Error()){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="address05" value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['address05']);}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※番地漏れがないようにご注意下さい。(例)○△1-19-23</p>	
                                    <?php if($register->getAddress05Error()):?>
                                        <p class="error_txt memo"><?= $register->getaddress05Error(); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">建物名</p>
                                <input class="form_input_item" type="text" maxlength="100" name="address06" id="add06" value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['address06']);}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※部屋番号まで記載して下さい。(例)○△マンション205</p>

                                </div>
                            </div>
                        </div>
                        <div class="register_field tel_field">
                            <div class="register_form_row">
                                <p class="register_form_title">電話番号*</p>
                                <input class="form_input_item <?php if($register->getTelError()){echo "error_box";}?>" name="tel" type="tel" maxlength="11" id="tel" value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['tel']);}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※ハイフン(-)なし</p>
                                    <?php if($register->getTelError()):?>
                                        <p class="error_txt memo"><?= $register->getTelError(); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="register_field mail_field">
                            <div class="register_form_row">
                                <p class="register_form_title">メール*</p>
                                <input class="form_input_item <?php if($register->getMailError()){echo "error_box";}?>" type="text" maxlength="100" name="mail" id="mail" value="<?php if(isset($_SESSION['register'])){echo h($_SESSION['register']['mail']);}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※お間違いがないか必ずご確認下さい。</p>
                                    <?php if($register->getMailError()):?>
                                        <p class="error_txt memo"><?= $register->getMailError(); ?></p>
                                    <?php endif; ?>
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
                                        <p class="error_txt memo"><?= $register->getPasswordError(); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">パスワード(再確認)*</p>
                                <input class="form_input_item <?php if($register->getPasswordConfirmError()){echo "error_box";}?>" type="password" placeholder="" name="passwordConfirm" id="confirm" maxlength="20">
                                <?php if($register->getPasswordConfirmError()):?>
                                    <p class="error_txt memo"><?= $register->getPasswordConfirmError(); ?></p>
                                <?php endif; ?>
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
