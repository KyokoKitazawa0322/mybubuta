<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();
/**--------------------------------------------------------
 * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
 ---------------------------------------------------------*/
     if(!isset($_SESSION['customer_id'])){
        header('Location:login.php');
        exit();
    }
/**--------------------------------------------------------
　　order_delivery_list.phpからきた場合
 ---------------------------------------------------------*/
    if(isset($_POST['cmd'])&&$_POST['cmd']=="from_order"){
        $_SESSION['from_order_flag']=$_POST['cmd'];   
    }

/**--------------------------------------------------------
 * 配送先の保存ボタンがおされたときの処理
 ---------------------------------------------------------*/
if(isset($_POST['cmd'])&& $_POST['cmd']=='add'){
      $_SESSION['del_add'] = array(
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
     'tel'    => $_POST['tel']
   );
    $session = $_SESSION['del_add'];
    require_once('check_cmn.php');
    
    if(!$isLastNameError && !$isFirstNameError && !$isRubyFirstNameError && !$isRubyLastNameError && !$isTelError && !$isZipcodeFirstError && !$isZipcodeLastError && !$isAdd03Error && !$isAdd04Error && !$isAdd05Error) {
        $_SESSION['add_data'] = 1;
        header('Location:mypage_delivery_add_comp.php');
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body class="mypage" id="">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
     <?php require_once('mypage_common.php'); ?>
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>配送先の登録</h2>
                <div class="register_wrapper">
                    <form method="POST" action="#" class="register_form">
  <!------------------------------------------------------------
                           名前フォーム
 -------------------------------------------------------------->
                    <div class="register_field name_field">
                        <div class="register_form_row">
                            <p class="register_form_title">氏名</p>
                            <p class="name_label">姓</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if(isset($isLastNameError) && $isLastNameError){echo "error_box";}?>" type="text" maxlength="20" name="name01" id="name01" value="<?php if(isset($_SESSION['del_add']['name01'])){echo $_SESSION['del_add']['name01'];} ?>"/>
                            </div>
                            <p class="name_label">名</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if(isset($isFirstNameError) && $isFirstNameError){echo "error_box";}?>" type="text" maxlength="20" name="name02" id="name02" value="<?php if(isset($_SESSION['del_add']['name02'])){echo $_SESSION['del_add']['name02'];}?>"/>
                            </div>
                            <?php if(isset($isLastNameError) && $isLastNameError):?>
                                <p class="error_txt error_cmn"><?php echo htmlspecialchars($lastNameErrors); ?></p>
                            <?php endif; ?>
                            <?php if(isset($isFirstNameError) && $isFirstNameError):?>
                                <p class="error_txt error_cmn clear"><?php echo htmlspecialchars($firstNameErrors); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">フリガナ(カタカナ)</p>
                            <p class="name_label">セイ</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if(isset($isRubyLastNameError) && $isRubyLastNameError){echo "error_box";}?>" type="text" maxlength="20"  name="name03" id="name03" value="<?php if(isset($_SESSION['del_add']['name03'])){echo $_SESSION['del_add']['name03'];}?>"/>
                            </div>
                            <p class="name_label">メイ</p>
                            <div class="name_input_wrapper">
                                <input class="form_input_item <?php if(isset($isRubyFirstNameError) && $isRubyFirstNameError){echo "error_box";}?>" type="text" maxlength="20" name="name04" id="name04" value="<?php if(isset($_SESSION['del_add']['name04'])){echo $_SESSION['del_add']['name04'];}?>"/>
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
                            <p class="register_form_title">郵便番号</p>
                            <div class="addr01_input_wrapper">
                                <input class="form_input_item <?php if(isset($isZipcodeFirstError) && $isZipcodeFirstError){echo "error_box";}?>" type="tel" name="add01" maxlength="3" id="add01" value="<?php if(isset($_SESSION['del_add']['add01'])){echo $_SESSION['del_add']['add01'];}?>"/>
                            </div>
                            <span class="txt_dash">―</span>
                            <div class="addr01_input_wrapper">
                                <input class="form_input_item <?php if(isset($isZipcodeLastError) && $isZipcodeLastError){echo "error_box";}?>" type="tel" name="add02" maxlength="4" id="add02" value="<?php if(isset($_SESSION['del_add']['add02'])){echo $_SESSION['del_add']['add02'];}?>"/>
                            </div>
                            <?php if(isset($isZipcodeFirstError) && $isZipcodeFirstError){ ?>
                                <p class="error_txt error_zip"><?php echo htmlspecialchars($zipcodeFirstErrors); ?></p>
                            <?php } ?>
                            <?php if(isset($isZipcodeLastError) && $isZipcodeLastError){ ?>
                                <p class="error_txt error_zip clear"><?php echo htmlspecialchars($zipcodeLastErrors); ?></p>
                            <?php } ?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">都道府県</p>
                            <div class="add_list_wrapper">
                               <select class="add_list <?php if(isset($isAdd03Error) && $isAdd03Error){echo "error_box";}?>" name="add03">
                                    <option value="">都道府県を選択して下さい</option>
                                    <?php
                                    $all = array('北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県','茨城県','栃木県','群馬県', '埼玉県','千葉県', '東京都', '神奈川県','新潟県',' 富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県', '山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');
                                    foreach($all as $kenmei) {
                                        if(isset($_SESSION['del_add'])){
                                            if($_SESSION['del_add']['add03'] == $kenmei){
                                                print('<option  value="'.$kenmei.'"selected>'.$kenmei.'</option>');
                                            }else{
                                                 print('<option value="'.$kenmei.'">'.$kenmei.'</option>');
                                            }
                                        }else { 
                                                 print('<option value="'.$kenmei.'">'.$kenmei.'</option>');    
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
                            <p class="register_form_title">市区町村</p>
                            <input class="form_input_item <?php if(isset($isAdd04Error) && $isAdd04Error){echo "error_box";}?>" type="text" maxlength="50" id="add04" name="add04" value="<?php if(isset($_SESSION['del_add'])){echo $_SESSION['del_add']['add04'];}?>"/>
                            <?php if(isset($isAdd04Error) && $isAdd04Error):?>
                                <p class="error_txt memo"><?php echo htmlspecialchars($add04Errors); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">番地</p>
                            <input class="form_input_item <?php if(isset($isAdd05Error) && $isAdd05Error){echo "error_box";}?>" type="text" maxlength="50" id="add05" name="add05" value="<?php if(isset($_SESSION['del_add'])){echo $_SESSION['del_add']['add05'];}?>"/>
                            <div class="memo_wrapper">
                                <p class="memo">※番地漏れがないようにご注意下さい。(例)○△1-19-23</p>
                                <?php if(isset($isAdd05Error) && $isAdd05Error):?>
                                    <p class="error_txt memo"><?php echo htmlspecialchars($add05Errors); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="register_form_row">
                            <p class="register_form_title">建物名</p>
                            <input class="form_input_item" type="text" maxlength="100" id="add06" name="add06" value="<?php if(isset($_SESSION['del_add'])){echo $_SESSION['del_add']['add06'];}?>"/>
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
                            <p class="register_form_title">電話番号</p>
                            <input class="form_input_item <?php if(isset($isTelError) && $isTelError){echo "error_box";}?>" name="tel" type="tel" maxlength="11" id="tel" value="<?php if(isset($_SESSION['del_add'])){echo $_SESSION['del_add']['tel'];}?>"/>
                            <div class="memo_wrapper">
                                <p class="memo">※ハイフン(-)なし</p>
                                <?php if(isset($isTelError) && $isTelError):?>
                                    <p class="error_txt memo"><?php echo htmlspecialchars($telErrors); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
<!------------------------------------------------------------
                      会員登録ボタン
-------------------------------------------------------------->
                    <div class="register_button_wrapper">
                        <input class="btn_cmn_l btn_design_01" type="submit" value="配送先を保存する" name="cmd">
                        <input type="hidden" name="cmd" value="add">
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

