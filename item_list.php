<?php
session_cache_limiter('none');
session_start();
mb_internal_encoding("utf-8");
require_once(__DIR__."/connection.php");
$con = new Connection();
$pdo = $con->pdo(); 
$taxIn = 1.1;
/**-----------------------------------------------------------
    商品一覧表示
 ------------------------------------------------------------*/
$sql = "SELECT * FROM items WHERE item_del_flag = '0' ";

if(isset($_GET["cmd"])){
    if($_GET['cmd']=="do_search" || $_GET['cmd']=="item_list") {
        $_SESSION["search_sql"] = NULL;
        $_SESSION["dress"] = NULL;
        $_SESSION["coat"] = NULL;
        $_SESSION["skirt"] = NULL;
        $_SESSION["tops"] = NULL;
        $_SESSION["pants"] = NULL;
        $_SESSION["bag"] = NULL;
        $_SESSION['item_name'] = NULL;
        $_SESSION["minimum_price"] = NULL;
        $_SESSION["maximum_price"] = NULL;
    }
}

/**-----------------------------------------------------------
    カテゴリのみ検索)
 ------------------------------------------------------------*/
if(isset($_GET['coat']) ||isset($_GET['dress']) || isset($_GET['skirt']) || isset($_GET['tops']) || isset($_GET['pants'])|| isset($_GET['bag'])) {
    if(isset($_GET['coat'])){$_SESSION['coat'] = $_GET['coat'];}
    if(isset($_GET['dress'])){$_SESSION['dress'] = $_GET['dress'];}
    if(isset($_GET['skirt'])){$_SESSION['skirt'] = $_GET['skirt'];}
    if(isset($_GET['tops'])){$_SESSION['tops'] = $_GET['tops'];}
    if(isset($_GET['pants'])){$_SESSION['pants'] = $_GET['pants'];}
    if(isset($_GET['bag'])){$_SESSION['bag'] = $_GET['bag'];}
    
    $in = "";
    if( isset($_GET['coat'])){
        $in = "{$in}'coat',";
    }
    if( isset($_GET['dress'])){
        $in = "{$in}'dress',";
    }
    if( isset($_GET['skirt'])){
        $in = "{$in}'skirt',";
    }
    if( isset($_GET['tops'])){
        $in = "{$in}'tops',";
    }
    if( isset($_GET['pants'])){
        $in = "{$in}'pants',";
    }
    if( isset($_GET['bag'])){
        $in = "{$in}'bag',";
    }
//preg_replace( $正規表現パターン , $置換後の文字列 , $置換対象の文字列 )
//正規表現$ = 直前の文字が行の末尾にある場合にマッチ
    $in = preg_replace( "/,$/", "", $in );
    $sql = $sql." AND item_category IN ( $in ) ";
}    


/**-----------------------------------------------------------
    商品名検索
 ------------------------------------------------------------*/
  if( !empty($_GET['item_name'])){
      $sql = "{$sql}AND item_name LIKE '%{$_GET["item_name"]}%'";
      $_SESSION['item_name'] = $_GET['item_name'];
    }
    
/**-----------------------------------------------------------
    金額検索
 ------------------------------------------------------------*/
        //下限額＆上限額
if(!empty($_GET["minimum_price"]) && !empty($_GET["maximum_price"])){
$sql = "{$sql} AND item_price >={$_GET['minimum_price']} && item_price <={$_GET['maximum_price']} ";    
}

if(isset($_GET["minimum_price"])){$_SESSION["minimum_price"] = $_GET["minimum_price"];}
if(isset($_GET["maximum_price"])){$_SESSION["maximum_price"] = $_GET["maximum_price"];}

        //下限額のみ
if(!empty($_GET["minimum_price"]) && empty($_GET["maximum_price"])){
$sql = "{$sql} AND item_price >={$_GET['minimum_price']} ";    
}       
        //上限額のみ
if(empty($_GET["minimum_price"]) && !empty($_GET["maximum_price"])){
$sql = "{$sql} AND item_price <={$_GET['maximum_price']} ";    
}  
      

/**-----------------------------------------------------------
    検索条件をセッションに保存
 ------------------------------------------------------------*/
if(isset($_GET["cmd"]) && $_GET["cmd"] == "do_search" ){
    $_SESSION["search_sql"] = $sql;
}

/**-----------------------------------------------------------
    並び替え
 ------------------------------------------------------------*/
