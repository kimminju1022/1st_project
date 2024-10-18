<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once(MY_ROOT_DB_LIB);
require_once(MY_ROOT_UTILITY);

session_start();

go_login();

// pagenation 관련-------------
$conn = null;
$max_board_cnt = 0;
$max_page = 0;
try {  
    // PDO Instance
    $conn = my_db_conn();
    
    // cnt---------
    // max page 획득 처리
    $max_board_cnt = cnt_checklist_todo_history($conn); // 게시글 총 수 획득
    $max_page = (int)(ceil($max_board_cnt / MY_BOARD_CARD_COUNT)); // max page 획득

    // pagination 설정
    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // 현재 페이지 획득
    $offset = ($page - 1) * MY_BOARD_CARD_COUNT; // 오프셋 설정
    $prev_page_button_number = $page - 1 < 1 ? 1 : $page - 1; // 이전 버튼
    $next_page_button_number = $page + 1 > $max_page ? $max_page : $page + 1; // 다음버튼

    // pagination select 처리
    $arr_prepare = [
        "limit"  => MY_BOARD_CARD_COUNT,
        "offset"   => $offset
    ];

    $result = get_todolist_board_history($conn, $arr_prepare);

} catch (Throwable $th) {
    echo $th->getMessage();
    exit;
}

?>


<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/history.css">
    <title>History-page</title>
</head>
<body>
    <container>
        <div class="main-background">
            <div class="side-bar">
                <div class="back-side-bar">
                    <div class="logo"><img src="/img/logo.png" alt="" height="120px" width="150px"></div>
                    <div class="main-side-bar">
                        <div class="emotion">
                            <p class="emotion_comment">TODAY IS... </p>
                        </div>
                        <div class="profile"><img class="profile-img" src="/img/profile.jpg" alt="" width="250px" height="250px"></div>
                        <br>
                        <div class="comment">
                            <p>난... ㄱr끔... </p>
                            <p>눈물을 흘린 ⊂ ト .... </p>
                            <p>ㄱ ㅏ끔은 눈물을 참을 수 없는 </p>
                            <p>내가 별루ㄷ ㅏ... </p>
                            <p>맘이 ㅇ ㅏ ㅍ ㅏ 서.... </p>
                            <p>소ㄹ ㅣ치며... </p>
                            <p>울 수 있 ㄷㅏ는건.... </p>
                            <p>좋은ㄱ ㅓ ㅇ ㅑ..... </p>
                        </div>
                        <form action="/logout.php" method="post">
                            <button type="submit" class="logout">로그아웃</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="main-content">
                    <div class="main-title">
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>
                    <div class="todo-list-title">History <br> <hr width="80px"></div>
                    <div class="to_body">
                        <!-- todo card foreach로 2행 4열 -->
                        <div class="to_main">
                            <div class="to_list">
                                <?php foreach($result as $item) {?>                                
                                    <div class="to_post">
                                        <a href="/history_detail.php?<?php echo "id=".$item["id"]."&page=".$page?>">
                                        <p class="to_title"><span class="title-hidden"><?php echo $item["name"] ?></span></p></a>
                                        <p class="to_date"><?php echo $item["deadline"] ?></p>
                                        <div class="list_box">
                                        <?php foreach($item["contents"] as $chk_lists ){ ?>
                                            <input class="list_chkbox" type="checkbox" disabled><span class="content-hidden"><?php echo $chk_lists["content"]?></span>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="to_pagination">
                                <?php if ($page !== 1) { ?>
                                <a href="/history.php?<?php echo "&page=".$prev_page_button_number ?>"><img src="/img/arrow-left.png" alt="before" class="img_btn" width="30px" height="30px"></a>
                                <?php } else { ?>
                                <div class="p_btn"></div>
                                <?php } ?>
                                <button class="p_btn"><?php echo $page ?></button>
                                <?php if ($page !== $max_page) { ?>
                                <a href="/history.php?<?php echo"&page=".$next_page_button_number ?>"><img src="/img/arrow-right.png" alt="before" class="img_btn" width="30px" height="30px"></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-bar">
                <div class="home"><a href="/index.php" class="home-tab">HOME</a></div>
                <div class="todo"><a href="/board.php?page_checklist_today=1&page=1" class="todo-tab">TODO</a></div>
                <div class="history"><a href="/history.php?page=1" class="history-tab">HISTORY</a></div>
                <div class="visit-btn"><a href="/visit.php" class="visit-tab">VISIT</a></div>
                <div class="credit"><a href="#" class="credit-tab">CREDIT</a></div>
            </div>
        </div>
    </container>
</body>
</html>