<?php
namespace Config;

class Config{

    //消費税
    const TAX = 1.1; 
    const TAXRATE = 0.1;
    //配送料
    const POSTAGE = 500;
    
    const POSTAGEFREEPRICE = 10000;

    const CATEGORY = array('coat'=>'コート', 'dress'=>'ワンピース', 'skirt'=>'スカート', 'tops'=>'トップス', 'pants'=>'パンツ', 'bag'=>'バッグ');
    
    const PRICERANGE = array(1000, 3000, 5000, 8000, 10000, 20000); 
    
    const PREFECTURES = array('北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県','茨城県','栃木県','群馬県', '埼玉県','千葉県', '東京都', '神奈川県','新潟県',' 富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県', '山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');
    
    public static function outputLog($e){
        date_default_timezone_set('Asia/Tokyo');
        $datetime = date( "Y/m/d H:i:s");
        $trace = $e->getTraceAsString();
        $code = $e->getCode();
        $str = $e->getMessage();
        $message = "[time:{$datetime}]\t[trace:{$trace}]\t[ExceptionCode:{$code}]\t[content:{$str}]\r\n";
        error_log($message);
    }
}
?>
