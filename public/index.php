<?php
require_once (__DIR__."/html/item_list.php");

/*
「execute」メソッドを実行するSQL文に引数があった場合(後で値を指定するために「?」や名前付きパラメータを指定した場合)、「execute」メソッドの引数に、値を配列の形で指定します。

例えば「?」を使って2箇所値を指定するようなSQL文を使った場合で考えます。

$sql = 'select id, name from shouhin where id > ? AND id < ?';
$stmt = $dbh->prepare($sql);
最初の部分に数字の「2」を、次の部分に数字の「4」を指定しようとした場合には下記のようになります。

$sql = 'select id, name from shouhin where id > ? AND id < ?';
$stmt = $dbh->prepare($sql);
$stmt->execute(array(2, 4));
名前付きパラメータを使った場合は、値の指定の仕方が若干異なります。具体的には下記のようになります。

$sql = 'select id, name from shouhin where id > :kagen AND id < :jyougen';
$stmt = $dbh->prepare($sql);
$stmt->execute(array(':kagen'=>2, ':jyougen'=>4));
*/
?>

