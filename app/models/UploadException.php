<?php 
namespace Models;

class UploadException extends  \Models\OriginalException{

    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, 500);
    }

    private function codeToMessage($code){
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in server";
                $this->setUserMessage("ファイルサイズが上限を超えてます。");
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                $this->setUserMessage("ファイルサイズが上限を超えてます。");
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                $this->setUserMessage("ファイルサイズが上限を超えてます。");
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                $this->setUserMessage("ファイルが選択されてません。");
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                $this->setUserMessage("ファイル保存時にエラーが発生しました。");
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                $this->setUserMessage("ファイル保存時にエラーが発生しました。");
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                $this->setUserMessage("ファイル保存時にエラーが発生しました。");
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
}