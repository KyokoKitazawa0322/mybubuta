<?php
require_once (__DIR__ ."/../../../vendor/autoload.php");
session_cache_limiter('none');
session_start();

use \Config\Config;

$adminSales = new \Controllers\AdminSalesAction();
$adminSales->execute();
$result = $adminSales->getResult();
$orderDetails = $adminSales->getOrderDetails();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
<!--

$(function(){
    $('input#search_btn').click(function(){ 
        var content = $('input[name="search_content"]:checked').val();
        switch(content){
            case "day":
                var year = $('#year_1').val();
                var month = $('#month_1').val();
                var day = $('#day_1').val();
                break;
            case "month":
                var year = $('#year_2').val();
                var month = $('#month_2').val();
                var day = null;
                break;
            case "term":
                var year = $('#year_3').val();
                var month = $('#month_3').val();
                var day = $('#day_2').val();
                var year_2 = $('#year_4').val();
                var month_2 = $('#month_4').val();
                var day_2 = $('#day_3').val();
                
                $('input#search_year_2').val(year_2);
                $('input#search_month_2').val(month_2);
                $('input#search_day_2').val(day_2);
                break;
        }
        $('input#content').val(content);
        $('input#search_year').val(year);
        $('input#search_month').val(month);
        $('input#search_day').val(day);
        $('form#searchForm').submit();
    });
});
    
