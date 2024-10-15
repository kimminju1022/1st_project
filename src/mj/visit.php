<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php"); //config파일의 정보를 가져와 쓴다
require_once(MY_ROOT_DB_LIB); //db_lib 파일의 정보를 가져와 쓴다

$conn = null;

// try catch
try {
    $conn=my_db_conn();

    $arr_prepare = [
        "limit" => 4
        ,"offset"=> 0
    ];

    $result = get_guestbook_board($conn, $arr_prepare);

}catch(Throwable $th) {
    echo $th->getMessage();
    exit;
}


 // pagination
    $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1; // 현재 페이지 획득
    $offset = ($page - 1) * MY_VISIT_COUNT; // 오프셋 설정
    $prev_page_button_number = $page - 1 < 1 ? 1 : $page - 1; // 이전 버튼
    $next_page_button_number = $page + 1 > $max_page ? $max_page : $page + 1; // 다음버튼

?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main-page</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/visit.css">
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
                        <!-- 폼태그확인하기 -->
                        <form action="/login.php">
                        <div class="logout"><button class="logout">로그아웃</button></div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="content">
                <div class="visit-content">
                    <div class="main-title">
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>
                    
                    <!-- visit_insert -->
                    <form action="/visit_insert.php" class="" method="post">
                        <div class="visit_comment">
                            <textarea maxlength="300" cols="10" rows="3" placeholder="남길 말씀이 있다면 여기에 남겨주세요"></textarea>
                            <!-- post -->
                            <button type="submit" class="post-btn">글남기기</button>
                        </div>
                    </form>
                </div>

                <!-- visit_detail -->
                <h3>방명록 <img src="/img/visit.png" alt="guest" id="g-icon"></h3>
                <div class="visit_post">
                    <!-- foreach로 4개 까지 표기 → 미니미,방명록,일자,삭제버튼 표시 -->
                    <?php foreach ($result as $item) { ?>
                        <div class="visit_box">
                            <img src="/img/icon.png" alt="미니미" class="visit_icon">

                            <form action="/delete.php?id=1">
                                <div class="visit_box">
                                    <img src="/img/icon.png" alt="미니미" class="visit_icon">
                                    <p class="visit-comment"><?php echo $item["content"] ?></p>
                                    <p class="visit_date"><?php echo $item["updated_at"] ?></p>
                                    <a href="/delete.php" class="delete-btn"><img src="/img/delete.png" alt="delete-btn"></a>
                                </div>
                            </form>


                            <form action="/visit.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $result["id"] ?>">
                                <a href="/detail.php?id=<?php echo $result["id"] ?>&page=<?php echo $page ?>"><img src="/img/delete.png" alt="delete-btn"></a>
                            </form>


                        </div>
                    <?php } ?>

                </div>

                <!-- pagenation → 유효페이지까지 가능하고, 전후, 현재만 표시 -->
                <div class="visit_footer">
                    <?php if($page !== 1) { ?>
                        <a href="/visit.php?page=<?php echo $prev_page_button_number ?>"><img src="/img/left-pagebtn.png" alt="before" class="p_btn"></a>
                    <?php } else { ?>
                        <div class="p_btn"></div>
                    <?php } ?>
                    <button class="p_btn"><?php echo $i ?></button>
                    <?php if($page !== $max_page) { ?>
                        <a href="/visit.php?page=<?php echo $next_page_button_number ?>"><img src="/img/right-pagebtn.png" alt="before" class="p_btn"></a>
                    <?php } ?>
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
<!-- 질문사항 -->
<!-- get_guestbook_boar에서 created_at이 아닌 updated_at을 사용하지 않는 이유 -->