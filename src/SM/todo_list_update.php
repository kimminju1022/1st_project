<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);
session_start();

$conn = null;

try {
    if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
        // GET처리
        // page 획득
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
        $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

        if($id < 1) {
            throw new Exception("파라미터 오류 : G");
        }

        // PDO Instance
        $conn = my_db_conn();

        // 데이터 조회
        $arr_prepare = [
            "id" => $id
        ];

        $result = get_todolist_detail($conn, $arr_prepare);

    } else {
    // POST 처리
        // parameter 획득(id, page, 제목, deadline)
        // img는 밑에서 동적 처리를 하기 때문에 여기서 획득하지 않는다.
        // id 획득
        $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

        // page 획득
        $page = isset($_POST["page"]) ? (int)$_POST["page"] : 1;

        // name, 제목 획득
        $name = isset($_POST["title_bar"]) ? $_POST["title_bar"] : "";
        
        // deadline 획득
        $deadline = isset($_POST["deadline"]) ? $_POST["deadline"] : date("Ymd");

        $checklists = [];

        if($id < 1 || $name ==="") {
            throw new Exception("파라미터 오류 : P");
        }
    
        // PDO Instance
        $conn = my_db_conn();
        
        // beginTransaction / Transaction Start
        $conn->beginTransaction();

        $arr_prepare = [
            "id" => $id
            ,"name" => $name
            ,"deadline" => $deadline
        ];
       
        update_todolist($conn, $arr_prepare, $checklists);

        // commit
        $conn->commit();

        // detail 페이지로 이동
        header("Location: /detail.php?id=".$id."&page=".$page);
        exit;
    }
    } catch(Throwable $th) {
    if(!is_null($conn) && $conn -> inTransaction()) {
        $conn -> rollBack();
    }


    echo $th ->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/common-design.css">
    <link rel="stylesheet" href="/css/todo_list_detail.css">
    <title>Todo list 수정페이지</title>
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
                        <div class="profile"><img class="profile-img" src="./img/profile.jpg" alt="" width="250px" height="300px"></div>
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
                        <form method="post" action="#">
                            <input type="hidden" name="posttype" value="logout">
                            <div class="logout"><button type="submit" class="logout">로그아웃</button></div>
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
                        Todo-List 수정페이지
                        <br>
                        <hr width="220px">
                    </div>

                    <form action="/todo_list_update.html" method="post" class="">
                        <input type="hidden" name="id" value="<?php echo $result[0]["todolist_id"] ?>">
                        <input type="hidden" name="page" value="<?php echo $page ?>">
                        <div>
                            <div class="calendar">
                                <div class="sub_title">제목</div>
                                <input type="text" id="title_bar" name="title_bar" class="input_area sub_title_area" value="<?php echo $result[0]["name"] ?>">
                                <div class="sub_date">수행일자</div>
                                <div class="input_area sub_date_area"></div>
                                <input type="date" name="deadline" id="deadline" class="deadline" value="<?php echo $result[0]["deadline"] ?>">
                            </div>
                            <div class="nine_bar">
                                <hr width="900px">
                            </div>
                        </div>
                        <div class="sub_content">
                            <div class="chk_area">
                                <div class="chk_list">
                                    <?php foreach($result as $item) { ?>
                                        <div>
                                            <input type="checkbox" class="check_btn" value="<?php echo $item["checklist_id"] ?>" <?php if($item["ischecked"] === true) { echo "checked" ;} ?>>
                                            <input type="text" name="text" maxlength="40" class="chk_text" value="<?php echo $item["content"] ?>">
                                            <hr class="bar">
                                        </div>
                                    <?php } ?>                   
                                </div>
                            </div>
                        </div>
                        <div class="btn-insert">
                            <input type="hidden" name="posttype" value="logout">
                            <a href="/photo.php?id=<?php echo $result[0]["todolist_id"] ?>&page=<?php echo $page ?>"><button type="button" class="btn">수정 취소</button></a>
                            <button type="submit" class="btn">수정 완료</button>
                        </div>
                    </form>
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