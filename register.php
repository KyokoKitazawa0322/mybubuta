<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

if(isset($_POST['cmd']) && $_POST['cmd']=="confirm"){
    //エラーがなかったら確認画面へリダイレクト
    $_SESSION['register'] = array(
     'name01' => $_POST['name01'],
     'name02' => $_POST['name02'],
     'name03' => $_POST['name03'],
     'name04' => $_POST['name04'],
     'add01'  => $_POST['add01'],
     'add02'  => $_POST['add02'],
     'add03'  => $_POST['add03'],
     'add04'  => $_POST['add04'],
     'add05'  => $_POST['add05'],
     'add06'  => $_POST['add06'],
     'tel'    => $_POST['tel'],
     'mail'   => $_POST['mail'],
     'password' => $_POST['password'],
     'confirm' => $_POST['confirm']
    );
    $session = $_SESSION['register'];
    require_once('check_cmn.php');
    
    //メール判定
    $mailExistsErrors = mailExists($session);
    if(empty($mailExistsErrors)) {
        $isMailExistsError = false;
        } else {
            $isMailExistsError = true;
            }
    //パスワード判定

    $passErrors = passValidation($session);
    if(empty($passErrors)) {
        $isPassError = false;
        } else {
            $isPassError = true;
            }

    $passConfErrors = passConfValidation($session);
    if(empty($passConfErrors)) {
        $isPassConfError = false;
        } else {
            $isPassConfError = true;
            }
    
    if(!$isLastNameError && !$isFirstNameError && !$isRubyFirstNameError && !$isRubyLastNameError && !$isMailError && !$isMailExistsError && !$isTelError && !$isZipcodeFirstError && !$isZipcodeLastError && !$isAdd03Error && !$isAdd04Error && !$isAdd05Error && !$isPassError && !$isPassConfError) {
        header('Location:register_confirm.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品詳細｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body id="register">
<div class="wrapper">
    
    <!--　ヘッダー　-->
    <div class="header">
        <div class="header_inner">
            <div class="header_contents">
                <a href="item_list.php">
                    <img class="main_logo" src="common/img/main_logo.png">
                </a>
                <div class="header_logo_area">
                    <a href="login.php">
                        <img class="header_logo" src="common/img/header_icon_member.png">
                    </a>
                    <img class="header_logo" src="common/img/header_icon_like.png">
                    <a href="cart.php">
                        <img class="header_logo" src="common/img/header_icon_cart.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--　ヘッダーここまで　-->

    <div class="container">
        
    <!-- メインコンテンツ -->
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="register_title">
                    <h2>
                        <img class="register_logo" src="common/img/main_contents_title_register.png" alt="新規会員登録">
                    </h2>
                </div>
                <div class="register_wrapper">
					<div class="register_subtitle_wrapper">
						<h3>お客様情報入力</h3>
					</div>
                    <form method="POST" action="#" class="register_form">
 <!------------------------------------------------------------
                           名前フォーム
 -------------------------------------------------------------->
                        <div class="register_field name_field">
                            <div class="register_form_row">
                                <p class="register_form_title">氏名*</p>
                                <p class="name_label">姓</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if(isset($isLastNameError) && $isLastNameError){echo "error_box";}?>" type="text" maxlength="20" name="name01" id="name01" value="<?php if(isset($_SESSION['register']['name01'])){echo $_SESSION['register']['name01'];}?>">
                                </div>
                                <p class="name_label">名</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if(isset($isFirstNameError) && $isFirstNameError){echo "error_box";}?>" type="text" maxlength="20" name="name02" id="name02" value="<?php if(isset($_SESSION['register']['name02'])){echo $_SESSION['register']['name02'];}?>">
                                </div>
                                <?php if(isset($isLastNameError) && $isLastNameError):?>
                                    <p class="error_txt error_cmn"><?php echo htmlspecialchars($lastNameErrors); ?></p>
                                <?php endif; ?>
                                <?php if(isset($isFirstNameError) && $isFirstNameError):?>
                                    <p class="error_txt error_cmn clear"><?php echo htmlspecialchars($firstNameErrors); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">氏名(全角カナ)*</p>
                                <p class="name_label">セイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if(isset($isRubyLastNameError) && $isRubyLastNameError){echo "error_box";}?>" type="text" maxlength="20"  name="name03" id="name03" value="<?php if(isset($_SESSION['register']['name03'])){echo $_SESSION['register']['name03'];}?>">
                                </div>
                                <p class="name_label">メイ</p>
                                <div class="name_input_wrapper">
                                    <input class="form_input_item <?php if(isset($isRubyFirstNameError) && $isRubyFirstNameError){echo "error_box";}?>" type="text" maxlength="20" name="name04" id="name04" value="<?php if(isset($_SESSION['register']['name04'])){echo $_SESSION['register']['name04'];}?>">
                                </div>
                                <?php if(isset($isRubyLastNameError) && $isRubyLastNameError):?>
                                    <p class="error_txt error_cmn"><?php echo htmlspecialchars($rubyLastNameErrors); ?></p>
                                <?php endif; ?>
                                <?php if(isset($isRubyFirstNameError) && $isRubyFirstNameError):?>
                                    <p class="error_txt error_cmn clear"><?php echo htmlspecialchars($rubyFirstNameErrors); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
     <!------------------------------------------------------------
                               住所フォーム
	 -------------------------------------------------------------->                                              
                        <div class="register_field">
                            <div class="register_form_row">
                                <p class="register_form_title">郵便番号*</p>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if(isset($isZipcodeFirstError) && $isZipcodeFirstError){echo "error_box";}?>" type="tel" name="add01" maxlength="3" id="add01" value="<?php if(isset($_SESSION['register']['add01'])){echo $_SESSION['register']['add01'];}?>">
                                </div>
                                <span class="txt_dash">―</span>
                                <div class="addr01_input_wrapper">
                                    <input class="form_input_item <?php if(isset($isZipcodeLastError) && $isZipcodeLastError){echo "error_box";}?>" type="tel" name="add02" maxlength="4" id="add02" value="<?php if(isset($_SESSION['register']['add02'])){echo $_SESSION['register']['add02'];}?>">
                                </div>
                                <?php if(isset($isZipcodeFirstError) && $isZipcodeFirstError){ ?>
                                    <p class="error_txt error_zip"><?php echo htmlspecialchars($zipcodeFirstErrors); ?></p>
                                <?php } ?>
                                <?php if(isset($isZipcodeLastError) && $isZipcodeLastError){ ?>
                                    <p class="error_txt error_zip clear"><?php echo htmlspecialchars($zipcodeLastErrors); ?></p>
                                <?php } ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">都道府県*</p>
                                <div class="add_list_wrapper">
                                    <select class="add_list <?php if(isset($isAdd03Error) && $isAdd03Error){echo "error_box";}?>" name="add03">
                                        <option value="">都道府県を選択して下さい</option>
                                        <?php
                                        $all = array('北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県','茨城県','栃木県','群馬県', '埼玉県','千葉県', '東京都', '神奈川県','新潟県',' 富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県', '山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');
                                        foreach($all as $kenmei) {
                                            if(isset($_SESSION['register']['add03']) && $_SESSION['register']['add03'] == $kenmei){
                                            print('<option  value="'.$kenmei.'" selected>'.$kenmei.'</option>');
                                            }else{
                                            print('<option  value="'.$kenmei.'">'.$kenmei.'</option>');
                                            }
                                        }
                                    ?>
                                    </select>
                                </div>		
                            <?php if(isset($isAdd03Error) && $isAdd03Error):?>
                                <p class="error_txt memo"><?php echo htmlspecialchars($add03Errors); ?></p>
                            <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">市区町村*</p>
                                <input class="form_input_item <?php if(isset($isAdd04Error) && $isAdd04Error){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="add04"  value="<?php if(isset($_SESSION['register']['add04'])){echo $_SESSION['register']['add04'];}?>">
                                <?php if(isset($isAdd04Error) && $isAdd04Error):?>
                                    <p class="error_txt memo"><?php echo htmlspecialchars($add04Errors); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">番地*</p>
                                <input class="form_input_item <?php if(isset($isAdd05Error) && $isAdd05Error){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="add05" value="<?php if(isset($_SESSION['register']['add05'])){echo $_SESSION['register']['add05'];}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※番地漏れがないようにご注意下さい。(例)○△1-19-23</p>	
                                    <?php if(isset($isAdd05Error) && $isAdd05Error):?>
                                        <p class="error_txt memo"><?php echo htmlspecialchars($add05Errors); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">建物名</p>
                                <input class="form_input_item" type="text" maxlength="100" id="add06" name="add06" value="<?php if(isset($_SESSION['register']['add06'])){echo $_SESSION['register']['add06'];}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※部屋番号まで記載して下さい。(例)○△マンション205</p>

                                </div>
                            </div>
                        </div>

     <!------------------------------------------------------------
                               電話フォーム
	 -------------------------------------------------------------->   
                        <div class="register_field tel_field">
                            <div class="register_form_row">
                                <p class="register_form_title">電話番号*</p>
                                <input class="form_input_item <?php if(isset($isTelError) && $isTelError){echo "error_box";}?>" name="tel" type="tel" maxlength="11" id="tel" value="<?php if(isset($_SESSION['register']['tel'])){echo $_SESSION['register']['tel'];}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※ハイフン(-)なし</p>
                                    <?php if(isset($isTelError) && $isTelError):?>
                                        <p class="error_txt memo"><?php echo htmlspecialchars($telErrors); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
     <!------------------------------------------------------------
                               メールフォーム
	 -------------------------------------------------------------->
                        <div class="register_field mail_field">
                            <div class="register_form_row">
                                <p class="register_form_title">メール*</p>
                                <input class="form_input_item <?php if(isset($isMailError) && $isMailError){echo "error_box";}?>" type="text" maxlength="100" name="mail" id="mail" value="<?php if(isset($_SESSION['register']['mail'])){echo $_SESSION['register']['mail'];}?>">
                                <div class="memo_wrapper">
                                    <p class="memo">※お間違いがないか必ずご確認下さい。</p>
                                    <?php if(isset($isMailError) && $isMailError):?>
                                        <p class="error_txt memo"><?php echo htmlspecialchars($mailErrors); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($isMailExistsError) && $isMailExistsError):?>
                                        <p class="error_txt memo"><?php echo htmlspecialchars($mailExistsErrors); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
     <!------------------------------------------------------------
                               パスワードフォーム
	 -------------------------------------------------------------->
                        <div class="register_field pass_field">
                            <div class="register_form_row">
                                <p class="register_form_title">パスワード*</p>
                                <input class="form_input_item <?php if(isset($isPassError) && $isPassError){echo "error_box";}?>" type="password" placeholder="" name="password" maxlength="20" id="password">
                                <div class="memo_wrapper">
                                    <p class="memo">※半角英数字の組み合わせ8〜20文字</p>
                                    <?php if(isset($isPassError) && $isPassError):?>
                                        <p class="error_txt memo"><?php echo htmlspecialchars($passErrors); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">パスワード(再確認)*</p>
                                <input class="form_input_item <?php if(isset($isPassConfError) && $isPassConfError){echo "error_box";}?>" type="password" placeholder="" name="confirm" maxlength="20" id="confirm">
                                <?php if(isset($isPassConfError) && $isPassConfError):?>
                                    <p class="error_txt memo"><?php echo htmlspecialchars($passConfErrors); ?></p>
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
