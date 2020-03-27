<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

/**-------------------------------------------------------
   前ページ情報をセッションへ格納
 ---------------------------------------------------------*/
if(!empty($_SESSION["cart"])){
if(isset($_POST['cmd']) && $_POST['cmd']=="order_confirm") 
{
    $_SESSION['total_amount'] = $_POST['total_amount'];//商品点数
    $_SESSION['total_payment'] = $_POST['total_payment'];//商品代金合計(税込)
    $_SESSION['postage'] = $_POST['postage'];//送料(税込)
    $_SESSION['tax'] = $_POST['tax'];//内消費税

    //商品点数（個別）
    $var = 1;
    for($i = 0 ; $i<count($_SESSION["cart"]); $i++ ){
        $_SESSION["cart"][$i]['item_count'] = $_POST["cart{$var}"];
        $var++;
        }

    //前画面のデータをセッションに格納したのち、非ログイン状態の場合はフラグをたててログイン画面へ。
     if(!isset($_SESSION['customer_id'])){
        $_SESSION['order_flag'] = 1;
        header('Location:login.php');
        exit();
    }
}

$sql = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);
$stmt->execute();
$res = $stmt->fetch();
$def_delFlag = $res['del_flag'];

if($def_delFlag !== "0"){    
    $sql = "SELECT * FROM delivery WHERE customer_id = ? && del_flag =?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['customer_id']);
    $stmt->bindvalue(2, "0");
    $stmt->execute();
    $res = $stmt->fetch();
}
    $_SESSION['name'] = $res['last_name'].$res['first_name'];
    $_SESSION['post'] = $res['address_01']."-".$res['address_02'];
    $_SESSION['address'] = $res['address_03'].$res['address_04'].$res['address_05'].$res['address_06'];
    $_SESSION['tel'] = $res['tel'];
    
/**--------------------------------------------------------
   配送先確定ボタンがおされたときの処理
 ---------------------------------------------------------*/
