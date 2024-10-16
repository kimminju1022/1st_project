<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once(MY_ROOT_DB_LIB);

// pagenation 관련-------------
$conn = null;
$max_board_cnt = 0;
$max_page = 0;
try {
    // PDO Instance
    $conn = my_db_conn();

    // get_checklist_today---------
    // max page 획득 처리
    $max_board_cnt = cnt_checklist_today($conn); // 게시글 총 수 획득
    $max_page = (int)ceil($max_board_cnt / MY_BOARD_CARD_COUNT); // max page 획득

    // pagination 설정
    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // 현재 페이지 획득
    $offset = ($page - 1) * MY_VISIT_COUNT; // 오프셋 설정
    $prev_page_button_number = $page - 1 < 1 ? 1 : $page - 1; // 이전 버튼
    $next_page_button_number = $page + 1 > $max_page ? $max_page : $page + 1; // 다음버튼

    // pagination select 처리
    $arr_prepare = [
        "list_cnt"  => MY_VISIT_COUNT,
        "offset"   => $offset
    ];
    
    // cnt_checklist_todo---------
    // max page 획득 처리
    $max_board_cnt1 = cnt_checklist_todo($conn); // 게시글 총 수 획득
    $max_page1 = (int)ceil($max_board_cnt1 / MY_BOARD_CARD_COUNT); // max page 획득

    // pagination 설정
    $page1 = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // 현재 페이지 획득
    $offset1 = ($page - 1) * MY_VISIT_COUNT; // 오프셋 설정
    $prev_page_button_number1 = $page - 1 < 1 ? 1 : $page - 1; // 이전 버튼
    $next_page_button_number1 = $page + 1 > $max_page ? $max_page : $page + 1; // 다음버튼

    // pagination select 처리
    $arr_prepare1 = [
        "list_cnt"  => MY_VISIT_COUNT,
        "offset"   => $offset1
    ];

    $result1 = get_checklist_today($conn, $arr_prepare);
    $result2 = get_todolist_board($conn, $arr_prepare1);

    $today_list_cnt = cnt_checklist_today($conn);
    $todo_list_cnt = cnt_checklist_todo($conn);

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
    <title>Main-page</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/board.css">
</head>

<body>
    <container>
        <div class="main-background">
            <div class="side-bar">
                <div class="back-side-bar">
                    <div class="main-side-bar">
                        <div class="emotion">
                            <p class="emotion_comment">TODAY IS... </p>
                        </div>
                        <div class="profile"><img class="profile-img" src="./img/profile.jpg" alt="" width="250px"
                                height="300px"></div>
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
                        <div class="logout"><button class="logout">로그아웃</button></div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="main-content">
                    <div class="main-title">
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>

                    <div class="to_body">
                        <!-- todo-list-title style위치 일치할 것 -->
                        <div class="todo-head"> 오늘까지 할 일 </div>
                        <div class="todo-deadline">
                        <a href="/board.php?page=<?php echo $prev_page_button_number ?>"><img src="/img/left-pagebtn.png" alt="왼쪽" class="to_btn"></a>

                           <!-- db lib에서 데이터 불러오기 -->
                            <!-- 최대 카드 2개, 이상일 시 pagination -->
                            <div class="to_box">
                                <?php foreach ($result1 as $item) { ?>
                                    <p><?php echo $item["content"] ?></p>
                                <?php } ?>
                            </div>

                            <?php if ($page !== $max_page) { ?>
                            <a href="/board.php?page=<?php echo $next_page_button_number ?>"><img src="/img/right-pagebtn.png" alt="오른쪽" class="to_btn"></a>
                            <?php } else { ?>
                            <div class="p_btn"></div>
                        <?php } ?>
                        </div>
                    </div>

                    <!-- todo card foreach로 2행 4열 -->
                    <div class="to_main">
                        <div class="to_list">
                            <?php foreach($result2 as $item) {?>
                                <div class="to_post">
                                    <p class="to_title"><?php echo $item["name"] ?></p>
                                    <p class="to_date"><?php echo $item["deadline"] ?></p>
                                    <div class="list_box">
                                    <?php foreach($item["contents"] as $chk_lists ){ ?>
                                        <input class="list_box" type="checkbox" disabled><?php echo $chk_lists["content"]?><br>
                                    <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="to_pagination">
                            <?php if ($page !== 1) { ?>
                            <a href="/board.php?page=<?php echo $prev_page_button_number ?>"><img src="/img/left-pagebtn.png" alt="before" class="p_btn"></a>
                        <?php } else { ?>
                            <div class="p_btn"></div>
                        <?php } ?>
                        <button class="p_btn"><?php echo $page ?></button>
                        <?php if ($page !== $max_page) { ?>
                            <a href="/visit.php?page=<?php echo $next_page_button_number ?>"><img src="/img/right-pagebtn.png" alt="before" class="p_btn"></a>
                        <?php } ?>
                    </div>
                </div>

            </div>
            <div class="menu-bar">
                <div class="home"><a href="" class="home-tab">HOME</a></div>
                <div class="todo"><a href="" class="todo-tab">TODO</a></div>
                <div class="diary"><a href="" class="diary-tab">DIARY</a></div>
                <div class="visit"><a href="" class="visit-tab">VISIT</a></div>
                <div class="credit"><a href="" class="credit-tab">CREDIT</a></div>
            </div>
        </div>
    </container>
</body>

</html>

<!-- pagination 위치 조정 실패 추후 추가 예정 -->