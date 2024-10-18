<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);
require_once(MY_ROOT_UTILITY);

$conn = null;
try{
  if(strtoupper($_SERVER["REQUEST_METHOD"]) === "POST"){

    $conn = my_db_conn();
    $user_name = isset($_POST["id-input"]) ? $_POST["id-input"] : "";
    $user_password = isset($_POST["pw-input"]) ? $_POST["pw-input"] : "";
    
    $arr_prepare = [
      "user_name" => $user_name
      ,"user_password" => $user_password
    ];

    $ismanager = [];
    if(check_account($conn, $arr_prepare, $ismanager)){
      login($ismanager["id"], $ismanager["ismanager"]);
      header("Location: /index.php");
    }
    else{
        throw new Exception("아이디 또는 비밀번호가 맞지 않습니다.");
    }
  }
} catch(Throwable $th) {

  header("Location: /error_login.php");
  echo $th -> getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join-Membership</title>
    <link rel="stylesheet" href="./css/login.css">
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
                        <!-- <form action="/index.php" method="post">
                            <button type="submit" class="logout">로그아웃</button>
                            <input type="hidden" name="posttype" value="logout">
                        </form> -->
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="main-content">
                    <div class="main-title">
                        Login
                    </div>
                    <div class="comment">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. <br>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit.<br>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit.<br>
                    </div>
                    <form action="/login.php" method="post">
                        <div class="login-back">
                            <div class="login">
                                <div class="id">
                                   <p class="login-name">ID</p>  
                                    <img class="visitor1" src="./img/icon.png" alt="" height="60px" width="30px">
                                </div>
                                <input type="text" name="id-input" class="id-input" maxlength="15" required>
                                <div class="pw">
                                    <p class="login-name">PW</p>
                                    <img class="visitor2" src="./img/icon.png" alt="" height="60px" width="30px">
                                </div>
                                <input type="password" name="pw-input" class="pw-input"  required>
                            </div>
                            <p class="login-mention">회원가입시 입력한 이름이 아이디입니다</p>
                            <button class="for-join"><a href="/joinmembership.php">회원가입</a></button>
                            <button class="for-login" type="submit">로그인</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="menu-bar">
                <div class="home"><a href="/index.php" class="home-tab">HOME</a></div>
                <div class="todo"><a href="/board.php?page_checklist_today=1&page=1" class="todo-tab">TODO</a></div>
                <div class="history"><a href="/history.php?page=1" class="history-tab">HISTORY</a></div>
                <div class="visit-btn"><a href="/visit.php" class="visit-tab">VISIT</a></div>
                <div class="credit"><a href="#" class="credit-tab">CREDIT</a></div>
            </div>
        </div>
    </container>  
</body>
</html>