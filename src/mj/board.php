<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);

$conn=null;

try{
    $conn=my_db_conn();
    $arr_prepare1 = [
        "limit" => 8
        ,"offset"=> 0
    ];
    $arr_prepare2 = [
        "limit" => 4
        ,"offset"=> 0
    ];

    $result1 = get_checklist_today($conn, $arr_prepare);
    $result2 = get_todolist_board($conn, $arr_prepare);


}catch(Throwable $th) {
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
                        <div class="profile"><img class="profile-img" src="/img/profile.jpg" alt="" width="250px"
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

                            <a href="/"><img src="/img/left-pagebtn.png" alt="왼쪽" class="to_btn"></a>
                            <!-- db lib에서 데이터 불러오기 -->
                            <!-- 최대 카드 2개, 이상일 시 pagination -->
                            <div class="to_box"></div>
                            <div class="to_box"></div>
                            <a href="/"><img src="/img/right-pagebtn.png" alt="오른쪽" class="to_btn"></a>
                        </div>
                    </div>

                    <!-- todo card foreach로 2행 4열 -->
                    <div class="to_main">
                    <?php
                    foreach ($result as $item) { ?>
                            <div class="to_list">
                                <div class="to_post">
                                    <p class="to_title"><?php echo $item["input_id"] ?></p>
                                    <p class="to_date"><?php echo $item["updated_at"] ?></p>
                                    <?php foreach ($item["contents"] as $chk_list_item) { ?>
                                        <input class="list_box" type="checkbox"><?php echo $chk_list_item["content"] ?><br>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="to_pagination">
                        <a href="/"><img src="/img/left-pagebtn.png" alt="왼쪽" class="to_btn"></a>
                        <a href="/">
                            <p>1</p>
                        </a>
                        <a href="/"><img src="/img/right-pagebtn.png" alt="오른쪽" class="to_btn"></a>
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