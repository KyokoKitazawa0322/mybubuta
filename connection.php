<?php
//header(Location:)とa hrefに使用
$server = "http://".$_SERVER['SERVER_NAME']."/sample/";

class Connection{
    // くらす内からconst定数を参照するときにはself::定数名の形式で指定
    const DB_NAME = 'heroku_4f96fa5a824de8a';
    const HOST = 'us-cdbr-iron-east-01.cleardb.net';
    const USER = 'b3c0c47b00dff6';
    const PASS = '9cfd0c93';
    private $pdo = null;

    public function __construct(){
    }

    //セッション開始
    public function start(){
        session_start();
    }

    //データベース切断
    public function close(){
        $this->pdo = null;
    }

    //DB接続チェック
    public function isConnection(){
        return ((bool)($this->pdo instanceof PDO));
    }

    public function pdo(){
        $dsn = "mysql:dbname=".self::DB_NAME.";charset=utf8;host=".self::HOST;
        $db_user = self::USER;
        $db_pass = self::PASS;

        try {
        $pdo = new PDO($dsn, $db_user, $db_pass);
        }catch(PDOExeption $e){
            echo $e->getMesseage();
            die("MySQL接続エラー");
        }
        $this->pdo = $pdo;
        return $pdo;
    }
    
	/**-----------------------------------------------------------
    　   マニュアル記載のsessionID管理法
	 ------------------------------------------------------------*/
    function my_session_start() {
    session_start();
    if (isset($_SESSION['destroyed'])) {
       if ($_SESSION['destroyed'] < time()-300) {
           // 通常は起こるべきではない。攻撃や不安定なネットワークによる可能性がある
           // このユーザーのセッションから、全ての認証ステータスを削除
           remove_all_authentication_flag_from_active_sessions($_SESSION['userid']);
           throw(new DestroyedSessionAccessException);
       }
       if (isset($_SESSION['new_session_id'])) {
           // 完全に expire してはいない。
           // Cookie が不安定なネットワークによって失われた可能性がある。
           // 適切なセッションIDのクッキーを設定するためにリトライする。
           // 注意: 認証フラグを削除したい場合は、セッションIDを再度設定しようとしてはいけない。
           session_commit();
           session_id($_SESSION['new_session_id']);
           // 新しいセッションIDが存在しているはず
           session_start();
           return;
            }
        }
    }

    function my_session_regenerate_id() {
        // 不安定なネットワークのために、セッションID が設定されなかったときは、
        // 新しいセッションID が、適切なセッションIDに設定されることが必須。
        $new_session_id = session_create_id();
        $_SESSION['new_session_id'] = $new_session_id;

        // 破棄された時のタイムスタンプを設定
        $_SESSION['destroyed'] = time();

        // 現在のセッションを書き込んで閉じる
        session_commit();

        // 新しいセッションを新しいセッションIDで開始
        session_id($new_session_id);
        ini_set('session.use_strict_mode', 0);
        session_start();
        ini_set('session.use_strict_mode', 1);

        // 新しいセッションには、以下の情報は不要
        unset($_SESSION['destroyed']);
        unset($_SESSION['new_session_id']);
    }
}
    

