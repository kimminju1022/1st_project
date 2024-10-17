<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
    require_once(MY_ROOT_DB_LIB);
    require_once(MY_ROOT_UTILITY);
    
    session_start();

    go_login();
    check_manager();

    $conn = null;
    try{
        if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST"){
            $conn = my_db_conn();

            $name = isset($_POST["sub_title"]) ? $_POST["sub_title"] : throw new Exception("제목을 입력하세요");
            $deadline = !empty($_POST["deadline"])  ? $_POST["deadline"] : date("Y-m-d");
            
            $checklists = [];

            $is_all_empty = true;

            for($i = 0; $i<20; $i++){
                $content = isset($_POST[(string)$i]) ? $_POST[(string)$i] : "";
                $checklists[$i] = $content;

                if($is_all_empty && !empty(trim($content))){
                    $is_all_empty = false;
                }
            }

            if(empty(trim($name))){
                throw new Exception("제목을 채워주세요");
            }

            if($is_all_empty){
                throw new Exception("todolist를 하나 이상 채워주세요");
            }

            

            $arr_prepare = [
                "name" => $name
                ,"deadline" => $deadline
            ];


            $conn -> beginTransaction();


            $id = insert_todolist($conn, $arr_prepare, $checklists);

            $conn -> commit();

            $page_todo = isset($_POST["page_todo"]) ? (int)$_POST["page_todo"] : 1;

            $page_checklist = isset($_POST["page_checklist"]) ? (int)$_POST["page_checklist"] : 1;

            header("Location: /todo_list_detail.php?id=".$id."&page_todo=".$page_todo."&page_checklist=".$page_checklist);
        }

        else{
            $page_todo = isset($_GET["page_todo"]) ? (int)$_GET["page_todo"] : 1;
            $page_checklist = isset($_GET["page_checklist"]) ? (int)$_GET["page_checklist"] : 1;
        }
    }
    catch(Throwable $th){
        if(!is_null($conn) && $conn -> inTransaction()){
            $conn -> rollBack();
        }

        header("Loaction: /error.php");

        echo $th -> getMessage();
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common-design.css">
    <link rel="stylesheet" href="./css/todo-list_insert.css">
    <title>Todo list 작성페이지</title>
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
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>
                    <div class="insert_title">
                        Todo-List 작성페이지
                        <br>
                        <hr width="220px">
                    </div>
                    
                    <form action="/todo_list_insert.php" method="post" class="">
                        <input type="hidden" name="page_todo" value="<?php echo $page_todo ?>">
                        <input type="hidden" name="page_checklist" value="<?php echo $page_checklist ?>">
                            <div class="calendar">
                                <div class="sub_title">제목</div>
                                <input type="text" name="sub_title" class="sub_title_area" maxlength="20">
                                <div class="sub_date">수행일자</div>
                                <div class="deadline-area"><input type="date" name="deadline" id="deadline" class="deadline"></div>
                            </div>
                            <div class="nine_bar">
                                <hr width="900px">
                            </div>
                        <div class="sub_content">
                            <div class="chk_area">
                                <div class="chk_list">
                                    <?php for($i = 0; $i<20; $i++) { ?>
                                        <div class="chk_content">
                                            <input type="checkbox" class="check_btn" disabled>
                                            <input type="text" name="<?php echo (string)($i) ?>" maxlength="40" class="chk_text">
                                            <hr class="bar">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="btn-insert">
                            <a href="/photo.php"><button type="button" class="btn">뒤로가기</button></a>
                            <button type="submit" class="btn">작성 완료</button>
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