<?php use \Config\Config; ?>
<script>
<!--
$(function() {
    jQuery(document).ready(function($){
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
});
// --> 
</script>
<div class="side_menu">
    <div class="bunner_wrap_pc">
        <div class="bunner">
            <img src="/img/bunner01.jpg"/>
            <img src="/img/bunner02.jpg"/>
            <img src="/img/bunner03.jpg"/>
        </div>
    </div>
    <form name="login_form" action="/html/item_list.php" method="GET">
        <input type="hidden" name="cmd" value="do_search" />
        <div class="box">
            <div class="box_2">
                <div class="side_title_wrap">
                    <h3 class="side_title">
                        <img src="/img/search_icon.png" alt="検索" />
                        <span>商品検索</span>
                    </h3>
                </div>
                <div class="search_item">
                    <p>商品名</p>
                    <div class="text_wrapper">
                        <input type="text" name="item_name" class="text" placeholder="キーワード" value="<?php if(isset($_SESSION['search']["item_name"])){echo $_SESSION['search']["item_name"]; }?>" />
                    </div>
                </div>
                <div class="search_item_bycategory">
                    <p>カテゴリ</p>
                    <div class="search_category">
                        <ul class="category_list">
                            <?php foreach(Config::CATEGORY as $key=>$value):?>
                            <li>
                                <input type="checkbox" name="<?= $key?>" id="<?= $key?>_l" value="1" <?php if(isset($_SESSION['search'][$key])){ echo "checked"; } ?> />
                                <label for="<?= $key?>_l"><?= $value?></label>
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
