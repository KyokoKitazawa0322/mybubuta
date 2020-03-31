<script>
<!--
$(function () {
    $('.container').on('click', function () {
        if ($('.is-open').is(':visible')) {
            $('#openMenu').trigger('click');
        } else {
            event.stopPropagation();
        }
    });
});
    
$(function(){
    $(window).resize(function(){ // ウィンドウがリサイズされたら
    var $window = $(this).width();
    var bp = 980;
    if($window > bp){
        $(".is-open").hide();
    }
});
});

<!--
$(function(){
  $("#openMenu").on('click',function(){
    $(".is-open").slideToggle();
  });
});
// --> 
</script>

<div id="header">
<div class="header_bg_area">
    <div class="header_inner">
        <div class="header_contents">
            <img class="top_search_logo" id="openMenu" src="common/img/search_icon.png">
            <div class="is-open">
                <div class="side_menu">
                   <form name="login_form" action="item_list.php" method="GET">
                        <input type="hidden" name="cmd" value="do_search" />
                        <div class="box">
                            <div class="box_2">
                                <div class="side_title_wrap">
                                    <h3 class="side_title">
                                        <img src="common/img/search_icon.png" alt="検索" />
                                        <span>商品検索</span>
                                    </h3>
                                </div>
                                <div class="search_item">
                                    <p>商品名</p>
                                    <div class="text_wrapper">
                                        <input type="text" name="item_name" class="text" placeholder="キーワード" value="<?php if(isset($_SESSION["item_name"])){print( $_SESSION["item_name"]); }?>" />
                                    </div>
                                </div>
                                <div class="search_item_bycategory">
                                    <p>カテゴリ</p>
                                    <div class="search_category">
                                        <ul class="category_list">
                                            <li>
                                                <input type="checkbox" name="coat" id="coat01" value="1" <?php if(isset($_SESSION["coat"]) && $_SESSION["coat"] == "1" ){ print( "checked" ); } ?> />
                                                <label for="coat01">コート</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="dress" id="dress01" value="1" <?php if(isset($_SESSION["dress"]) && $_SESSION["dress"] == "1" ){ print( "checked" ); } ?> />
                                                <label for="dress01">ワンピース</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="skirt" id="skirt01" value="1" <?php if(isset($_SESSION["skirt"]) && $_SESSION["skirt"] == "1" ){ print( "checked" ); } ?> />
                                                <label for="skirt01">スカート</label> 
                                            </li>
                                            <li>
                                                <input type="checkbox" name="tops" id="tops01" value="1" <?php if(isset($_SESSION["tops"]) && $_SESSION["tops"] == "1" ){ print( "checked" ); } ?> />
                                                <label for="tops01">トップス</label> 
                                            </li>
                                            <li>
                                                <input type="checkbox" name="pants" id="pants01" value="1" <?php if(isset($_SESSION["pants"]) && $_SESSION["pants"] == "1" ){ print( "checked" ); } ?> />
                                                <label for="pants01">パンツ</label> 
                                            </li>
                                            <li>
                                                <input type="checkbox" name="bag" id="bag01" value="1" <?php if(isset($_SESSION["bag"]) && $_SESSION["bag"] == "1" ){ print( "checked" ); } ?> />
                                                <label for="bag01">バッグ</label> 
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="search_item_byprice">
                                    <p>価格</p><br/>
                                    <div class="select_wrap">
                                        <select name="minimum_price" class="minimum_price">
                                            <option value="">  </option>
                                            <option value="1000" <?php if($_SESSION['minimum_price']=="1000"){echo "selected";}?>>￥1,000</option>
                                            <option value="3000"<?php if($_SESSION['minimum_price']=="3000"){echo "selected";}?>>￥3,000</option>
                                            <option value="5000"<?php if($_SESSION['minimum_price']=="5000"){echo "selected";}?>>￥5,000</option>
                                            <option value="8000"<?php if($_SESSION['minimum_price']=="8000"){echo "selected";}?>>￥8,000</option>
                                            <option value="10000"<?php if($_SESSION['minimum_price']=="10000"){echo "selected";}?>>￥10,000</option>
                                            <option value="20000"<?php if($_SESSION['minimum_price']=="20000"){echo "selected";}?>>￥20,000</option>
                                        </select>
                                    </div>
                                    <span class="length">～</span>
                                    <div class="select_wrap">
                                        <select name="maximum_price" class="maximum_price">
                                            <option value="">  </option>
                                            <option value="1000"<?php if($_SESSION['maximum_price']=="1000"){echo "selected";}?>>￥1,000</option>
                                            <option value="3000"<?php if($_SESSION['maximum_price']=="3000"){echo "selected";}?>>￥3,000</option>
                                            <option value="5000"<?php if($_SESSION['maximum_price']=="5000"){echo "selected";}?>>￥5,000</option>
                                            <option value="8000"<?php if($_SESSION['maximum_price']=="8000"){echo "selected";}?>>￥8,000</option>
                                            <option value="10000"<?php if($_SESSION['maximum_price']=="10000"){echo "selected";}?>>￥10,000</option>
                                            <option value="20000"<?php if($_SESSION['maximum_price']=="20000"){echo "selected";}?>>￥20,000</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="button_wrapper">
                                <input class="btn_design_01 btn_cmn_mid" type="submit" value="検索" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <h1 class="main_title">
            <a href="item_list.php?cmd=item_list">
                <img class="main_logo" src="common/img/main_logo.png">
            </a>
            </h1>
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
</div>