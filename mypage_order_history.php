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
$sql = "SELECT * FROM order_history where customer_id = ? order by purchase_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage" id="order_history">
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
                <h2>ご注文履歴</h2>
                <div class="main_contents_inner">
                    <?php if($result): ?>
                        <div class="box_order_history">
                            <div class="box_order_header">
                                <div class="box_col_date">ご注文日</div>
                                <div class="box_col_number">ご注文番号</div>
                                <div class="box_col_price">合計金額</div>
                                <div class="box_col_method">決済方法</div>
                            </div>
                            <div class="box_order_info">
                                <?php foreach($result as $history):?>
                                    <div class="box_row">
                                        <p class="box_col_date"><span class="txt_header_sp"><?php echo $history['purchase_date']?></span></p>
                                        <p class="box_col_number"><span class="txt-header-sp"></span><?php echo $history['order_id']?></p>
                                        <p class="box_col_price"><span class="txt_header_sp">&yen;<?php echo $history['total_payment']?></span></p>
                                        <p class="box_col_method"><span class="txt_heade_-sp"><?php echo $history['payment']?></span></p>
                                        <div class="detail_link_wrap">
                                            <form method="POST" action="mypage_order_detail.php">
                                                <input type="submit" class="btn_cmn_01 btn_design_02" value="詳細を見る">
                                                <input type="hidden" name="order_id" value="<?php echo $history['order_id']; ?>">
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach ;?>
                            </div>
                        </div>
                    <?php else :?>
                    <div class="txt_wrapper">
                        <p class="none_txt">購入履歴はありません。</p>
                    </div>
                    <?php endif; ?>
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

