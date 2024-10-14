<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);

$conn=null;

try{
    // $id = isset($_GET["id"]) ? (int)$_GET["id"] : 0 ;

    // $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1 ;

    // if($id <1) {
    //     throw new Exception("Param Error");
    // }

    $conn=my_db_conn();

    $arr_prepare = [
        "limit" => 4
        ,"offset"=> 0
    ];

    $result = get_todolist_board($conn, $arr_prepare);

    $result2 = get_guestbook_board($conn, $arr_prepare);

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
                        <div class="profile"><img class="profile-img" src="/img/profile.jpg" alt="" width="250px" height="300px"></div>
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
                    <div class="todo-list-title">Updated Todo-List <br> <hr width="200px"></div>
                    <div class="main-list">
                        <?php 
                        foreach($result as $item) { ?>
                            <div class="main-content-box">
                                <div class="main-checkbox">
                                    <p class="check-title"><?php echo $item["name"] ?></p>
                                    <p class="check-date"><?php echo $item["deadline"] ?></p>
                                    <?php foreach($item["contents"] as $chk_list_item) { ?>
                                    <input class="check-list check-first" type="checkbox"><?php echo $chk_list_item["content"] ?><br>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                   <div class="sec-content">
                        <div class="sec-content-title">Miniroom <br>
                            <div class="miniroom">
                            <img src="/img/miniroom.PNG" alt="" width="580px" height="330px"></div>
                        </div>
                        <div class="sec-content-visitor">
                            <div>Visitor</div>
                            <?php
                                foreach($result2 as $item) { ?>
                                    <div class="visit">
                                        <img class="visitor" src="/img/icon.png" alt="" height="60px" width="30px">
                                        <p class="visit-comment"><?php echo $item["content"] ?></p>
                                    </div>
                                <?php } ?>
                          
                        </div>

                    </div>
                </div>
            </div>
            <div class="menu-bar">
                <div class="home"><a href="" class="home-tab">HOME</a></div>
                <div class="todo"><a href="" class="todo-tab">TODO</a></div>
                <div class="diary"><a href="" class="diary-tab">DIARY</a></div>
                <div class="visit-btn"><a href="" class="visit-tab">VISIT</a></div>
                <div class="credit"><a href="" class="credit-tab">CREDIT</a></div>
            </div>
        </div>
    </container>  
</body>
</html>