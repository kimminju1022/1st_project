<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);
session_start();

$conn = null;

try {
    if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
        // GET 처리
        // 파라미터 획득(id, page)
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $page_todo = isset($_GET["page_todo"]) ? (int)$_GET["page_todo"] : 1;

        $page_checklist = isset($_GET["page_checklist"]) ? (int)$_GET["page_checklist"] : 1;

        if($id < 1) {
            throw new Exception("파라미터 오류 : G");
        }

        // PDO Instance
        $conn = my_db_conn();

        // 여기서 transaction을 하지 않는 이유는
        // 단순 조회만 하기 때문이다.

        // 데이터 조회 - 특정 id로 조회할 예정
        $arr_prepare = [
            "id" => $id
        ];

        $result = get_todolist_detail($conn, $arr_prepare);

    } else {
        // POST 처리
        // parameter 획득(id, page, title, content)
        
        $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
        
        if($id < 1 ) {
            throw new Exception("파라미터 오류 : P");
        }

        // PDO Instance
        $conn = my_db_conn();

        // beginTransaction / Transaction Start 
        // 데이터에 변화가 생기니까 transactio을 하는것임
        $conn->beginTransaction();

        // 데이터 조회
        $arr_prepare = [
            "id" => $id
        ];

        // 삭제 처리
        delete_todolist($conn, $arr_prepare);

        // commit
        $conn->commit();

        // 리스트 페이지로 이동
        header("Location: /board.php?page_checklist_today=1&page_todo=1");
        exit;
    }
} catch(Throwable $th) {
    if(!is_null($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    } // 오타 없이 코드를 잘 만들었다면 여기로 들어올 확률은 1% 정도이다.

    header("Location: /error.php");
    // echo $th ->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common-design.css">
    <link rel="stylesheet" href="./css/todo-list_delete.css">
    <title>Todo list 삭제페이지</title>
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
                    <div class="update_title">
                        Todo-List 삭제페이지
                        <br>
                        <hr width="220px">
                    </div>
                    <div class="title_bar">
                        <h1>삭제하시겠습니까?</h1>
                    </div>
                    <div class="nine_bar">
                        <hr width="900px">
                    </div>
                    <div class="calendar">
                        <div class="sub_title">제목</div>
                        <div class="echo">
                        <p class="input_area"><?php echo $result[0]["name"] ?></p>
                        </div>
                    </div>
                    <div class="white">
                        <div class="white_board">
                            <p class="write">더 이상 다른 이에게 노출되지 않습니다.</p>
                            <p class="write sub_p">정말 기억을 지우시겠습니까?</p>
                        </div>
                    </div>
                    <form action="/todo_list_delete.php" method="post" class="">
                        <div class="btn-insert">
                            <input type="hidden" name="id" value="<?php echo $result[0]["todolist_id"] ?>">
                            <a href="/detail.php?<?php echo "id=".$result[0]["todolist_id"]."&page_todo=".$page_todo."&page_checklist=".$page_checklist; ?>"><button type="button" class="btn">취소</button></a>
                            <button type="submit" class="btn">삭제하기</button>
                        </div>
                    </form>
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