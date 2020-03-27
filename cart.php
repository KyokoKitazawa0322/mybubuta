<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo();

if(!isset( $_SESSION["cart"]))
{
    $_SESSION["cart"] = array();
}

/**--------------------------------------------------------
 * favorite.phpからカートにいれるボタンがおされたときの処理
 ---------------------------------------------------------*/
if(isset($_POST["cmd"]) && $_POST["cmd"] == "add_cart_fromFav"){
    $is_already_exists  = 0;
    for( $i=0 ; $i<count($_SESSION["cart"]); $i++){
        if( $_SESSION["cart"][$i]["item_code"] == $_POST["item_code"] ){
            // 追加する商品がカートに既に存在している場合は数量を合算。
            $_SESSION["cart"][$i]["item_count"] = $_SESSION["cart"][$i]["item_count"] + 1;
            $is_already_exists = 1;
        }
    }
    
    // 追加する商品がカートに存在しない場合、カートに新規登録。
    if( $is_already_exists == 0 ){ 
        $sql = "select * from items where item_code = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(1,($_POST["item_code"]));
        $stmt->execute();
        if($record = $stmt->fetch()) {
            $item["item_code"] = $_POST["item_code"];
            $item["item_count"] = 1;
            $item["item_image"] = $record["item_image"];
            $item["item_name"] = $record["item_name"];
            $item["item_price"] = $record["item_price"];
            array_push($_SESSION["cart"], $item);
        }
    }
}
/**--------------------------------------------------------
 * 非ログイン状態でお気に入りに移動ボタンをおした時(フラグでもどってくる) 
 ---------------------------------------------------------*/
if(isset($_SESSION['cart_flag']) && $_SESSION['cart_flag'] == "1"){
    if(isset($_SESSION['customer_id'])){
        //カートから削除
     for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ ){
        if( $_SESSION["cart"][$i]["item_code"] == $_SESSION["move_fav_item_code"]){
            unset( $_SESSION["cart"][$i]);
        }
    }
    $_SESSION["cart"] = array_merge($_SESSION["cart"]);  
        
    $sql = "insert ignore into favorite(item_code, customer_id) values(?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1,($_SESSION["move_fav_item_code"]));
    $stmt->bindvalue(2,$_SESSION['customer_id']);  
    $stmt->execute();
    }
    unset($_SESSION['cart_flag']);
    unset($_SESSION['move_fav_item_code']);
}
/**--------------------------------------------------------
 * ログイン状態でお気に入りに移動ボタンがおされたとき
 ---------------------------------------------------------*/
if(isset($_POST["cmd"]) && $_POST["cmd"] == "move_fav" ){
    if(isset($_SESSION['customer_id'])){
        //カートから削除
     for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ ){
        if( $_SESSION["cart"][$i]["item_code"] == $_POST["item_code"] ){
            unset( $_SESSION["cart"][$i] );
        }
    }
        //カート内の整列
    $_SESSION["cart"] = array_merge($_SESSION["cart"]);  
     //既にお気に入りに保存されてるか確認
    $sql = "select * from favorite where item_code=? && customer_id=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_POST["item_code"]);
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
    $res = $stmt->fetch();
    //ない場合
    if(!$res){
    $sql = "insert into favorite(item_code, customer_id) values(?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindvalue(1, $_POST["item_code"]);
    $stmt->bindvalue(2, $_SESSION['customer_id']);  
    $stmt->execute();
    }
    }else{
        //ログイン状態がなければlogin.phpへ
        $_SESSION['cart_flag']=1;
        $_SESSION['move_fav_item_code']=$_POST['item_code'];
        header('Location:login.php');
        exit();
    }
}

/**-----------------------------------------------------------
 * リクエスト cmd の中身が、「add_cart」であった場合の処理。
 ------------------------------------------------------------*/

 if(isset($_GET["cmd"]) && $_GET["cmd"] == "add_cart"){
    $is_already_exists  = 0;
    for( $i=0 ; $i<count($_SESSION["cart"]); $i++){
        if( $_SESSION["cart"][$i]["item_code"] == $_GET["item_code"] ){
            // 追加する商品がカートに既に存在している場合は数量を合算。
            $_SESSION["cart"][$i]["item_count"] = $_SESSION["cart"][$i]["item_count"] + $_GET["item_count"];
            $is_already_exists = 1;
        }
    }
     
    // 追加する商品がカートに存在しない場合、カートに新規登録。
    if( $is_already_exists == 0 ){ 
        $sql = "select * from items where item_code = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(1,($_GET["item_code"]));
        $stmt->execute();
        if($record = $stmt->fetch()) {
            $item["item_code"] = $_GET["item_code"];
            $item["item_count"] = $_GET["item_count"];
            $item["item_image"] = $record["item_image"];
            $item["item_name"] = $record["item_name"];
            $item["item_price"] = $record["item_price"];
            array_push($_SESSION["cart"], $item);
        }
    }
}

