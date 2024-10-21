<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/error.css">
    <title>로그인 에러페이지</title>
</head>
<body>
    <container>
        <div class="main-background">
            <div class="side-bar">
                <div class="back-side-bar">
                    <div class="logo"><img src="/img/logo.png" alt="" height="120px" width="150px"></div>
                    <div class="main-side-bar">
                        <div class="emotion">
                            <span class="emotion_comment">TODAY IS...</span><img src="/img/error.png" class="error-img" alt="" width="35px" height="35px">
                        </div>
                        <div class="profile"><img class="profile-img" src="./img/profile.jpg" alt="" width="250px" height="250px"></div>
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
                        <!-- <div class="logout"><button class="logout">로그아웃</button></div> -->
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="main-content">
                    <div class="main-title">
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>
                    <div class="error_title">
                        로그인 에러페이지
                        <br>
                        <hr width="180px">
                    </div>
                    <div class="error">
                        <p>아이디 또는 비밀번호가 일치하지 않습니다.</p>
                        
                        <div>
                            <a href="/"><button type="button" class="btn btn-bottom">메인페이지로 이동하기</button></a>
                        </div>
                    </div>
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