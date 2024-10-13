<?php

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
    <form action="/todo_list_insert.html" class="">
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
                        <div class="logout"><button class="logout">로그아웃</button></div>
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
                        <div>
                            <div class="calendar">
                                <div class="sub_title">제목</div>
                                <input type="text" class="input_area sub_title_area">
                                <div class="sub_date">수행일자</div>
                                <div class="input_area sub_date_area"></div>
                                <input type="date" name="deadline" id="deadline" class="deadline">
                            </div>
                            <div class="nine_bar">
                                <hr width="900px">
                            </div>
                        </div>
                        <div class="sub_content">
                            <div class="chk_area">
                                <div class="chk_list">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                </div>
                                <div class="chk_list">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                    <input type="text" maxlength="40" class="chk_text">
                                </div>
                            </div>
                        </div>
                        <div class="btn-insert">
                            <a href="/photo.php"><button type="button" class="btn">뒤로가기</button></a>
                            <a href="/photo.php"><button type="submit" class="btn">작성 완료</button></a>
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
        </form>
    </container>  
</body>
</html>