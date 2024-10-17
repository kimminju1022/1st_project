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
    if(strtoupper($_SERVER["REQUEST_METHOD"] === "POST")){
        $posttype = isset($_POST["posttype"]) ? $_POST["posttype"] : null;

        if($posttype === "logout"){
            logout();
            exit;
        }
    } else {
    
    // PDO Instance
    $conn = my_db_conn();

    // http 주소 구조 설명
    // localhost/board.php?page_checklist_today=1&page_todo=1
    // checklist_today : 오늘 해야 할 일 관련 페이지네이션
    // todo : todolist 관련 페이지네이션

    // get_checklist_today---------
    // max page 획득 처리
    $max_board_cnt_check = cnt_checklist_today($conn); // 게시글 총 수 획득
    $max_page_check = (int)(ceil($max_board_cnt_check / MY_BOARD_CARD_COUNT)); // max page 획득

    // pagination 설정
    $page_checklist_today = isset($_GET["page_checklist_today"]) ? (int)$_GET["page_checklist_today"] : 1; // 현재 페이지 획득
    $offset_checklist_today = ($page_checklist_today - 1) * MY_BOARD_CARD_COUNT; // 오프셋 설정
    $prev_page_button_number_check = $page_checklist_today - 1 < 1 ? 1 : $page_checklist_today - 1; // 이전 버튼
    $next_page_button_number_check = $page_checklist_today + 1 > $max_page_check ? $max_page_check : $page_checklist_today + 1; // 다음버튼

    // pagination select 처리
    $arr_prepare_check = [
        "limit"  => MY_BOARD_CARD_COUNT,
        "offset"   => $offset_checklist_today
    ];
    
    // cnt_todo---------
    // max page 획득 처리
    $max_board_cnt_todo = cnt_checklist_todo($conn); // 게시글 총 수 획득
    $max_page_todo = (int)(ceil($max_board_cnt_todo / MY_BOARD_CARD_COUNT)); // max page 획득

    // pagination 설정
    $page_todo = isset($_GET["page_todo"]) ? (int)$_GET["page_todo"] : 1; // 현재 페이지 획득
    $offset_todo = ($page_todo - 1) * MY_BOARD_CARD_COUNT; // 오프셋 설정
    $prev_page_button_number_todo = $page_todo - 1 < 1 ? 1 : $page_todo - 1; // 이전 버튼
    $next_page_button_number_todo = $page_todo + 1 > $max_page_todo ? $max_page_todo : $page_todo + 1; // 다음버튼

    // pagination select 처리
    $arr_prepare_todo = [
        "limit"  => MY_BOARD_CARD_COUNT,
        "offset"   => $offset_todo
    ];

    $result1 = get_checklist_today($conn, $arr_prepare_check);
    $result2 = get_todolist_board($conn, $arr_prepare_todo);
    }

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
    <link rel="stylesheet" href="/css/board.css">
</head>
<body>
    <container>
        <div class="main-background">
            <div class="side-bar">
                <div class="back-side-bar">
                    <div class="logo"><img src="/img/logo.png" alt="" height="120px" width="180px"></div>
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
                        ブl억님으l ㅁıLI홈ㅍı1
                    </div>
                    <div class="to_body">
                        <!-- todo-list-title style위치 일치할 것 -->
                        <div class="insert-btn">
                            <div class="todo-head"> 오늘까지 할 일</div>
                            <a href="/todo_list_insert.php?<?php echo "page_todo=".$page_todo."&page_checklist=".$page_checklist_today ?>">
                                <button type="submit" class="post-btn">글남기기</button>
                            </a>
                        </div>
                        <div class="todo-deadline">
                            <div class="arrow-to-left"><a href="/board.php?<?php echo "page_checklist_today=".$prev_page_button_number_check."&page_todo=".$page_todo ?>"><img src="/img/left-pagebtn.png" alt="왼쪽" class="to_btn"></a></div>
                            <!-- db lib에서 데이터 불러오기 -->
                            <!-- 최대 카드 2개, 이상일 시 pagination -->
                            <div class="to_box">
                                <?php foreach ($result1 as $item) { ?>
                                    <p class="for-today"><?php echo $item["content"] ?></p>
                                <?php } ?>
                            </div>
                            <div class="arrow-to-right"><?php if ($page_checklist_today !== $max_page) { ?>
                                <a href="/board.php?<?php echo "page_checklist_today=".$next_page_button_number_check."&page_todo=".$page_todo ?>"><img src="/img/right-pagebtn.png" alt="오른쪽" class="to_btn"></a>
                                <?php }?>
                            </div>  
                        </div>
                    </div>
                    <!-- todo card foreach로 2행 4열 -->
                    <div class="to_main">
                        <div class="to_list">
                            <?php foreach($result2 as $item) {?>                                
                                <div class="to_post">
                                    <a href="/todo_list_detail.php?<?php echo "id=".$item["id"]."&page_todo=".$page_todo."&page_checklist=".$page_checklist_today?>">
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
                            <?php if ($page_todo !== 1) { ?>
                            <a href="/board.php?<?php echo "page_checklist_today=".$page_checklist_today."&page_todo=".$prev_page_button_number_todo ?>"><img src="/img/left-pagebtn.png" alt="before" class="p_btn" width="50px" height="50px"></a>
                        <?php } else { ?>
                            <div class="p_btn"></div>
                        <?php } ?>
                        <button class="p_btn"><?php echo $page_todo ?></button>
                        <?php if ($page_todo !== $max_page) { ?>
                            <a href="/board.php?<?php echo "page_checklist_today=".$page_checklist_today."&page_todo=".$next_page_button_number_todo ?>"><img src="/img/right-pagebtn.png" alt="before" class="p_btn" width="50px" height="50px"></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
            <div class="menu-bar">
                <div class="home"><a href="/index.php" class="home-tab">HOME</a></div>
                <div class="todo"><a href="/board.php?page_checklist_today=1&page_todo=1" class="todo-tab">TODO</a></div>
                <div class="diary"><a href="#" class="diary-tab">DIARY</a></div>
                <div class="visit-btn"><a href="/visit.php" class="visit-tab">VISIT</a></div>
                <div class="credit"><a href="#" class="credit-tab">CREDIT</a></div>
            </div>
        </div>
    </container>
</body>

</html>

<!-- pagination 위치 조정 실패 추후 추가 예정 -->