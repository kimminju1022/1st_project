<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);
require_once(MY_ROOT_UTILITY);

session_start();

go_login();

$conn = null;

try {

    if(strtoupper($_SERVER["REQUEST_METHOD"] === "POST")){
        $posttype = isset($_POST["posttype"]) ? $_POST["posttype"] : null;

        if($posttype === "logout"){
            logout();
            exit;
        }
    } else {
    // id 획득 - 유저가 get method로 요청
    $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

    // page 획득
    $page_todo = isset($_GET["page_todo"]) ? (int)$_GET["page_todo"] : 1;

    $page_checklist = isset($_GET["page_checklist"]) ? (int)$_GET["page_checklist"] : 1;


   
    if($id < 1) {
        throw new Exception("파라미터 오류");
    }

    // PDO Instance
    $conn = my_db_conn();

    $arr_prepare = [
        "id" => $id
    ];

    $result = get_todolist_detail($conn, $arr_prepare);
    }

} catch(Throwable $th) {
    header("Location: /error.php");
    // echo $th -> getMessage();
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
    <title>Todo list 상세페이지</title>
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
                        <form action="#" method="post">
                            <input type="hidden" name="posttype" value="logout">
                            <div class="logout"><button class="logout">로그아웃</button></div>
                        </form>
                    </div>
                </div>
            </div>
        
            <div class="content">
                <div class="main-content">
                    <div class="main-title">
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>
                    <div class="insert_title">
                        Todo-List 상세페이지
                        <br>
                        <hr width="220px">
                    </div>
                    
                    <form action="/todo_list_chk_save.php" method="post" class="">
                        <input type="hidden" name="id" value="<?php echo $result[0]["todolist_id"] ?>">
                        <input type="hidden" name="page_checklist" value="<?php echo $page_checklist ?>">
                        <input type="hidden" name="page_todo" value="<?php echo $page_todo ?>">
                        <div>
                            <div class="calendar">
                                <div class="sub_title">제목</div>
                                <input type="text" name="sub_title" class="input_area sub_title_area" value="<?php echo $result[0]["name"] ?>" disabled>
                                <div class="sub_date">수행일자</div>
                                <div class="input_area sub_date_area"></div>
                                <input type="date" name="deadline" id="deadline" class="deadline" value="<?php echo $result[0]["deadline"] ?>" disabled>
                            </div>
                            <div class="nine_bar">
                                <hr width="900px">
                            </div>
                        </div>
                        <div class="sub_content">
                            <div class="chk_area">
                                <div class="chk_list">
                                    <?php $i = 0; for(;$i<count($result); $i++) {?>
                                        <div class="chk_content">
                                            <input type="checkbox" class="check_btn" name="chk[]" value="<?php echo $result[$i]["checklist_id"] ?>" <?php if($result[$i]["ischecked"] === 1) { echo "checked" ;} ?>>
                                            <input type="text" name="text" maxlength="40" class="chk_text" value="<?php echo $result[$i]["content"] ?>" disabled>
                                            <hr class="bar">
                                        </div>
                                    <?php } for(;$i<20; $i++) { ?>
                                        <div class="chk_content">
                                            <input type="checkbox" class="check_btn" name="chk[]" value="<?php echo $i ?>"?>
                                            <input type="text" name="text" maxlength="40" class="chk_text" value="" disabled>
                                            <hr class="bar">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="btn-insert">
                            <a href="/board.php?<?php echo "page_checklist_today=".$page_checklist."&page_todo=".$page_todo ?>"><button type="button" class="btn">뒤로가기</button></a>
                            <a href="/todo_list_delete.php?<?php echo "id=".$id."&page_checklist_today=".$page_checklist."&page_todo=".$page_todo ?>"><button type="button" class="btn">삭제하기</button></a>                            
                            <a href="/todo_list_update.php?<?php echo "id=".$id."&page_checklist_today=".$page_checklist."&page_todo=".$page_todo ?>"><button type="button" class="btn">수정하기</button></a>
                            <button type="submit" class="btn">저장하기</button>
                            <input type="hidden" name="posttype" value="insert">
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