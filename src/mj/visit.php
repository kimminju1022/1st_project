<?php



?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main-page</title>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/visit.css">
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
                        <div class="profile"><img class="profile-img" src="./img/profile.jpg" alt="" width="250px"
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
                <div class="visit-content">
                    <div class="main-title">
                        ブl억님으l ㅁıLI홈ㅍı
                    </div>
                    <div class="visit_comment">
                        <textarea maxlength="300" cols="10" rows="3" placeholder="남길 말씀이 있다면 여기에 남겨주세요"></textarea>
                        <!-- post -->
                        <button class="post-btn">글남기기</button>
                    </div>
                    <h3>방명록</h3>
                    <div class="visit_post">
                        <!-- foreach로 4개 까지 표기 -->
                        <?php foreach ($result as $item) { ?>
                            <div class="visit_box">
                            <img src="/img/icon.png" alt="미니미" class="visit_icon">
                                <div><?php echo $item["content"] ?></div>
                                <div><?php echo $item["updated_at"] ?></div>


                <form action="/visit.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $result["id"] ?>">
                    <a href="/detail.php?id=<?php echo $result["id"] ?>&page=<?php echo $page ?>"><img src="/img/delete.png" alt="delete-btn"></a>
                </form>


                        </div>
                        <?php } ?>

                    </div>
                    <div class="visit_footer"> <!-- 유효페이지까지 가능하고, 전후, 현재만 표시 -->
                        <a href="/"><img src="/img/left-pagebtn.png" alt="before" class="p_btn"></img></a>
                        <a href="/"><button class="p_btn">p</button></a>
                        <a href="/"><img src="/img/right-pagebtn.png" alt="before" class="p_btn"></img></a>

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