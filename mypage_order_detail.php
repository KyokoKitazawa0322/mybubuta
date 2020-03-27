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
 *　購入履歴の取得
 ---------------------------------------------------------*/
if(isset($_POST['order_id'])){
    $_SESSION['order_id'] = "NULL";
    $_SESSION['order_id'] = $_POST['order_id']; 
}

$sql = "SELECT * FROM order_history where customer_id = ? && order_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);
$stmt->bindvalue(2, $_SESSION['order_id']);
$stmt->execute();
$history = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage" id="history_detail">
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
     <!-- 左メニュー -->
     <?php require_once('mypage_common.php'); ?>
    <!-- メインコンテンツ -->
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>ご注文履歴明細</h2>
                <div class="main_contents_inner">
                    <div class="cart_item_box">
                        <div class="order_detail_box">
                            <dl class="list_order_detail_01">
                                <dt>ご注文日 :</dt>
                                <dd><?php echo $history['purchase_date']?></dd>
                                <dt>ご注文番号 :</dt>
                                <dd><?php echo $history['order_id']?></dd>
                                <dt>ご注文金額 :</dt>
                                <dd>&yen;<?php echo number_format($history['total_payment'])?></dd>
                                <dt>決済方法 :</dt>
                                <dd><?php echo $history['payment']?></dd>
                            </dl>
                        </div>
                        <div class="order_detail_box">
                            <h3 class="ttl_cmn">配送先住所</h3>
                            <dl class="list_order_detail_02">
                                <dt>名前 :</dt>
                                <dd><?php echo $history['delivery_name']?></dd>
                                <dt>郵便番号 :</dt>
                                <dd><?php echo $history['delivery_post']?></dd>
                                <dt>電話番号:</dt>
                                <dd><?php echo $history['delivery_tel']?></dd>
                                <dt>住所 :</dt>
                                <dd><?php echo $history['delivery_addr']?></dd>
                            </dl>
                        </div>
                        <div class="order_detail_box">
                            <h3 class="ttl_cmn last_ttl_cmn">ご注文内容</h3>
                            <div class="shipping_box_wrap">
<?php
/**--------------------------------------------------------
 *　購入明細の取得
 ---------------------------------------------------------*/
$sql = "SELECT items.item_name, items.item_image, items.item_price, order_detail.item_count FROM items LEFT JOIN order_detail ON items.item_code = order_detail.item_code where order_detail.order_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['order_id']);
$stmt->execute();
$result = $stmt->fetchAll();
if($result){
    foreach($result as $detail){
    $taxIn=1.1;
?>
                            <div class="cart_item">
                                <div class="cart_item_img">
                                    <img src="img/items/<?php print( $detail["item_image"] ); ?>"/>
                                </div>
                                <div class="cart_item_txt">
                                    <h4><?php print( $detail["item_name"] ); ?></h4>
                                    <dl class="buy_itemu_menu mod_order_info">
                                        <dt>価格:</dt>
                                        <dd>
                                            &yen;<?php print( number_format($detail["item_price"]*$taxIn)); ?>(税込)
                                        </dd>
                                    </dl>
                                    <dl class="buy_item_amount mod_order_info">
                                        <dt>数量:</dt>
                                        <dd>
                                            <?php echo $detail['item_count']; ?>個
                                        </dd>
                                    </dl>
                                    <dl class="mod_order_info mod_order_total">
                                        <dt>小計:</dt>
                                        <dd>
                                            &yen;<?php echo number_format($detail['item_price'] * $detail['item_count'] * $taxIn);?>(税込)
                                        </dd>
                                    </dl>
                                </div>
                            </div>

<?php
    }
}
?>   
                            </div>
                        </div>
                    </div>
                    <div class="box-shipping-sub">
                        <div class="payment_box">
                            <div class="payment_details">
                                <dl class="mod_payment mod_payment_details">
                                    <dt>商品点数</dt>
                                    <dd><?php echo $history['total_amount'];?>点</dd>
                                    <dt>商品代金合計(税込)</dt>
                                    <dd>&yen;<?php echo number_format($history['total_payment']);?></dd>
                                    <dt>送料</dt>
                                    <dd>&yen;<?php echo $history['postage'];?></dd>
                                    <dt>内消費税</dt>
                                    <dd>&yen;<?php echo number_format($history['tax']);?></dd>
                                </dl>
                                <div class="payment_total">
                                    <dl class="mod_payment mod_payment_total">
                                        <dt>ご注文合計</dt>
                                        <dd>&yen;<?php echo number_format($history['total_payment']+$history['postage']);?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="btn_link_wrap">
                            <a href="mypage_order_history.php" class="btn_cmn_01 btn_design_03">ご注文履歴に戻る</a>
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

