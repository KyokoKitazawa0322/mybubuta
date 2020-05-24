<?php use \Config\Config; ?>
<script>
<!--
    
$(function () {
	$('.drawer_button').click(function () {
		$(this).toggleClass('active');
		$('.drawer_bg').fadeToggle();
		$('nav').toggleClass('open');
        $('.drawer_btn_wrap').toggleClass('open');
	})
	$('.drawer_bg').click(function () {
		$(this).fadeOut();
		$('.drawer_button').removeClass('active');
		$('nav').removeClass('open');
        $('.drawer_btn_wrap').removeClass('open');
	});
})

// --> 
</script>

<div id="header">
<div class="header_bg_area">
    <div class="header_inner">
        <div class="header_contents">
            <div class="drawer_menu">
                <div class="drawer_bg"></div>
                <div class="drawer_btn_wrap">
                    <button type="button" class="drawer_button">
                        <span class="drawer_bar drawer_bar1"></span>
                        <span class="drawer_bar drawer_bar2"></span>
                        <span class="drawer_bar drawer_bar3"></span>
                        <span class="drawer_menu_text drawer_text">MENU</span>
                        <span class="drawer_close drawer_text">CLOSE</span>
                    </button>
                </div>
                <nav class="drawer_nav_wrapper">
                    <div class="drawer_nav">
                    <form name="login_form" action="/html/item_list.php" method="GET">
                        <input type="hidden" name="cmd" value="do_search" />
                        <div class="box">
                            <div class="box_2">
                                <div class="box_header_menu">
                                    <div class="btn_wrap">
                                        <a class="btn_design_02 btn_cmn_full" href="/html/login.php">ログイン</a>
                                    </div>
                                    <div class="btn_wrap">
                                        <a class="btn_design_02 btn_cmn_full" href="/html/register/register.php">新規会員登録</a>
                                    </div>
                                </div>
                                <div class="side_title_wrap">
                                    <h3 class="side_title">
                                        <img src="/img/search_icon.png" alt="検索" />
                                        <span>商品検索</span>
                                    </h3>
                                </div>
                                <div class="search_item">
                                    <p>商品名</p>
                                    <div class="text_wrapper">
                                        <input type="text" name="keyword" class="text" placeholder="キーワード" value="<?php if(isset($_SESSION['search']["keyword"])){echo $_SESSION['search']["keyword"]; }?>" />
                                    </div>
                                </div>
                                <div class="search_item_bycategory">
                                    <p>カテゴリ</p>
                                    <div class="search_category">
                                        <ul class="category_list">
                                            <?php foreach(Config::CATEGORY as $key=>$value):?>
                                            <li>
                                                <input type="checkbox" name="<?=$key?>" id="<?=$key?>" value="1" <?php if(isset($_SESSION['search']['category'][$key])){ echo "checked"; } ?> />
                                                <label for="<?=$key?>"><?php echo $value?></label>
                                            </li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="search_item_byprice">
                                    <p>価格</p><br/>
                                    <div class="select_wrap">
                                        <select name="min_price" class="minimum_price">
                                            <option value="">  </option>
                                            <?php foreach(Config::PRICERANGE as $key):?>
                                                <option value="<?= $key ?>" <?php if(isset($_SESSION['search']['min_price']) && $_SESSION['search']['min_price'] == $key){echo "selected";}?>>￥<?= number_format($key)?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <span class="length">～</span>
                                    <div class="select_wrap">
                                        <select name="max_price" class="maximum_price">
                                            <option value="">  </option>
                                            <?php foreach(Config::PRICERANGE as $key):?>
                                                <option value="<?= $key ?>" <?php if(isset($_SESSION['search']['max_price']) && $_SESSION['search']['max_price'] == $key){echo "selected";}?>>￥<?= number_format($key)?></option>
                                            <?php endforeach;?>
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
                </nav>
            </div>
            <h1 class="main_title">
            <a href="/html/item_list.php?cmd=item_list">
                <img class="main_logo" src="/img/main_logo.png">
            </a>
            </h1>
            <div class="header_logo_area">
                <a href="/html/login.php">
                    <img class="header_logo" src="/img/header_icon_member.png">
                </a>
                <a href="/html/mypage/mypage_favorite.php">
                    <img class="header_logo" src="/img/header_icon_like.png">
                </a>
                <a href="/html/cart.php">
                    <img class="header_logo" src="/img/header_icon_cart.png">
                </a>
            </div>
        </div>
    </div>
</div>
</div>