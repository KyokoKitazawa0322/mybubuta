<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");

session_cache_limiter('none');
session_start();

use \Config\Config;
$myPageDeliveryEntry = new \Controllers\MyPageDeliveryEntryAction();
$myPageDeliveryEntry->execute();
$deliveryDto = $myPageDeliveryEntry->getDeliveryDto();
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

<body class="mypage" id="del_ent">
<div class="wrapper">
    <?php require_once(__DIR__.'/../common/header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>配送先の編集</h2>
                <div class="register_wrapper">
                    <form method="POST" action="#" class="register_form">
                    <div class="register_field name_field">
                        <div class="register_form_row">
                            <p class="register_form_title">氏名</p>
                            <p class="name_label">姓</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryEntry->getLastNameError()){echo "error_box";}?>" type="text" maxlength="20" name="last_name" id="name01" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['last_name'];}else{echo $deliveryDto->getLastName();} ?>"/>
                            </div>
                            <p class="name_label">名</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryEntry->getFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="first_name" id="name02" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['first_name'];}else{echo $deliveryDto->getFirstName();} ?>"/>
                            </div>
                            <?php if($myPageDeliveryEntry->getLastNameError()):?>
                                <p class="error_txt error_cmn"><?= $myPageDeliveryEntry->getLastNameError(); ?></p>
                            <?php endif; ?>
                            <?php if($myPageDeliveryEntry->getFirstNameError()):?>
                                <p class="error_txt error_cmn clear"><?= $myPageDeliveryEntry->getFirstNameError(); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">フリガナ(カタカナ)</p>
                            <p class="name_label">セイ</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryEntry->getRubyLastNameError()){echo "error_box";}?>" type="text" maxlength="20"  name="ruby_last_name" id="name03" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['ruby_last_name'];}else{echo $deliveryDto->getRubyLastName();} ?>"/>
                            </div>
                            <p class="name_label">メイ</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryEntry->getRubyFirstNameError()){echo "error_box";}?>" type="text" maxlength="20" name="ruby_first_name" id="name04" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['ruby_first_name'];}else{echo $deliveryDto->getRubyFirstName();}?>"/>
                            </div>
                            <?php if($myPageDeliveryEntry->getRubyLastNameError()):?>
                                <p class="error_txt error_cmn"><?= $myPageDeliveryEntry->getRubyLastNameError();?></p>
                            <?php endif; ?>
                            <?php if($myPageDeliveryEntry->getRubyFirstNameError()):?>
                                <p class="error_txt error_cmn clear"><?= $myPageDeliveryEntry->getRubyFirstNameError();?></p>
                            <?php endif; ?>
                    </div>
                    </div>
                    <div class="register_field">
                        <div class="register_form_row">
                            <p class="register_form_title">郵便番号</p>
                            <div class="addr01_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryEntry->getAddress01Error()){echo "error_box";}?>" type="tel" name="address01" maxlength="3" id="add01" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['address01'];}else{echo $deliveryDto->getAddress01();}?>"/>
                            </div>
                            <span class="txt_dash">―</span>
                            <div class="addr01_input_wrapper">
                                <input class="form_input_item <?php if($myPageDeliveryEntry->getAddress02Error()){echo "error_box";}?>" type="tel" name="address02" maxlength="4" id="add02" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['address02'];}else{echo $deliveryDto->getAddress02();}?>"/>
                            </div>
                            <?php if($myPageDeliveryEntry->getAddress01Error()):?>
                                <p class="error_txt error_zip"><?= $myPageDeliveryEntry->getAddress01Error();?></p>
                            <?php endif;?>
                            <?php if($myPageDeliveryEntry->getAddress02Error()):?>
                                <p class="error_txt error_zip clear"><?= $myPageDeliveryEntry->getAddress01Error();?></p>
                            <?php endif;?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">都道府県</p>
                            <div class="add_list_wrapper">
                               <select class="add_list <?php if($myPageDeliveryEntry->getAddress03Error()){echo "error_box";}?>" name="address03">
                                    <option value="">都道府県を選択して下さい</option>
                                    <?php foreach(Config::PREFECTURES as $kenmei):?>
                                        <?php if(isset($_SESSION['del_update'])):?>
                                            <?php if($_SESSION['del_update']['address03'] == $kenmei):?>
                                                <option  value="<?=$kenmei?>"selected><?=$kenmei?></option>
                                                <?php else:?>
                                                <option  value="<?=$kenmei?>"><?=$kenmei?></option>
                                                <?php endif;?>
                                        <?php elseif($deliveryDto->getAddress03() == $kenmei):?>
                                                <option  value="<?=$kenmei?>"selected><?=$kenmei?></option>
                                        <?php else:?>
                                                <option  value="<?=$kenmei?>"><?=$kenmei?></option>   
                                        <?php endif;?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if($myPageDeliveryEntry->getAddress03Error()):?>
                                <p class="error_txt memo"><?= $myPageDeliveryEntry->getAddress03Error(); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">市区町村</p>
                            <input class="form_input_item <?php if($myPageDeliveryEntry->getAddress04Error()){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="address04" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['address04'];}else{echo $deliveryDto->getAddress04();}?>"/>
                            <?php if($myPageDeliveryEntry->getAddress04Error()):?>
                                <p class="error_txt memo"><?= $myPageDeliveryEntry->getAddress01Error();?></p>
                            <?php endif;?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">番地</p>
                            <input class="form_input_item <?php if($myPageDeliveryEntry->getAddress05Error()){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="address05" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['address05'];}else{echo $deliveryDto->getAddress05();}?>"/>
                            <div class="memo_wrapper">
                                <p class="memo">※番地漏れがないようにご注意下さい。(例)○△1-19-23</p>
                                <?php if($myPageDeliveryEntry->getAddress05Error()):?>
                                    <p class="error_txt memo"><?= $myPageDeliveryEntry->getAddress05Error();?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">建物名</p>
                            <input class="form_input_item" type="text" maxlength="100" id="add06" name="address06" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['address06'];}else{echo $deliveryDto->getAddress06();}?>"/>
                            <div class="memo_wrapper">
                                <p class="memo">※部屋番号まで記載して下さい。(例)○△マンション205</p>

                            </div>
                        </div>
                    </div>  
                    <div class="register_field tel_field">
                        <div class="register_form_row">
                            <p class="register_form_title">電話番号</p>
                            <input class="form_input_item <?php if($myPageDeliveryEntry->getTelError()){echo "error_box";}?>" name="tel" type="tel" maxlength="11" id="tel" value="<?php if(isset($_SESSION['del_update'])){echo $_SESSION['del_update']['tel'];}else{echo $deliveryDto->getTel();}?>"/>
                            <div class="memo_wrapper">
                                <p class="memo">※ハイフン(-)なし</p>
                                <?php if(isset($isTelError) && $isTelError):?>
                                    <p class="error_txt memo"><?= $myPageDeliveryEntry->getTelError(); ?></p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="register_button_wrapper">
                        <input class="btn_cmn_l btn_design_01" type="submit" value="配送先を保存する" name="cmd">
                        <input type="hidden" name="cmd" value="register_del">
                    </div> 
                </form>
                </div>
            </div>
        </div>
     <?php require_once(__DIR__.'/mypage_common.php'); ?>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
