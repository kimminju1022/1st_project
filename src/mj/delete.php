<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php"); //config파일의 정보를 가져와 쓴다
require_once(MY_PATH_DB_LIB); //db_lib 파일의 정보를 가져와 쓴다

$conn = null;

// try catch
try {
    if (strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
        // get처리

        // id획득
        $id = isset($_GET["id"]) ?  (int)$_GET["id"] : 0;

        // page 획득
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

        if ($id < 1) {
            throw new Exception("파라미터 이상");
        }

        // PDO instance
        $conn = my_db_conn();

        // 데이터 조회!!
        $arr_prepare = [
            "id" => $id
        ];

        $result = my_board_select_id($conn, $arr_prepare);
    } else {
        // post처리

        // parm setting ↓        
        // id획득
        $id = isset($_POST["id"]) ?  (int)$_POST["id"] : 0;
        if ($id < 1) {
            throw new Exception("파라미터 오류");
        }

        // pdo instance
        $conn = my_db_conn();

        //transaction start
        $conn->beginTransaction();

        $arr_prepare = [
            "id" => $id
        ];
        // 삭제처리
        my_board_delete_id($conn, $arr_prepare);

        // commit
        $conn->commit();

        // 리스트페이지 이동
        header("Location: /");
        exit;
    }
} catch (Throwable $th) {
    if (!is_null($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    require_once(MY_PATH_ERROR);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/common-design.css">
    <link rel="stylesheet" href="/css/todo-list_delete.css">
    <link rel="stylesheet" href="/css/delete.css">
    <title>Todo list 삭제페이지</title>
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
                    <div class="d_container">
                        <div class="title_bar">
                            <h1>삭제하시겠습니까?</h1>
                        </div>

                        <div class="white_board">
                            <p class="write">더 이상 다른 이에게 노출되지 않습니다.</p>
                            <p class="write sub_p">정말 기억을 지우시겠습니까?</p>
                        </div>
                        <form action="/todo_list_delete.html" class="">
                            <!-- php적용 -->
                            <div class="btn-insert">
                                <a href="/photo.php"><button type="button" class="btn">취소</button></a>
                                <button type="submit" class="btn">삭제하기</button>
                            </div>
                        </form>
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