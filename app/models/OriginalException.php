<?php
namespace Models;

class OriginalException extends \Exception {

    // 例外を再定義し、メッセージをオプションではなくする
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }
}

?>
