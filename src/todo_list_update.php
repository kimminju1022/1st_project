<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);
require_once(MY_ROOT_UTILITY);


go_login();
check_manager();

$conn = null;

try {
    if(strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
        // GET처리
        // page 획득
        $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

        $page_todo = isset($_GET["page_todo"]) ? (int)$_GET["page_todo"] : 1;

        $page_checklist = isset($_GET["page_checklist"]) ? (int)$_GET["page_checklist"] : 1;

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

        if(strtotime($result[0]["deadline"]) < strtotime(date('Ymd'))){
            throw new Exception("deadline이 지난 todo_list는 수정할 수 없습니다.");
        }

    } 
    else {

        $posttype = isset($_POST["posttype"]) ? $_POST["posttype"] : "";

        // POST 처리
            // parameter 획득(id, page, 제목, deadline)
            // img는 밑에서 동적 처리를 하기 때문에 여기서 획득하지 않는다.
            // id 획득
            $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

            // page 획득
            $page_todo = isset($_POST["page_todo"]) ? (int)$_POST["page_todo"] : 1;

            $page_checklist = isset($_POST["page_checklist"]) ? (int)$_POST["page_checklist"] : 1;

            // name, 제목 획득
            $name = isset($_POST["title_bar"]) ? $_POST["title_bar"] : null;
            
            // deadline 획득
            $deadline = isset($_POST["deadline"]) ? $_POST["deadline"] : date("Ymd");

            $checklists = [];

            $is_all_empty = true;
            
            for($i = 0; $i<20; $i++){
                $content = isset($_POST[(string)$i]) ? $_POST[(string)$i] : "";
                $checklists[$i] = $content;

                if($is_all_empty && !empty($content)){
                    $is_all_empty = false;
                }
            }

            if($is_all_empty){
                throw new Exception("todolist를 하나 이상 채워주세요");
            }

            if(empty($name)){
                throw new Exception("제목을 채워주세요");
            }

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
            header("Location: /todo_list_detail.php?id=".$id."&page_todo=".$page_todo."&page_checklist=".$page_checklist);
            exit;
        }
    } catch(Throwable $th) {
    if(!is_null($conn) && $conn -> inTransaction()) {
        $conn -> rollBack();
    }

    header("Location: /error.php");
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
    <link rel="stylesheet" href="/css/todo_list_update.css">
    <title>Todo list 수정페이지</title>
</head>
<body>
    <container>
        <div class="main-background">
            <div class="side-bar">
                <div class="back-side-bar">
                    <div class="logo"><img src="/img/logo.png" alt="" height="120px" width="150px"></div>
                    <div class="main-side-bar">
                        <div class="emotion">
                            <span class="emotion_comment">TODAY IS...</span><img src="/img/heart.png" alt="" width="40px" height="40px">
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
                        Todo-List 수정페이지
                        <br>
                        <hr width="220px">
                    </div>

                    <form action="/todo_list_update.php" method="post" class="">
                        <input type="hidden" name="id" value="<?php echo $result[0]["todolist_id"] ?>">
                        <input type="hidden" name="page_todo" value="<?php echo $page_todo ?>">
                        <input type="hidden" name="page_checklist" value="<?php echo $page_checklist ?>">
                        
                            <div class="calendar">
                                <div class="sub_title">제목</div>
                                <input type="text" id="title_bar" name="title_bar" maxlength="20" class="input_area sub_title_area" value="<?php echo $result[0]["name"] ?>">
                                <div class="sub_date">수행일자</div>
                                <input type="date" name="deadline" id="deadline" class="deadline" value="<?php echo $result[0]["deadline"] ?>">
                            </div>
                            <div class="nine_bar">
                                <hr width="900px">
                            </div>
                        
                        <div class="sub_content">
                            <div class="chk_area">
                                <div class="chk_list">
                                    <?php $i = 0; for(;$i<count($result); $i++) {?>
                                        <div class="chk_content">
                                            <input type="checkbox" class="check_btn" name="chk[]" value="<?php echo $result[$i]["checklist_id"] ?>" disabled>
                                            <input type="text" name="<?php echo (string)$i ?>" maxlength="40" class="chk_text" value="<?php echo $result[$i]["content"] ?>">
                                            <hr class="bar">
                                        </div>
                                    <?php } for(;$i<20; $i++) { ?>
                                        <div class="chk_content">
                                            <input type="checkbox" class="check_btn" name="chk[]" value="<?php echo $i ?>" disabled>
                                            <input type="text" name="<?php echo (string)$i ?>" maxlength="40" class="chk_text" value="" >
                                            <hr class="bar">
                                        </div>
                                    <?php } ?>         
                                </div>
                            </div>
                        </div>
                        <div class="btn-insert">
                            <input type="hidden" name="posttype" value="logout">                            
                            <a href="/todo_list_detail.php?<?php echo "id=".$result[0]["todolist_id"]."&page_todo=".$page_todo."&page_checklist=".$page_checklist; ?>"><button type="button" class="btn">수정 취소</button></a>
                            <button type="submit" class="btn">수정 완료</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="menu-bar">
                <div class="home"><a href="/index.php" class="home-tab">HOME</a></div>
                <div class="todo"><a href="/board.php?page_checklist_today=1&page=1" class="todo-tab">TODO</a></div>
                <div class="history"><a href="/history.php?page=1" class="history-tab">HISTORY</a></div>
                <div class="visit-btn"><a href="/visit.php" class="visit-tab">VISIT</a></div>
                <div class="credit"><a href="/credit.php" class="credit-tab">CREDIT</a></div>
            </div>   
        </div>
    </container>  
</body>
</html>