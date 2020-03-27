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
  配送先保存ボタンが押された時の処理
 ---------------------------------------------------------*/
if(isset($_SESSION['add_data'])) { 
    $sql ="INSERT INTO delivery(last_name, first_name, ruby_last_name, ruby_first_name, address_01, address_02, address_03, address_04, address_05, address_06, tel, customer_id, del_flag, delivery_insert_date)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['del_add']['name01']);
    $stmt->bindvalue(2, $_SESSION['del_add']['name02']);
    $stmt->bindvalue(3, $_SESSION['del_add']['name03']);
    $stmt->bindvalue(4, $_SESSION['del_add']['name04']);
    $stmt->bindvalue(5, $_SESSION['del_add']['add01']);
    $stmt->bindvalue(6, $_SESSION['del_add']['add02']);
    $stmt->bindvalue(7, $_SESSION['del_add']['add03']);
    $stmt->bindvalue(8, $_SESSION['del_add']['add04']);
    $stmt->bindvalue(9, $_SESSION['del_add']['add05']);
    $stmt->bindvalue(10, $_SESSION['del_add']['add06']);
    $stmt->bindvalue(11, $_SESSION['del_add']['tel']);
    $stmt->bindvalue(12, $_SESSION['customer_id']);
    $stmt->bindvalue(13, "1");
    $result = $stmt->execute();
    $_SESSION['add_data']=NULL;
    $_SESSION['del_add']=NULL;
/**--------------------------------------------------------
 * order_delivery_listからきた場合
 ---------------------------------------------------------*/
    if(isset($_SESSION['from_order_flag'])){
        header('Location:order_delivery_list.php');
        $_SESSION['from_order_flag']=NULL;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage" id="">
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
                <h2>配送先の登録</h2>
                <div class="register_wrapper">
                    <div class="txt_wrapper">
                        <p>配送先の登録が完了しました。</p>
                        
                        <div class="complete_button_wrapper">
                            <input class="btn_cmn_l btn_design_01" type="button" value="配送先の登録・変更" onClick="location.href='mypage_deliver.php'" >
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

