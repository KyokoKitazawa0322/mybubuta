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
if(isset($_SESSION["update_data"])){   
    $sql ="UPDATE delivery SET last_name=:last_name, first_name=:first_name, ruby_last_name=:ruby_last_name, ruby_first_name=:ruby_first_name, address_01=:address_01, address_02=:address_02, address_03=:address_03, address_04=:address_04, address_05=:address_05, address_06=:address_06, tel=:tel, delivery_updated_date = now() where customer_id=:customer_id && delivery_id=:delivery_id";
        
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(':last_name', $_SESSION['del_update']['name01']);
    $stmt->bindvalue(':first_name', $_SESSION['del_update']['name02']);
    $stmt->bindvalue(':ruby_last_name', $_SESSION['del_update']['name03']);
    $stmt->bindvalue(':ruby_first_name', $_SESSION['del_update']['name04']);
    $stmt->bindvalue(':address_01', $_SESSION['del_update']['add01']);
    $stmt->bindvalue(':address_02', $_SESSION['del_update']['add02']);
    $stmt->bindvalue(':address_03', $_SESSION['del_update']['add03']);
    $stmt->bindvalue(':address_04', $_SESSION['del_update']['add04']);
    $stmt->bindvalue(':address_05', $_SESSION['del_update']['add05']);
    $stmt->bindvalue(':address_06', $_SESSION['del_update']['add06']);
    $stmt->bindvalue(':tel', $_SESSION['del_update']['tel']);
    $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
    $stmt->bindvalue(':delivery_id', $_SESSION['del_id']);
    $result = $stmt->execute();
    $_SESSION["update_data"] = NULL;
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

<body class="mypage" id="del_comp">
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
                <h2>配送先の編集</h2>
                <div class="register_wrapper">
                    <div class="txt_wrapper">
                        <p>配送先の編集が完了しました。</p>
                        
                        <div class="complete_button_wrapper">
                            <input class="" type="button" value="配送先の登録・変更" onClick="location.href='mypage_deliver.php'" >
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

