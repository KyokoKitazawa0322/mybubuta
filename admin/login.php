<?php require_once "../system/common_admin.php";?>
<?php $page_title = "ログイン";?>
<?php require "header.php";?>
    <form action="login.php" method="post">
      <div>
        ログインID<br>
        <input type="text" name="user_loginid" size="30" value="">
      </div>
      <div>
        パスワード<br>
        <input type="password" name="user_password" size="30" value="">
      </div>
      <div>
        <input type="submit" name="send" value="ログインする">
      </div>
    </form>
<?php require "footer.php";?>
