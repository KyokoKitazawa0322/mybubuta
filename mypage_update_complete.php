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

if(isset($_POST["cmd"])){
    
    $sql ="UPDATE customers SET last_name=:last_name, first_name=:first_name, ruby_last_name=:ruby_last_name, ruby_first_name=:ruby_first_name, address_01=:address_01, address_02=:address_02, address_03=:address_03, address_04=:address_04, address_05=:address_05, address_06=:address_06, tel=:tel, mail=:mail, hash_password=:hash_password, customer_updated_date = now() where customer_id=:customer_id";
 
    $hash_pass = password_hash($_SESSION['update']['password'], PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(':last_name', $_SESSION['update']['name01']);
    $stmt->bindvalue(':first_name', $_SESSION['update']['name02']);
    $stmt->bindvalue(':ruby_last_name', $_SESSION['update']['name03']);
    $stmt->bindvalue(':ruby_first_name', $_SESSION['update']['name04']);
    $stmt->bindvalue(':address_01', $_SESSION['update']['add01']);
    $stmt->bindvalue(':address_02', $_SESSION['update']['add02']);
    $stmt->bindvalue(':address_03', $_SESSION['update']['add03']);
    $stmt->bindvalue(':address_04', $_SESSION['update']['add04']);
    $stmt->bindvalue(':address_05', $_SESSION['update']['add05']);
    $stmt->bindvalue(':address_06', $_SESSION['update']['add06']);
    $stmt->bindvalue(':tel', $_SESSION['update']['tel']);
    $stmt->bindvalue(':mail', $_SESSION['update']['mail']);
    $stmt->bindvalue(':hash_password', $hash_pass);
    $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
    $res = $stmt->execute();
}
/**--------------------------------------------------------
 * order_delivery_listからきた場合
 ---------------------------------------------------------*/
if(isset($_SESSION['from_order_flag'])){
    header('Location:order_delivery_list.php');
    $_SESSION['from_order_flag']=NULL;
    exit;
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
<script type="text/javascript">
<!--
$(function(){
 history.pushState(null, null, null); //ブラウザバック無効化
 //ブラウザバックボタン押下時
 $(window).on("popstate", function (event) {
  window.location.replace('mypage.php');
 });
});
// --> 
</script>
</head>

<body class="mypage update_confirm">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
     <?php require_once('mypage_common.php'); ?>
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>登録内容の確認・変更</h2>
                <div class="register_wrapper">
                    <div class="txt_wrapper">
                        <p>会員登録が完了しました。</p>
                        
                        <div class="complete_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="button" value="マイページ" onClick="location.href='mypage.php'" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved. </p>
    </div>
</div>
</body>
</html>

