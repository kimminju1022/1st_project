<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once(MY_ROOT_DB_LIB);
require_once(MY_ROOT_UTILITY);

go_login();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main-page</title>
    <link rel="stylesheet" href="/css/credit.css">
    <link rel="stylesheet" href="/css/common-design.css">
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
                    <div class="todo-list-title">Comments<br> <hr width="120px"></div>
                    <div class="main-list">
                        <div class="hyun">
                            <div class="team-name">현진</div>
                            <div class="team-comment">
                                <img src="/img/hj.png" alt="" width="110px" height="230px" class="team-img">
                                <p class="personal-comment">
                                    <br>
                                    <br>
                                    이거 괜찮아요?<br>
                                    디자인 봐주실 분?<br>
                                    마음에안드는데?<br>
                                    이게아닌데? 
                                </p>
                            </div>
                        </div>
                        <div class="won">
                            <div class="team-name">원상</div>
                            <div class="team-comment">
                                <img src="/img/ws.png" alt="" width="110px" height="230px" class="team-img">
                                <p class="personal-comment">
                                    <br>
                                    <br>
                                    일단 복붙해서<br>
                                    해결은 했는데<br> 
                                    왜 되는지 <br>
                                    모르겠어
                                </p>
                            </div>
                        </div>
                        <div class="min">
                            <div class="team-name">민주</div>
                            <div class="team-comment">
                                <img src="/img/mj.png" alt="" width="110px" height="230px" class="team-img">
                                <p class="personal-comment">
                                    <br>
                                    자기들과 함께라<br>
                                    모든 날 모든 순간 <br>
                                    열심히 살았네 <br>
                                    그렇게 모든 날 <br>
                                    모든 순간 행복했어
                                </p>
                            </div>
                        </div>
                        <div class="sang">
                            <div class="team-name">상민</div>
                            <div class="team-comment">
                                <img src="/img/sm.png" alt="" width="110px" height="230px" class="team-img">
                                <p class="personal-comment">
                                    <br>
                                    <br>
                                    내가 어떻게<br> 
                                    버그를 찾았더라...?<br>
                                    우리 팀 <br>
                                    감사합니다<br>
                                </p>
                            </div>
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