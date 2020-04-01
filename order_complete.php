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

//リロード対策
if (/*isset($_POST['reload']) && */$_SESSION['user']['reload'] == $_POST['reload']) {
    //一致するならセッションデータ削除
    $_SESSION['user']['reload'] = "";    
    //一致したとき（初回訪問）の処理
/**--------------------------------------------------------
 *　order_comfirm.phpで注文確定ボタンがおされたときの処理
 ---------------------------------------------------------*/
if(isset($_POST['cmd']) && $_POST['cmd'] == "order_comp"){
    if(!isset($_SESSION['pay'])){
        header('Location:order_confirm.php');
        $_SESSION['isPay'] = "none";
        exit();
    }
    //配送先情報を連結して変数に格納
    $sql ="insert into order_history(customer_id, total_payment, total_amount, tax, postage, payment, delivery_name, delivery_addr, delivery_post, delivery_tel, purchase_date)values(?,?,?,?,?,?,?,?,?,?,now())";
        
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['customer_id']);
    $stmt->bindvalue(2, $_SESSION['total_payment']);
    $stmt->bindvalue(3, $_SESSION['total_amount']);
    $stmt->bindvalue(4, $_SESSION['tax']);
    $stmt->bindvalue(5, $_SESSION['postage']);
    $stmt->bindvalue(6, $_SESSION['payment']);
    $stmt->bindvalue(7, $_SESSION['name']);
    $stmt->bindvalue(8, $_SESSION['address']);
    $stmt->bindvalue(9, $_SESSION['post']);
    $stmt->bindvalue(10,$_SESSION['tel']);
    $result = $stmt->execute();
    
    //INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録
    $sql = "SELECT order_id FROM order_history where customer_id = ? order by purchase_date DESC LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['customer_id']);
    $stmt->execute();
    $result = $stmt->fetch();
    if($result){
        $order_id = $result['order_id'];
        foreach($_SESSION['cart'] as $cart){
            $sql ="INSERT into order_detail(order_id, item_code, item_count)values(?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindvalue(1, $order_id);
            $stmt->bindvalue(2, $cart['item_code']);
            $stmt->bindvalue(3, $cart['item_count']);
            $result = $stmt->execute();
        }
    }
    unset($_SESSION['cart']);
    unset($_SESSION['pay']);
    unset($_SESSION['total_payment']);
    unset($_SESSION['total_amount']);
    unset($_SESSION['tax']);
    unset($_SESSION['postage']);
    unset($_SESSION['payment']);
    unset($_SESSION['name']);
    unset($_SESSION['address']);
    unset($_SESSION['post']);
    unset($_SESSION['tel']);
    unset($_SESSION['def_addr']);
    unset($_SESSION['isPay']);
}else{
    header('Location:login.php');
    exit();  
}
$con->close();
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
<body id="order_complete">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="cart_title">
                    <h2>
                        <img class="product_logo" src="common/img/main_contents_title_cart.png" alt="カートの中">
                    </h2>
                </div>
                <div class="main_contents_inner">
                    <p>ご注文ありがとうございました。<br/>
                    商品の到着まで今しばらくお待ちください。</p>
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
}
else{
    header('Location:login.php');
    exit();
}
?>
