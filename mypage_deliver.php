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
   削除ボタンがおされたときの処理
 ---------------------------------------------------------*/

if(isset($_POST['del_item'])){
    $sql = "DELETE FROM delivery WHERE customer_id = ? && delivery_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['customer_id']);
    $stmt->bindvalue(2, $_POST['del_id']);
    $stmt->execute();

    //全件削除した場合にデフォルト住所を基本登録にもどす
    $sql = "SELECT * FROM delivery WHERE customer_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_SESSION['customer_id']);
    $stmt->execute();
    if(!$stmt->fetch()){
       $sql ="UPDATE customers SET del_flag=:del_flag where customer_id=:customer_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(':del_flag', '0');
        $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
        $stmt->execute();         
    }
}
/**--------------------------------------------------------
   配送先設定ボタンがおされたときの処理
 ---------------------------------------------------------*/
if(isset($_POST['set'])){
//会員登録情報であれば値はdef
    if($_POST['del_id']=="def"){
        //customers:del_flag=0(デェフォルト)
        //delivery:del_flag=1に(customer_idで取得した全件)
       $sql ="UPDATE customers SET del_flag=:del_flag where customer_id=:customer_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(':del_flag', '0');
        $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
        $stmt->execute();
       
        $sql ="UPDATE delivery SET del_flag=:del_flag where customer_id=:customer_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(':del_flag', '1');
        $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
        $stmt->execute();
        
//deliver登録情報であれば値はdelivery_id
    }else{
        
        //customer_idで取得したdelivery情報全件del_flag=1にする
       $sql ="UPDATE delivery SET del_flag=:del_flag where customer_id=:customer_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(':del_flag', '1');
        $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
        $stmt->execute();
        
        //delivery:del_flag=0　NULL
       $sql ="UPDATE delivery SET del_flag=:del_flag where customer_id=:customer_id && delivery_id=:delivery_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(':del_flag', '0');
        $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
        $stmt->bindvalue(':delivery_id', $_POST['del_id']);
        $stmt->execute();

        //customers:del_flag=1
       $sql ="UPDATE customers SET del_flag=:del_flag where customer_id=:customer_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(':del_flag', '1');
        $stmt->bindvalue(':customer_id', $_SESSION['customer_id']);
        $stmt->execute();
    }
}

/**--------------------------------------------------------
   会員情報の取得
 ---------------------------------------------------------*/
$sql = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);
$stmt->execute();
$res = $stmt->fetch();

$name = $res['last_name'].$res['first_name'];
$post = $res['address_01']."-".$res['address_02'];
$address = $res['address_03'].$res['address_04'].$res['address_05'].$res['address_06'];
$def_delFlag = $res['del_flag'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧｜洋服の通販サイト</title>    
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
<!--
    //いつもの配送先を設定
$(function(){
$('input[name="def_addr"]').click(function(){
	var seq = $(this).data("value");
	$("#updId").val(seq);
	$("form#addr-update").submit();
});

$('form#addr-update').submit(function(){
    var scroll_top = $(window).scrollTop();  
    $('input.st',this).prop('value',scroll_top); 
});

window.onload = function(){
$(window).scrollTop(<?php echo @$_REQUEST['scroll_top']; ?>);
}

});
// --> 
</script>
</head>

<body class="mypage" id="mypage_deliver">
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
                <div class="deliver_title">
                    <h2>配送先の登録・確認</h2>
                </div>
                <div class="main_contents_inner">
                    <h3 class="ttl_cmn">お客様会員住所</h3>
                    <div class="mypage_addr_box">
                        <div class="box_info">
                            <input name="def_addr" type="radio" id="def_addr" data-value="def" <?php if($def_delFlag == 0){ echo 'checked="checked"';}?>>
                            <label for="def_addr" class="input_radio_addr_01">
                                <span class="txt_label">いつもの配送先</span>
                            </label>
                            <dl class="list_addr_info">
                                <dt>名前 :</dt>
                                <dd><?php print($name);?></dd>
                                <dt>郵便番号 :</dt>
                                <dd><?php print($post);?></dd>
                                <dt>電話番号 :</dt>
                                <dd><?php print($res['tel']);?></dd>
                                <dt>住所 :</dt>
                                <dd><?php print($address);?></dd>
                            </dl>
                        </div>
                        <div class="update_reg_link_wrap">
                            <a href="mypage_update.php" class="btn_cmn_mid btn_design_02">会員住所を変更する</a>
                        </div>
                    </div>
                    <div class="add_reg_wrap">
                        <h3 class="ttl_cmn">配送先ご登録住所</h3>
                    
<?php
/**--------------------------------------------------------
   配送先情報の取得（あれば表示）
 ---------------------------------------------------------*/
$sql = "SELECT * FROM delivery WHERE customer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);
$stmt->execute();
$result = $stmt->fetchAll();
$i=1;
if($result){ 
    foreach($result as $res){
$i++;
$name = $res['last_name'].$res['first_name'];
$post = $res['address_01']."-".$res['address_02'];
$address = $res['address_03'].$res['address_04'].$res['address_05'].$res['address_06'];
$add_delFlag = $res['del_flag'];
?>
                        <div class="mypage_addr_box">
                            <div class="box_info">
                                <input name="def_addr" type="radio" id="reg_addr<?= $i?>" data-value="<?php echo $res['delivery_id'];?>" <?php if($add_delFlag == 0){ echo 'checked="checked"';}?>>
                                <label for= "reg_addr<?= $i?>" class="input_radio_addr_01">
                                    <span class="txt_label">いつもの配送先</span>
                                </label>
                                <dl class="list_addr_info">
                                    <dt>名前 :</dt>
                                    <dd><?php print($name);?></dd>
                                    <dt>郵便番号 :</dt>
                                    <dd><?php print($post);?></dd>
                                    <dt>電話番号 :</dt>
                                    <dd><?php print($res['tel']);?></dd>
                                    <dt>住所 :</dt>
                                    <dd><?php print($address);?></dd>
                                </dl>
                            </div>
                            <div class="update_reg_link_wrap">   
                                <form method="POST" action="mypage_deliver_entry.php">
                                    <input type="hidden" name="del_id" value="<?php echo $res['delivery_id']?>">
                                    <input type="submit" class="btn_cmn_mid btn_design_02" value="編集する" name="del_update"> 
                                </form>
                                <form method="POST" action="#">
                                    <input type="hidden" name="del_id" value="<?php echo $res['delivery_id']; ?>">
                                    <input type="submit" name="del_item" class="btn_cmn_mid btn_cmn_01 btn_design_03" value="削除"> 
                                </form>
                            </div>
                        </div>
<?php
    }
}
?>
                    </div>
<?php if(!$result):?>
                    <div class="txt_wrapper">
                        <p>登録はありません。</p>
                    </div>
<?php endif; ?>
                    <div class="add_reg_link_wrap">
                        <a href="mypage_delivery_add.php" class="add_reg_link btn_cmn_l btn_design_01">配送先を新しく追加する</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form method="POST" id="addr-update" action="#">
        <input type="hidden" name="del_id" id="updId" value>
        <input type="hidden" name="set" value="">
        <input type="hidden" name="scroll_top" value="" class="st">
    </form>
</div>
</body>
</html>