if(isset($_POST['cmd']) && $_POST['cmd']=="del_comp") {
    $_SESSION['def_addr'] = $_POST['def_addr'];
    if($_POST['def_addr']!=="1") {
        $sql = "SELECT * FROM delivery WHERE customer_id=? && delivery_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(1, $_SESSION['customer_id']);
        $stmt->bindvalue(2, $_POST['def_addr']);
        $stmt->execute();
        $res = $stmt->fetch();
    } else {
        $sql = "SELECT * FROM customers WHERE customer_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(1, $_SESSION['customer_id']);
        $stmt->execute();
        $res = $stmt->fetch();
    }
        $_SESSION['name'] = $res['last_name'].$res['first_name'];
        $_SESSION['post'] = $res['address_01']."-".$res['address_02'];
        $_SESSION['address'] = $res['address_03'].$res['address_04'].$res['address_05'].$res['address_06'];
        $_SESSION['tel'] = $res['tel'];
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品詳細｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    
</head>
<body id="order_confirm">
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
                    <a href="mypage_favorite.php">
                        <img class="header_logo" src="common/img/header_icon_like.png">
                    </a>
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
                <div class="cart_title">
                    <h2>
                        <img class="product_logo" src="common/img/main_contents_title_cart.png" alt="カートの中">
                    </h2>
                </div>
                <div class="main_contents_inner">
                    <div class="cart_item_box"> 
<?php
if(isset($_POST['cmd']) && $_POST['cmd']=="pay_comp") {
    unset($_SESSION['isPay']);
}
if(isset($_SESSION['isPay'])){
        echo <<<EOM
            <h4 style="color:red;">決済方法が未選択です。</h4>
        EOM;            
}
?>
                        <h3 class="ttl_cmn">配送先住所</h3>
                        <div class="shipping_box_wrap">
                            <div class="shipping_box">
                                <dl class="shipping_address">
                                    <dt>名前：</dt>
                                    <dd><?php echo $_SESSION['name'];?></dd>
                                    <dt>郵便番号：</dt>
                                    <dd><?php echo $_SESSION['post'];?></dd>
                                    <dt>電話番号：</dt>
                                    <dd><?php echo $_SESSION['tel'];?></dd>
                                    <dt>住所：</dt>
                                    <dd><?php echo $_SESSION['address'];?></dd>
                                </dl>
                            </div>
                            <div class="update_link_wrap">
                                <a class="btn_cmn_01 btn_design_03" href="order_delivery_list.php">変更</a>
                            </div>
                        </div>
                        <h3 class="ttl_cmn">決済方法</h3>
                        <div class="shipping_box_wrap">
                            <div class="shipping_box">
<?php
/**--------------------------------------------------------
   決済方法確定ボタンが押されたときの処理
 ---------------------------------------------------------*/
if(isset($_POST['cmd']) && $_POST['cmd']=="pay_comp") {
        unset($_SESSION['pay_error']);
        unset($_SESSION['isPay']);
        unset($_SESSION['payment']);
    if(!isset($_POST['payTypeSelect'])){
        $_SESSION['pay_error'] = "is";
        header('Location:order_pay_list.php');
        exit();   
    }
    
    $_SESSION['pay'] = $_POST['payTypeSelect'];
    if($_POST['payTypeSelect']=="1") {
        $_SESSION['payment'] = "クレジットカード";
    } elseif ($_POST['payTypeSelect']=="2") {
        $_SESSION['payment'] = "代引き";
    } else{
        $_SESSION['payment'] = "銀行振込";
    } 
}
    
if(isset($_SESSION['payment'])){
    if($_SESSION['payment'] == "クレジットカード"){
        echo <<<EOM
            <h4>クレジットカード</h4> 
        EOM;
    }elseif($_SESSION['payment'] == "代引き"){
        echo <<<EOM
            <h4>代引き</h4>
            <p>※代引き手数料：210円</p>
        EOM;      
    }elseif($_SESSION['payment'] == "銀行振込"){
        echo <<<EOM
            <h4>銀行振込</h4>
            <p>銀行振込手数料はお客様ご負担となります。</p>
        EOM;
    }
}else{
        echo <<<EOM
            <h4>決済方法を選択してください。</h4>
        EOM;
    }
?>
                            </div>
                            <div class="update_link_wrap">
                                <a class="btn_cmn_01 btn_design_03" href="order_pay_list.php">変更</a>
                            </div>
                        </div>
                        <h3 class="ttl_cmn last_ttl_cmn">ご注文内容</h3>
                        <div class="shipping_box_wrap">                        
<?php
if(isset($_SESSION["cart"])){
    $i = 0;
    $taxIn = 1.1;
    $tax = 0.1;
    foreach($_SESSION["cart"] as $cart) {
    $i++;
    $item_total_price = $cart['item_price'] * $cart['item_count'];
?>
                        <div class="cart_item">
                            <div class="cart_item_img">
                                <a href="">
                                    <img src="img/items/<?php print( $cart["item_image"] ); ?>"/>
                                </a>
                            </div>
                            <div class="cart_item_txt">
                                <h4><?php print( $cart["item_name"] ); ?></h4>
                                <dl class="buy_itemu_menu mod_order_info">
                                    <dt>価格:</dt>
                                    <dd>
                                        &yen;<?php print( number_format($cart["item_price"]*$taxIn)); ?>(税込)
                                    </dd>
                                </dl>
                                <dl class="buy_item_amount mod_order_info">
                                    <dt>数量:</dt>
                                    <dd>
                                        <?php echo $cart['item_count']; ?>個
                                    </dd>
                                </dl>
                                <dl class="mod_order_info mod_order_total">
                                    <dt>小計:</dt>
                                    <dd>
                                        &yen;<?php echo number_format($item_total_price*$taxIn);?>(税込)
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        
<?php
		}
        
	}
$con->close();
                    
?>                                    
                        </div>
                    </div>
                    <div class="box-shipping-sub">
                        <div class="payment_box">
                            <div class="payment_details">
                                <dl class="mod_payment mod_payment_details">
                                    <dt>商品点数</dt>
                                    <dd><?php echo $_SESSION['total_amount'];?>点</dd>
                                    <dt>商品代金合計(税込)</dt>
                                    <dd>&yen;<?php echo number_format($_SESSION['total_payment']);?></dd>
                                    <dt>送料</dt>
                                    <dd>&yen;<?php echo $_SESSION['postage'];?></dd>
                                    <dt>内消費税</dt>
                                    <dd>&yen;<?php echo number_format($_SESSION['tax']);?></dd>
                                </dl>
                                <div class="payment_total">
                                    <dl class="mod_payment mod_payment_total">
                                        <dt>ご注文合計</dt>
                                        <dd>&yen;<?php echo number_format($_SESSION['total_payment']+$_SESSION['postage']);?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="cart_button_area">
                        <form action="order_complete.php" method="POST">
                            <input type="submit" class="btn_cmn_l btn_design_01" value="注文を確定する"/>
                            <input type="hidden" name="cmd" value="order_comp">
<?php  //リロード対策
$_SESSION['user']['reload'] = "hoge";
$reload_off = $_SESSION['user']['reload'];
print <<<EOF
<input type="hidden" name="reload" value="$reload_off" />
EOF;?>
                        </form>
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
    header('Location:cart.php');
}
?>
