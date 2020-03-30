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
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA 公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
<!--

$(function() {
    $("input#cartIn").click(function(){
        var item_code = $(this).data('value');
        $('#itemId').val(item_code);
        $("form#cartInForm").submit();
    });
});
// --> 
</script>
</head>

<body class="mypage" id="favorite">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
     <?php require_once('mypage_common.php'); ?>
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="favorite_title">
                    <h2>お気に入り商品</h2>
                </div>
                <div class="main_contents_inner">
<?php if($favorite): ?>
                    <ul class="fav_list">
<?php  $taxIn = 1.1; ?>
<?php foreach($favorite as $item): ?>
                        <li class="products">
                            <div class="product_inner">
                                <a class="product_link" href="item_detail.php?item_code=<?php print(htmlspecialchars( $item["item_code"])); ?>">
                                    <img src="img/items/<?php print($item["item_image"]);?>" alt="" />
                                    <div class="item_txt_wrap">
                                        <p class="item_name"><?php print($item["item_name"]); ?></p>
                                        <p class="item_list_price">&yen;<?php print(number_format($item["item_price"]*$taxIn));?></p>
                                    </div>
                                </a>
                            </div>
                            <div class="fav_btn_area">
                                <div class="fav_btn_wrap">
                                    <input type="button" class="btn_cmn_mid btn_design_02" value="カートにいれる" id="cartIn" data-value="<?php print(htmlspecialchars($item["item_code"])); ?>">
                                </div>
                                <div class="fav_btn_wrap">
                                    <a href="mypage_favorite.php?cmd=del&item_code=<?php print( $item["item_code"] ); ?>" class="btn_cmn_01 btn_design_03">削除</a>
                                </div>
                            </div>
                        </li>
<?php endforeach; ?>
                    </ul>
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
    <form method="POST" id="cartInForm" action="cart.php">
        <input type="hidden" name="cmd" value="add_cart_fromFav">
        <input type="hidden" name="item_code" id="itemId" value>
    </form>
</div>
</body>
</html>
