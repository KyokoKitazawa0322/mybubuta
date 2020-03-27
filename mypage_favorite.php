<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

/**--------------------------------------------------------
 * 詳細画面で「お気に入り保存」ボタンが押された時に処理を行う
 ---------------------------------------------------------*/
if(isset($_GET["cmd"]) && $_GET["cmd"] == "add_favorite" ){
    //非ログイン状態の場合はフラグをたててログイン画面へ
    if(!isset($_SESSION['customer_id'])){
        $_SESSION['fav_flug']=1;
        $_SESSION['add_item_code'] = $_GET['item_code'];
        header('Location:login.php');
        exit();
    }
    $sql = "select * from favorite where item_code=? && customer_id=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_GET["item_code"]);
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
    $res = $stmt->fetch();
    if(!$res){
    $sql = "insert into favorite(item_code, customer_id) values(?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_GET["item_code"]);
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
    }
}

//今日追加
/**--------------------------------------------------------
 * ログイン状態の判定(セッション切れの場合はlogin.phpへ)
 ---------------------------------------------------------*/
 if(!isset($_SESSION['customer_id'])){
    header('Location:login.php');
    exit();
 }

/**--------------------------------------------------------
 * 詳細画面で「お気に入り保存」ボタンが押され、その後ログインをはさんだ場合の処理
 ---------------------------------------------------------*/
if(isset($_SESSION['fav_flug']) && $_SESSION['fav_flug'] == "1"){
    $sql = "select * from favorite where item_code=? && customer_id=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION["add_item_code"]);
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
    $res = $stmt->fetch();
    if(!$res){
    $sql = "insert into favorite(item_code, customer_id) values(?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION["add_item_code"]);
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
    }
    unset($_SESSION['fav_flug']);
    unset($_SESSION['add_item_code']);
}

/**-----------------------------------------------------------
  「削除」ボタンが押された時の処理
------------------------------------------------------------*/
if(isset($_GET["cmd"]) && $_GET["cmd"] == "del"){
    $sql = "delete from favorite where item_code = ? && customer_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_GET['item_code']);  
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
}

/**-----------------------------------------------------------
  一覧表示
------------------------------------------------------------*/
$sql = "select items.item_name, items.item_image, items.item_price, items.item_code FROM items left join favorite on items.item_code = favorite.item_code where favorite.customer_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);  
$stmt->execute();
$favorite = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="mypage favorite">
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
                <div class="favorite_title">
                    <h2>お気に入り商品</h2>
                </div>
                <div class="main_contents_inner">
<?php if($favorite): ?>
                    <table class="fav_list" cellpadding="0" cellspacing="0">
<?php  $taxIn = 1.1; ?>
<?php foreach($favorite as $item): ?>
                    <tr>
                        <td class="tc1">
                            <a href="item_detail.php?item_code=<?php print( $item["item_code"] ); ?>">
                                <img class="fav_img" src="img/items/<?php print( $item["item_image"] ); ?>"/>
                            </a>
                        </td>
                        <td class="tc2">
                            <a href="item_detail.php?item_code=<?php print( $item["item_code"] ); ?>">
                                <?php print( $item["item_name"] );?>
                            </a>
                        </td>
                        <td class="tc3">
                            &yen;<?php print(number_format($item["item_price"]*$taxIn));?>(税込)
                        </td>
                        <td class="tc4">
                            <form action="cart.php" method="POST">
                                <input type="submit" class="btn_cmn_mid btn_design_01" value="カートにいれる">
                                <input type="hidden" name="cmd" value="add_cart_fromFav" />
                                <input type="hidden" name="item_code" value="<?php print(htmlspecialchars($item["item_code"])); ?>" />
                            </form>
                        </td>
                        <td class="tc5">
                            <a href="mypage_favorite.php?cmd=del&item_code=<?php print( $item["item_code"] ); ?>" class="btn_cmn_01 btn_design_03">削除</a>
                        </td>
                    </tr>
<?php endforeach; ?>
                    </table><br/>
<?php else:?>
                    <div class="txt_wrapper">
                        <p class="none_txt">お気に入りに登録されている商品はありません。</p>
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
