<?php require_once "../system/common_admin.php";?>
<?php $page_title = "記事編集";?>
<?php require "header.php";?>
    <form action="post_edit.php" method="post">
      <div>
        記事タイトル <span class="attention">[必須]</span><br>
        <input type="text" name="post_title" size="30" value="">
      </div>
      <div>
        記事本文<br>
        <textarea name="post_content" rows="5" cols="20"></textarea>
      </div>
      <div>
        <input type="submit" name="send" value="送信する">
      </div>
    </form>
<?php require "footer.php";?>
