<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

//リロード対策
if (isset($_POST['reload'])&&$_SESSION['user']['reload'] == $_POST['reload']) {
    //一致するならセッションデータ削除
    $_SESSION['user']['reload'] = "";    
    //一致したとき（初回訪問）の処理
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

<body id="register_complete">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="register_title">
                    <h2>
                        <img class="register_logo" src="common/img/main_contents_title_register.png" alt="新規会員登録">
                    </h2>
                    <div class="txt_wrapper">
                        <p>会員登録が完了しました。</p>
<?php   

    if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_register" ){

    $sql ="insert into customers(last_name, first_name, ruby_last_name, ruby_first_name, address_01, address_02, address_03, address_04, address_05, address_06, tel, mail, hash_password, del_flag, customer_insert_date)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())";
    
    $hash_pass = password_hash($_SESSION['register']['password'], PASSWORD_DEFAULT);
                
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['register']['name01']);
    $stmt->bindvalue(2, $_SESSION['register']['name02']);
    $stmt->bindvalue(3, $_SESSION['register']['name03']);
    $stmt->bindvalue(4, $_SESSION['register']['name04']);
    $stmt->bindvalue(5, $_SESSION['register']['add01']);
    $stmt->bindvalue(6, $_SESSION['register']['add02']);
    $stmt->bindvalue(7, $_SESSION['register']['add03']);
    $stmt->bindvalue(8, $_SESSION['register']['add04']);
    $stmt->bindvalue(9, $_SESSION['register']['add05']);
    $stmt->bindvalue(10, $_SESSION['register']['add06']);
    $stmt->bindvalue(11, $_SESSION['register']['tel']);
    $stmt->bindvalue(12, $_SESSION['register']['mail']);
    $stmt->bindvalue(13, $hash_pass);
    $stmt->bindvalue(14, '0');
    $stmt->execute();
  
    $sql = "SELECT * FROM customers WHERE mail=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['register']['mail']);
    $stmt->execute();
    if($result = $stmt->fetch()){ 
    $_SESSION['customer_id'] = $result['customer_id'];
    }
}
$con->close();
unset($_SESSION['register']);         
?>
                        <div class="complete_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="button" value="マイページ" onClick="location.href='mypage.php'" />
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
<?php
}else{
    header("Location:login.php");   
}
?>
     