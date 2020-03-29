
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
  登録情報を取得しvalueで表示
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

/**--------------------------------------------------------
   削除ボタンがおされたときの処理
 ---------------------------------------------------------*/

if(isset($_POST['delete'])){
$sql = "DELETE FROM delivery WHERE customer_id = ? && delivery_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindvalue(1, $_SESSION['customer_id']);
$stmt->bindvalue(2, $_POST['del_id']);
$stmt->execute();
}

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
<script type = "text/javascript">
<!--
    	// 住所登録編集(mypage_update.php)
function updbaseAddr(){
	$("form#base-addr-update").submit();
} 

    	// 住所追加(mypage_delivery_add.php)
function addAddr(){
	$("form#add_addr").submit();
} 
    
    	// 住所登録編集(mypage_delivery_add.php)
function updAddr(addrSeq){
	var seq = addrSeq.getAttribute("data-value");
	document.getElementById("updId").value = seq;
	$("form#exist-addr-update").submit();
} 

        //削除
function deleteAddr(addrSeq){
	var seq = addrSeq.getAttribute("data-value");
	document.getElementById("deleteId").value = seq;
	$("form#exist-addr-delete").submit();
}
    
// --> 
</script>
</head>

<body class="mypage" id="order_del_list">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
        <div class="main_wrapper">
            <div class="main_contents">
                <div class="deliver_title">
                    <h2>配送先を選んでください</h2>
                </div>
                <div class="main_contents_inner">
                    <form action="order_confirm.php" method="POST">
                        <h3 class="ttl_cmn">お客様会員住所</h3>
                        <div class="mypage_addr_box">
                            <div class="box_info">                           
                                <input name="def_addr" type="radio" id="def_addr" value="1" <?php if(isset($_SESSION['def_addr']) && $_SESSION['def_addr']=="1"){echo 'checked="checked"';}elseif($def_delFlag == 0){ echo 'checked="checked"';}?>>
                                <label for="def_addr" class="input_radio_addr_01">
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
                                </label>
                            </div>
                            <div class="update_reg_link_wrap">
                                <a href="javascript:void(0)" class="update_reg_link btn_cmn_mid btn_design_02" onclick="updbaseAddr()">会員住所を変更する</a>
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
if($result){ 
    $i=0;
    foreach($result as $res){
    $i++;
$name = $res['last_name'].$res['first_name'];
$post = $res['address_01']."-".$res['address_02'];
$address = $res['address_03'].$res['address_04'].$res['address_05'].$res['address_06'];
$add_delFlag = $res['del_flag'];
?>
                            <div class="mypage_addr_box">
                                <div class="box_info">
                                    <input name="def_addr" type="radio" id="def_addr<?= $i?>" value="<?php echo $res['delivery_id'];?>" <?php if(isset($_SESSION['def_addr']) && $_SESSION['def_addr']==$res['delivery_id']){echo 'checked="checked"';}elseif($add_delFlag == 0){ echo 'checked="checked"';}?>>
                                    <label for="def_addr<?= $i?>" class="input_radio_addr_01">
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
                                    </label>
                                </div>
                                <div class="update_reg_link_wrap">
                                    <input type="button" class="btn_cmn_mid btn_design_02" value="編集する" onclick="updAddr(this)" data-value="<?php echo $res['delivery_id']?>"> 
                                    <input type="button" onclick="deleteAddr(this)" class="btn_cmn_mid btn_design_03" value="削除" data-value="<?php echo $res['delivery_id']?>"> 
                                </div>
                            </div>
<?php
    }
}
?>
                        </div>
                        <div class="cart_button_area">
                            <input type="submit" class="btn_cmn_l btn_design_01" value="配送先を変更する"/>
                            <input type="hidden" name="cmd" value="del_comp">
                        </div>
                        <div class="add_btn_wrap">
                            <a href="javascript:void(0)" class="btn_cmn_l btn_design_02" onclick="addAddr()">配送先を新しく追加する</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form method="POST" id="base-addr-update" action="mypage_update.php">
        <input type="hidden" name="cmd" value="from_order">
    </form>
    <form method="POST" id="exist-addr-update" action="mypage_deliver_entry.php">
        <input type="hidden" name="del_id" id="updId" value>
        <input type="hidden" name="cmd" value="from_order">
        <input type="hidden" name="del_upd" value="">
    </form>
    <form method="POST" id="add_addr" action="mypage_delivery_add.php">
        <input type="hidden" name="cmd" value="from_order">
    </form>
    <form method="POST" id="exist-addr-delete" action="#">
        <input type="hidden" name="del_id" id="deleteId" value>
        <input type="hidden" name="delete" value="">
    </form>
</div>
</body>
</html>