window.onload = (function(){
    //日付範囲決定
    function calcDays(){
        $('#day_1').empty();
        var y = $('#year_1').val();
        var m = $('#month_1'). val();

        if (m == "" || y == "") { //年か月が選択されていない時は31日まで表示
            var last = 31;
        }else if (m == 2 && ((y % 400 == 0) || ((y % 4 == 0) && (y % 100 != 0)))) {
            var last = 29; //うるう年判定
        }else {
            var last = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)[m-1];
        }

        $('#day_1').append('<option value="">日</option>');
        for (var i = 1; i <= last; i++) {
            if (d == i) { //日がすでに選択されている場合はその値が選択された状態で表示
                $('#day_1').append('<option value="'+i+'" selected>'+i+'</option>');
            } else {
                $('#day_1').append('<option value="'+i+'">'+i+'</option>');
            }
        }
    }
    
    function calcDays_2(){
        $('#day_2').empty();
        var y = $('#year_3').val();
        var m = $('#month_3'). val();

        if (m == "" || y == "") { //年か月が選択されていない時は31日まで表示
            var last = 31;
        }else if (m == 2 && ((y % 400 == 0) || ((y % 4 == 0) && (y % 100 != 0)))) {
            var last = 29; //うるう年判定
        }else {
            var last = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)[m-1];
        }

        $('#day_2').append('<option value="">日</option>');
        for (var i = 1; i <= last; i++) {
            if (d_2 == i) { //日がすでに選択されている場合はその値が選択された状態で表示
                $('#day_2').append('<option value="'+i+'" selected>'+i+'</option>');
            } else {
                $('#day_2').append('<option value="'+i+'">'+i+'</option>');
            }
        }
    }
    
    function calcDays_3(){
        $('#day_3').empty();
        var y = $('#year_4').val();
        var m = $('#month_4'). val();

        if (m == "" || y == "") { //年か月が選択されていない時は31日まで表示
            var last = 31;
        }else if (m == 2 && ((y % 400 == 0) || ((y % 4 == 0) && (y % 100 != 0)))) {
            var last = 29; //うるう年判定
        }else {
            var last = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)[m-1];
        }

        $('#day_3').append('<option value="">日</option>');
        for (var i = 1; i <= last; i++) {
            if (d_3 == i) { //日がすでに選択されている場合はその値が選択された状態で表示
                $('#day_3').append('<option value="'+i+'" selected>'+i+'</option>');
            } else {
                $('#day_3').append('<option value="'+i+'">'+i+'</option>');
            }
        }
    }


    var d = 0;
    var d_2 = 0;
    var d_3 = 0;
    var date = new Date();
    var nowyear = date.getFullYear();
    
    $(function(){
        var data_year_1 = <?php $adminSales->checkOptionValue("day", "year");?>;
        var data_year_2 = <?php $adminSales->checkOptionValue("month", "year");?>;
        var data_year_3 = <?php $adminSales->checkOptionValue("term", "year");?>;
        var data_year_4 = <?php $adminSales->checkOptionValue("term", "year_2");?>;
        for (var i = nowyear; i >= 2019; i--) {
            if(data_year_1 == i){
                $('#year_1').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#year_1').append('<option value="'+i+'">'+i+'</option>');
            }
            if(data_year_2 == i){
                $('#year_2').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#year_2').append('<option value="'+i+'">'+i+'</option>');
            }
            if(data_year_3 == i){
                $('#year_3').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#year_3').append('<option value="'+i+'">'+i+'</option>');
            }
            if(data_year_4 == i){
                $('#year_4').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#year_4').append('<option value="'+i+'">'+i+'</option>');
            }
        }
        
        var data_month_1 = <?php $adminSales->checkOptionValue("day", "month");?>;
        var data_month_2 = <?php $adminSales->checkOptionValue("month", "month");?>;
        var data_month_3 = <?php $adminSales->checkOptionValue("term", "month");?>;
        var data_month_4 = <?php $adminSales->checkOptionValue("term", "month_2");?>;
        //1月～12月まで表示
        for (var i = 1; i <= 12; i++) {
            if(data_month_1 == i){
                $('#month_1').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#month_1').append('<option value="'+i+'">'+i+'</option>');  
            }
            if(data_month_2 == i){
                $('#month_2').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#month_2').append('<option value="'+i+'">'+i+'</option>');  
            }
            if(data_month_3 == i){
                $('#month_3').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#month_3').append('<option value="'+i+'">'+i+'</option>');  
            }
            if(data_month_4 == i){
                $('#month_4').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#month_4').append('<option value="'+i+'">'+i+'</option>');  
            }
        }
        
        var data_day_1 = <?php $adminSales->checkOptionValue("day", "day");?>;
        var data_day_2 = <?php $adminSales->checkOptionValue("month", "day");?>;
        var data_day_3 = <?php $adminSales->checkOptionValue("term", "day_2");?>;

        //1日～31日まで表示
        for (var i = 1; i <= 31; i++) {
            if(data_day_1 == i){
                $('#day_1').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#day_1').append('<option value="'+i+'">'+i+'</option>');
            }
            if(data_day_2 == i){
                $('#day_2').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#day_2').append('<option value="'+i+'">'+i+'</option>');
            }
            if(data_day_3 == i){
                $('#day_3').append('<option value="'+i+'" selected>'+i+'</option>');
            }else{
                $('#day_3').append('<option value="'+i+'">'+i+'</option>');
            }
        }

        $('#day_1').change(function(){
            d = $(this).val();
        });
        //年か月が変わるごとに日数を計算
        $('#year_1').change(calcDays);
        $('#month_1').change(calcDays);
        
        $('#day_2').change(function(){
            d_2 = $(this).val();
        });
        //年か月が変わるごとに日数を計算
        $('#year_3').change(calcDays_2);
        $('#month_3').change(calcDays_2);
        
        $('#day_3').change(function(){
            d_3 = $(this).val();
        });
        //年か月が変わるごとに日数を計算
        $('#year_4').change(calcDays_3);
        $('#month_4').change(calcDays_3);
    });
});
// --> 
</script>
</head>
<body class="admin" id="admin_sales">
	<div class="wrapper">
        <?php require_once(__DIR__.'/admin_header.php')?>
		<div class="container">
		    <div class="main_wrapper">
		        <div class="main_contents">
                    <div class="admin_title">
                        <h2><a href="/html/admin/admin_sales.php">売上管理画面</a></h2>
                    </div>
		            <div class="main_contents_inner">
                        <?php if(isset($_SESSION['search_error']['radio'])):?>
                            <p class="error_text">「日計/月計/期間指定」のいずれかを選択してください。</p>
                        <?php elseif(isset($_SESSION['search_error']['select'])):?>
                            <p class="error_text">日付を正しく選択してください。</p>  
                        <?php endif;?>
                        <table class="admin_sales_list_wrapper">
                            <tr>
                                <th>
                                    <input type="radio" id="search_day" name="search_content" value="day" <?=$adminSales->checkRadioValue("day");?>>
                                    <label for="search_day">日計</label>
                                </th>
                                <td>
                                    <select id="year_1">
                                        <option value="">--西暦--</option>
                                    </select>
                                    <select id="month_1">
                                        <option value="">-月-</option>
                                    </select>
                                    <select id="day_1">
                                        <option value="">-日-</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <input type="radio" id="search_month" name="search_content" value="month" <?=$adminSales->checkRadioValue("month");?>>
                                    <label for="search_month">月計</label>
                                </th>
                                <td>
                                    <select id="year_2">
                                        <option value="">--西暦--</option>
                                    </select>
                                    <select id="month_2">
                                        <option value="">-月-</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><input type="radio" id="search_term" name="search_content" value="term" <?=$adminSales->checkRadioValue("term");?>>
                                    <label for="search_term">期間指定</label>
                                </th>
                                <td>
                                    <select id="year_3">
                                        <option value="">--西暦--</option>
                                    </select>
                                    <select id="month_3">
                                        <option value="">-月-</option>
                                    </select>
                                    <select id="day_2">
                                        <option value="">-日-</option>
                                    </select>
                                    <span>～</span>
                                    <select id="year_4">
                                        <option value="">--西暦--</option>
                                    </select>
                                    <select id="month_4">
                                        <option value="">-月-</option>
                                    </select>
                                    <select id="day_3">
                                        <option value="">-日-</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div class="search_sales_btn_wrap">
                            <input class="btn_cmn_l btn_design_01" type="submit" id="search_btn" value="検索する">
                        </div>
                        <?php if($result):?>
                            <table class="sales_aggregate">
                                <tr>
                                    <th>対象期間</th>
                                    <th>合計売上数量</th>
                                    <th>合計売上金額</th>
                                </tr>
                                <tr>
                                    <td><?=Config::h($_SESSION['search_term']);?></td>
                                    <td><?=Config::h(number_format($result->getTotalQuantityByTerm()));?>点</td>
                                    <td>&yen;<?=Config::h(number_format($result->getTotalAmountByTerm()));?></td>          
                                </tr>
                            </table>
                        <?php endif;?>
                        <?php if($orderDetails):?>
                                <table class="sales_result_list">
                                    <tr>
                                        <th>購入日</th>
                                        <th>注文番号</th>
                                        <th>顧客ID</th>
                                        <th>商品コード</th>
                                        <th>商品名</th>
                                        <th>数量</th>
                                        <th>商品価格</th>
                                        <th>消費税</th>
                                        <th>合計金額</th>
                                    </tr>
                                    <?php foreach($orderDetails as $orderDetail):?>
                                    <tr>
                                        <td><?=Config::h($orderDetail->getPurchaseDate());?></td>
                                        <td><?=Config::h($orderDetail->getOrderId());?></td>
                                        <td><?=Config::h($orderDetail->getCustomerId());?></td>
                                        <td><?=Config::h($orderDetail->getItemCode());?></td>
                                        <td><?=Config::h($orderDetail->getItemName());?></td>
                                        <td><?=Config::h($orderDetail->getItemQuantity());?></td>
                                        <td>&yen;<?=Config::h(number_format($orderDetail->getItemPrice()));?></td>
                                        <td>&yen;<?=Config::h(number_format($orderDetail->getItemTax()));?></td>
                                        <td>&yen;<?=Config::h(number_format($orderDetail->getTotalPrice()));?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </table>
                        <?php endif;?>
		            </div>
		        </div>
		    </div>
		</div>
		<div id="footer">
		    <p class="copy">&copy; 2020 BUBUTA All Rights Reserved.</p>
		</div>
        <form method="POST" id="searchForm" action="#">
            <input type="hidden" name="cmd" value="search_sales">
            <input type="hidden" id="content" name="content" value>
            <input type="hidden" id="search_year" name="year" value>
            <input type="hidden" id="search_month" name="month" value>
            <input type="hidden" id="search_day" name="day" value>
            <input type="hidden" id="search_year_2" name="year_2" value>
            <input type="hidden" id="search_month_2" name="month_2" value>
            <input type="hidden" id="search_day_2" name="day_2" value>
        </form>
    </div>
</body>
</html>