/**-----------------------------------------------------------
 　　「削除」ボタンが押された時の処理
 ------------------------------------------------------------*/

if(isset($_GET["cmd"]) && $_GET["cmd"] == "del")
{
    for( $i = 0 ; $i < count( $_SESSION["cart"] ); $i++ ){
        if( $_SESSION["cart"][$i]["item_code"] == $_GET["item_code"] ){
            unset( $_SESSION["cart"][$i] );
        }
    }
    $_SESSION["cart"] = array_merge($_SESSION["cart"]);
}
$con->close();  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品詳細｜洋服の通販サイト</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script type="text/javascript">
<!--

$(function() {
    $("a#move_fav").click(function(){
        Swal.fire({
            text: "お気に入りに移動しました",
            confirmButtonText: '戻る',
        }).then((result) => {
        if (result.value) {
        	var item_num = $(this).data('num');
        $('#move_num').val(item_num);
            $("form#item_code").submit();
        }
    });
});
});
    
$(function() {
$("select").change(function() {
    var price = [];
    var amount = [];
    for(var i = 0; i < $(".buy_itemu_menu").length; i++){
        var item_price = $(".buy_itemu_menu").eq(i).data("price");
        var item_select = $(".buy_itemu_menu").eq(i).next(".select_wrap").children("select").find("option:selected").data("num");
          price.push(item_price * item_select);
          amount.push(item_select);
    } 
    //合計金額
    var total = 0;
    for(var j = 0; j < price.length; j++){
    total += price[j];
    }
    //合計点数
    var totalAmount = 0;
    for(var j = 0; j < amount.length; j++){
    totalAmount += amount[j];
    }
    
    if(total>=10000) {
    var postage = 0;
    var postageDis = '\xA5' + 0;
    }else{
    var postage = 500;
    var postageDis = '\xA5' + 500;
    }
    
    var tax = total * 0.1;
    var taxDis = '\xA5' + separate(tax);
    var taxIncludeTotal = total + tax;
    var taxIncludeTotalDis ='\xA5'+ separate(total+tax); 
    var total_paymentAll = '\xA5'+ separate(total+postage+tax);
    //POST送信用
    $(".total_amount").val(totalAmount);
    $(".tax").val(tax);
    $(".postage").val(postage);
    $(".total_payment").val(taxIncludeTotal);    
    //表示用
    $(".taxDis").val(taxDis);
    $(".total_paymentDis").val(taxIncludeTotalDis);
    $(".postageDis").val(postageDis);
    $(".total_price").val(total_paymentAll);

}); 
});     

    function separate(num){
    num = String(num);
    var len = num.length;
    if(len > 3){
        // 前半を引数に呼び出し + 後半3桁
        return separate(num.substring(0,len-3))+','+num.substring(len-3);
    } else {
        return num;
    }
}
    
$(function() {
    $("select").change(function() {
        for(var i = 0; i < $(".buy_itemu_menu").length; i++){
        var item_price = $(".buy_itemu_menu").eq(i).data("price");
        var item_select = $(".buy_itemu_menu").eq(i).next(".select_wrap").children("select").find("option:selected").data("num");
        var total = item_price * item_select;
        var tax = total * 0.1;
        var item_total = '\xA5'+ separate(item_price * item_select + tax) + "(税込)"; 
        $(".item_price").eq(i).val(item_total);
    };
});
});
// --> 
</script>
    
</head>
<body id="cart">
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
                    <?php if(empty($_SESSION["cart"])):?>
                        <div class="txt_wrapper">
                            <p class="none_txt">ショッピングカート内に商品はありません。</p>
                            <div class="back_btn_wrap">
                                <input class="btn_design_02 btn_cmn_l" type="button" value="お買い物を続ける" onclick="history.back()" /> 
                            </div>
                        </div>
                    <?php endif;?>
                    <?php if(!empty($_SESSION["cart"])):?>
                    <form action="order_confirm.php" method="POST">
                    <div class="cart_item_box">                      
