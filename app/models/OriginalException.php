<?php
namespace Models;

class OriginalException extends \Exception {

    /**
     * データ取得失敗:$code = 111
     * データ更新失敗:$code = 222
     * データ削除失敗:$code = 333
     * データ登録失敗:$code = 444
     */
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }
}

?>
