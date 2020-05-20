    <div id="mypage_menu">
        <div class="side_menu">
            <div class="box" id="mypage">
                <div class="box_2">
                    <p class="list_title">マイページメニュー</p>
                    <ul class="list_nav">
                        <li>
                            <a class="nav_item_link" href="/html/mypage/mypage.php">
                                <span>マイページトップ</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_item_link" href="/html/mypage/mypage_update.php">
                                <span>登録内容の確認・変更</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_item_link" href="/html/mypage/mypage_delivery.php">
                                <span>配送先の登録・変更</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_item_link" href="/html/mypage/mypage_favorite.php">
                                <span>お気に入り商品</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_item_link is-current" href="/html/mypage/mypage_order_history.php">
                                <span>ご注文履歴</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav_item_link" href="/html/mypage/mypage_leave.php">
                                <span>退会</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="logout_button_wrapper">
                <form method="POST" action="">
                    <input class="btn_design_03 btn_cmn_full" type="submit" value="ログアウト">
                    <input type="hidden" name="cmd" value="do_logout">
                </form>
            </div>
        </div>
    </div>