if(isset($_GET["sortkey"])){

        //検索条件なしで並び替え($_SESSION["search_sql"]がNULL)
    if(!isset($_SESSION["search_sql"])){
        if($_GET["sortkey"] == "01"){
        $sql = $sql."order by item_price asc";
        }
        if($_GET["sortkey"] == "02"){
        $sql = $sql."order by item_price desc";
        }
        if($_GET["sortkey"] == "03"){
        $sql = $sql."order by item_insert_date asc";
        }
    }else{
        //検索条件ありで並び替え($_SESSION["search_sql"]あり)
        if($_GET["sortkey"] == "01"){
        $sql = "{$_SESSION['search_sql']}order by item_price asc";
        }
        if($_GET["sortkey"] == "02"){
        $sql = "{$_SESSION['search_sql']}order by item_price desc";
        }
        if($_GET["sortkey"] == "03"){
        $sql = "{$_SESSION['search_sql']}order by item_insert_date asc";
        }  
    }

}else{
    $sql = $sql."ORDER BY item_insert_date asc";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="良質のアイテムが手に入るファッション通販サイト。ぶぶた BUBUTAはレディースファッション洋服通販サイトです。">
<title>ぶぶた　BUBUTA ss公式 | レディースファッション通販のぶぶた【公式】</title>
<link href="common/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body id="item_list">
<div class="wrapper">
    <?php require_once('header_common.php')?>
    <div class="container">
    <?php require_once('left_pane.php')?>
        <div class="main_wrapper">
            <div class="main_contents">
                <h2>
                    <img class="product_logo" src="common/img/main_contents_title_products.png" alt="商品一覧">
                </h2>
                <div class="sort_item_wrapper">
                    <div class="sort_item_byorder">
                        <p>並び替え：</p>
                        <form name="sort_form" class="sort_form" action="#" method="GET">
                            <div class="select_wrap">
                                <select name="sortkey" class="sortkey" onchange="submit(this.form)">
                                    <option value="01" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="01"){echo "selected";} ?>>価格の安い順</option>
                                    <option value="02" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="02"){echo "selected";} ?>>価格の高い順</option>
                                    <option value="03" <?php if(isset($_GET['sortkey']) && $_GET['sortkey']=="03"){echo "selected";}elseif(!isset($_GET['sortkey'])){echo "selected";} ?>>新着順</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="main_contents_inner">
                    <div class="item_list_left">

<?php
    $stmt = $pdo->query($sql); 
    $items = $stmt->fetchAll();
    if($items){
        foreach ($items as $item){
?>
                        <div class="products">
                            <div class="product_inner">
                                <a class="product_link" href="item_detail.php?item_code=<?php print(htmlspecialchars( $item["item_code"])); ?>">
                                    <img src="img/items/<?php print($item["item_image"]);?>" alt="" />
                                    <p class="item_name"><?php print($item["item_name"]); ?></p>
                                    <p class="item_list_price">&yen;<?php print(number_format($item["item_price"]*$taxIn));?></p>
                                </a>
                            </div>
                        </div>
<?php 
    }
}else{
?>
                        <div class="txt_wrapper">
                            <p class="none_txt">該当する商品はありません。</p>
                        </div>                    
<?php
}
?>
                        </div>
                        <div class="item_list_rank">
                            <h3>人気ランキング</h3>
<?php    
$sql = "SELECT A.item_code, A.item_name, A.item_image, A.item_price, COUNT(B.item_code) AS '販売数量' FROM items AS A LEFT JOIN order_detail AS B ON A.item_code = B.item_code GROUP by A.item_code, A.item_code ORDER BY 販売数量 DESC LIMIT 3";
$stmt = $pdo->query($sql); 
$items = $stmt->fetchAll();
$i=0;
foreach ($items as $item){    
$i++;
?>
                        <div class="products">
                            <div class="product_inner">
                                <span>No.<?= $i?></span>
                                <a class="product_link" href="item_detail.php?item_code=<?php print(htmlspecialchars( $item["item_code"])); ?>">
                                    <img src="img/items/<?php print($item["item_image"]);?>" alt="" />
                                    <p class="item_name"><?php print($item["item_name"]); ?></p>
                                    <p class="item_list_price">&yen;<?php print(number_format($item["item_price"]*$taxIn));?></p>
                                </a>
                            </div>
                        </div>
<?php 
}
$con->close();
?>
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