<?php
	if(isset($_SESSION["cart"])){
        $var = 0;
        $total_price = 0;
        $total_amount = 0;
        $taxIn = 1.1;
        $tax = 0.1;
	    foreach($_SESSION["cart"] as $cart) {
        $var++;
        $item_total_price = $cart['item_price']*$cart['item_count']*$taxIn;
        $total_price += $cart['item_price']*$cart['item_count'];
        $total_amount += $cart['item_count'];
?>
                        <div class="cart_item">
                        
                            <div class="cart_item_img">
                                <a href="item_detail.php?item_code=<?php print(htmlspecialchars($cart["item_code"])); ?>">
                                    <img src="img/items/<?php print( $cart["item_image"] ); ?>"/>
                                </a>
                            </div>
                            <div class="cart_item_txt">
                                <p><a class="product_link" href="item_detail.php?item_code=<?php print(htmlspecialchars( $cart["item_code"])); ?>"><?php print($cart["item_name"]); ?></a></p>
                                <dl class="buy_itemu_menu mod_order_info" data-price="<?php print($cart['item_price'])?>">
                                    <dt>価格:</dt>
                                    <dd>&yen;<?php print(number_format($cart["item_price"]*$taxIn)); ?>(税込)</dd>
                                </dl>
                                <div class="select_wrap">
                                    <select name="cart<?php echo $var;?>" class="item_count">
                                    <?php 
                                        $min = 1;
                                        $max = 10;
                                        for($i=$min; $i<=$max; $i++){
                                            if($cart['item_count'] == $i){
                                            echo "<option data-num={$i} value={$i} selected>{$i}</option>";
                                            }else{
                                            echo "<option data-num={$i} value={$i}>{$i}</option>";
                                           }
                                        }
                                    ?>
                                    </select>
                                </div>
                                <span>個</span>
                                <dl class="mod_order_info mod_order_total">
                                    <dt>小計:</dt>
                                    <dd>
                                        <input type="text" class="item_price" value="&yen;<?php print(number_format($item_total_price));?>(税込)" readonly><span></span>
                                    </dd>
                                </dl>
                                <div class="cart_btn_wrap">
                                    <a href="javascript:void(0);" id="move_fav" class="btn_cmn_mid btn_design_02" data-num="<?php print( $cart["item_code"] ); ?>">あとで買う<br/><span>(お気に入りに移動)</span>
                                    </a>
                                    <a class="btn_cmn_mid btn_design_03" href="cart.php?cmd=del&item_code=<?php print( $cart["item_code"] ); ?>">削除</a>
                                </div>
                            </div>
                        </div>
                        
<?php
		}
        
	}
$con->close();
                    
?>                                    
                    </div>
                        <div class="box-shipping-sub">
                            <div class="payment_box">
                                <div class="payment_details">
                                    <dl class="mod_payment mod_payment_details">
                                        <dt>商品点数</dt>
                                        <dd><input name="total_amount" class="total_amount" type="text" value="<?php echo $total_amount;?>" readonly>点</dd>
                                        <dt>商品代金合計(税込)</dt>
                                        <dd><input class="total_paymentDis" type="text" value="&yen;<?php print(number_format($total_price*$taxIn));?>" readonly></dd>
                                        <dt>送料</dt>
                                        <dd><input class="postageDis" type="text" value="&yen;<?php if($total_price<=10000){echo 500;}else{echo 0;}?>" readonly></dd>
                                        <dt>内消費税</dt>
                                        <dd><input type="text" class="taxDis" value="&yen;<?php print(number_format($total_price*$tax));?>" readonly></dd>
                                    </dl>
                                    <div class="payment_total">
                                        <dl class="mod_payment mod_payment_total">
                                            <dt>ご注文合計</dt>
                                            <dd><input class="total_price" type="text" value="&yen;<?php if($total_price<=10000){print(number_format($total_price*$taxIn+500));}else{print(number_format($total_price*$taxIn));}?>" readonly></dd>
                                        </dl>
                                    </div>
                                </div>              
                            </div>
                            <br/>
                            <div class="cart_button_area">
                                <input type="hidden" name="total_payment" class="total_payment" value="<?php print($total_price*$taxIn);?>">
                                <input type="hidden" name="postage" class="postage" value="<?php if($total_price<=10000){echo 500;}else{echo 0;}?>">
                                <input type="hidden" name="tax" class="tax" value="<?php print($total_price*$tax);?>">
                                <input type="submit" class="btn_cmn_l btn_design_01" value="レジに進む" />
                                <input type="hidden" name="cmd" value="order_confirm">
                                <div class="back_button">
                                    <input class="btn_cmn_l btn_design_03" type="button" value="お買い物を続ける" onclick="history.back()" />   
                                </div>
                            </div>
                        </div>   
                    </form>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
    <div id="footer">
        <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
    </div>
    <form action="#" method="POST" id="item_code">
        <input type="hidden" name="cmd" value="move_fav">
        <input type="hidden" name="item_code" id="move_num" value>
    </form>
</div>
</body>
</html>

