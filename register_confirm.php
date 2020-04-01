<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();
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
<body id="register_confirm">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
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
                    <div class="register_form">
                        <div class="register_field name_field">
                            <div class="register_form_row">
                                <p class="register_form_title">氏名</p>
                                <p class="name_label">姓:</p>
                                <div class="name_input_wrapper">
                                    <p><?php echo $_SESSION['register']['name01'];?></p>
                                </div>
                                <p class="name_label">名:</p>
                                <div class="name_input_wrapper">
                                    <p><?php echo $_SESSION['register']['name02'];?></p>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">フリガナ(カタカナ)</p>
                                <p class="name_label">セイ:</p>
                                <div class="name_input_wrapper">
                                    <p><?php echo $_SESSION['register']['name03'];?></p>
                                </div>
                                <p class="name_label">メイ:</p>
                                <div class="name_input_wrapper">
                                    <p><?php echo $_SESSION['register']['name04'];?></p>
                                </div>
                            </div>
                        </div>  
                        <div class="register_field">
                            <div class="register_form_row">
                                <p class="register_form_title">郵便番号</p>
                                <div class="addr01_input_wrapper">
                                    <p><?php echo $_SESSION['register']['add01'];?></p>
                                </div>
                                <span class="txt_dash">－</span>
                                <div class="addr01_input_wrapper">
                                    <p><?php echo $_SESSION['register']['add02'];?></p>
                                </div>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">都道府県</p>
                                <div class="add_list_wrapper">
                                   <p><?php echo $_SESSION['register']['add03'];?></p>
                                </div>		
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">市区町村</p>
                                <p><?php echo $_SESSION['register']['add04'];?></p>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">番地</p>
                                <p><?php echo $_SESSION['register']['add05'];?></p>
                            </div>
                            <div class="register_form_row">
                                <p class="register_form_title">建物名</p>
                                <p><?php echo $_SESSION['register']['add06'];?></p>
                            </div>
                        </div>                     
                        <div class="register_field tel_field">
                            <div class="register_form_row">
                                <p class="register_form_title">電話番号</p>
                                <p><?php echo $_SESSION['register']['tel'];?></p>
                            </div>
                        </div>
                        <div class="register_field mail_field">
                            <div class="register_form_row">
                                <p class="register_form_title">メール</p>
                                <p><?php echo $_SESSION['register']['mail'];?></p>
                            </div>
                        </div>
                    <div class="register_field pass_field">
                            <div class="register_form_row">
                                <p class="register_form_title">パスワード</p>
                                <div class="pass_icon">
                                    <p>
<?php
for ($i = 0; $i < strlen($_SESSION['register']['password']); $i++) {
    echo '●';
}
echo PHP_EOL;
?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="confirm_button_wrapper">
                            <div class="confirm_button_inner">
                                <input class="btn_cmn_mid btn_design_03" name="cmd" type="button" onClick="history.back()" value="前の画面に戻る"/>
                            </div>
                            <form action="register_complete.php" method="POST">
                                <div class="confirm_button_inner">
                                    <input class="register_button btn_design_01" name="cmd" type="submit" value="この内容で登録する"/>
                                    <input type="hidden" name="cmd" value="do_register">
                                </div>
<?php  //リロード対策
$_SESSION['user']['reload'] = "hoge";
$reload_off = $_SESSION['user']['reload'];
print <<<EOF
<input type="hidden" name="reload" value="$reload_off" />
EOF;
?>
                            </form>
                        </div>  
                    </div>
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

