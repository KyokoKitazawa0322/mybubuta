
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
		  
<script>
$(document).ready(function(){
  $('.bunner').bxSlider({
  auto: true,
  mode:'fade',
  speed: 1000,
  pause: 3000,
  controls: false,
  infiniteLoop: true,
  slideWidth: 200,
                    });
});
</script>

<div id="leftbox">
    <div class="bunner">
        <img src="common/img/bunner01.jpg"/>
        <img src="common/img/bunner02.jpg"/>
        <img src="common/img/bunner03.jpg"/>
    </div>
    <form name="login_form" action="item_list.php" method="GET">
        <input type="hidden" name="cmd" value="do_search" />
        <div class="box" id="search">
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
                                <input type="checkbox" name="coat" id="coat" value="1" <?php if(isset($_SESSION["coat"]) && $_SESSION["coat"] == "1" ){ print( "checked" ); } ?> />
                                <label for="coat">コート</label>
                            </li>
                            <li>
                                <input type="checkbox" name="dress" id="dress" value="1" <?php if(isset($_SESSION["dress"]) && $_SESSION["dress"] == "1" ){ print( "checked" ); } ?> />
                                <label for="dress">ワンピース</label>
                            </li>
                            <li>
                                <input type="checkbox" name="skirt" id="skirt" value="1" <?php if(isset($_SESSION["skirt"]) && $_SESSION["skirt"] == "1" ){ print( "checked" ); } ?> />
                                <label for="skirt">スカート</label> 
                            </li>
                            <li>
                                <input type="checkbox" name="tops" id="tops" value="1" <?php if(isset($_SESSION["tops"]) && $_SESSION["tops"] == "1" ){ print( "checked" ); } ?> />
                                <label for="tops">トップス</label> 
                            </li>
                            <li>
                                <input type="checkbox" name="pants" id="pants" value="1" <?php if(isset($_SESSION["pants"]) && $_SESSION["pants"] == "1" ){ print( "checked" ); } ?> />
                                <label for="pants">パンツ</label> 
                            </li>
                            <li>
                                <input type="checkbox" name="bag" id="bag" value="1" <?php if(isset($_SESSION["bag"]) && $_SESSION["bag"] == "1" ){ print( "checked" ); } ?> />
                                <label for="bag">バッグ</label> 
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
