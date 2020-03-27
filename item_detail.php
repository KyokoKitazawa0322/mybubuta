<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品詳細｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body id="item_detail">
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
    <?php require_once('left_pane.php'); ?>
        
    <!-- メインコンテンツ -->
        <div class="main_wrapper">
            <div class="main_contents">

          
<?php

$con = new Connection();
$pdo = $con->pdo();
$sql = "SELECT * FROM items WHERE item_code = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1,$_GET["item_code"]); 
$stmt->execute();   

/*$res = $stmt->execute(
array( $_REQUEST["code"] )
);
*/
if($item = $stmt->fetch()) {
$taxIn = 1.1;
?>
                <div class="item_detail_title">
                    <h2>
                        <img class="product_logo" src="common/img/main_contents_title_detail.png" alt="商品詳細">
                    </h2>
                </div>
                <div class="main_contents_inner">
                    <p class="photo">
                        <img src="img/items/<?php print( htmlspecialchars( $item["item_image"])); ?>" />
                    </p>
                    <div class="text_side">
                        <h3 class="item_name">
                            <?php print( htmlspecialchars($item["item_name"])); ?>
                        </h3>
                        <p class="price">&yen;<?php print( htmlspecialchars(number_format($item["item_price"]*$taxIn)));?><span>(税込)</span>
                        </p>
                        <p class="item_detail_txt">
                            <?php print( htmlspecialchars( $item["item_detail"])); ?>
                        </p>
                        <div class="detail_form_wrap">
                            <form action="cart.php" method="GET" class="item_num_form">
                                <div class="select_wrap">
                                    <select name="item_count" class="item_count_sl">
                                    <?php  for($i=1; $i<=10; $i++){
                                       echo "<option value={$i}>{$i}</option>";
                                    } ?>
                                    </select>
                                </div>
                                <span>個</span>
                                <div class="cart_btn_wrap">
                                    <input type="submit" class="btn_cmn_mid btn_design_01" value="カートにいれる" />
                                    <input type="hidden" name="cmd" value="add_cart" />
                                    <input type="hidden" name="item_code" value="<?php print(htmlspecialchars($item["item_code"])); ?>" />
                                </div>
                            </form>
                            <div class="favorite_btn_wrap">
                             <form action="mypage_favorite.php" method="GET">
                                <input type="submit" class="btn_cmn_mid btn_design_02" value="お気に入り保存" />
                                <input type="hidden" name="cmd" value="add_favorite" />
                                <input type="hidden" name="item_code" value="<?php print(htmlspecialchars($item["item_code"])); ?>" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="back_btn_wrap">
                        <input type="button" class="btn_cmn_mid btn_design_03" value="前の画面へ戻る" onclick="history.back()" />    
                    </div>
                </div>
<?php 
}
$con->close();
?>

            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
