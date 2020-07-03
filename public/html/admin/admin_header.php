<div id="header">
    <div class="header_bg_area">
        <div class="header_inner">
            <div class="header_contents">
                <h1 class="main_title">
                    <a href="/html/admin/admin_index.php">
                        <img class="main_logo" src="/img/main_logo.png">
                    </a>
                </h1>
                <?php if(isset($_SESSION['admin_id'])):?>
                    <form method="POST" action="">
                        <input class="admin_logout_btn" type="submit" value="ログアウト">
                        <input type="hidden" name="cmd" value="admin_logout">
                    </form>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>