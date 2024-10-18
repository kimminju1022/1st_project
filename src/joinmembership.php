<?php
  require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
  require_once(MY_ROOT_DB_LIB);
  require_once(MY_ROOT_UTILITY);

  $conn = null;
  try{
    if(strtoupper($_SERVER["REQUEST_METHOD"])=== "POST" ){
      $conn = my_db_conn();
      $user_name = isset($_POST["id-input"]) ? $_POST["id-input"] : throw new Exception("id를 입력하지 않았습니다.");
      $user_password = isset($_POST["pw-input"]) ? $_POST["pw-input"] : throw new Exception("pw를 입력하지 않았습니다.");

      $arr_prepare = [
        "user_name" => $user_name
        ,"user_password" => $user_password
      ];

      $conn -> beginTransaction();

      insert_membership($conn, $arr_prepare);

      $conn -> commit();

      header("Location: /completejoinmembership.php");
    }
  } catch(Throwable $th) {
    if(!is_null($conn) && $conn -> inTransaction()){
      $conn -> rollBack();
    }
    
    header("Location: /error_membership.php");
    echo $th-> getMessage();
    exit;
  }
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join-Membership</title>
    <link rel="stylesheet" href="./css/joinMembership.css">
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
                        회원ㄱr입
                    </div>
                    <div class="comment">
                        우리의 가족이 되어보세요!<br>
                        소중한 순간을 함께 나누고,<br>
                        지금 회원가입으로 시작하세요!<br>
                    </div>
                    <form action="joinmembership.php" method="post">
                      <div class="login-back">
                        <div class="login">
                            <div class="id">
                                <p class="login-name">NAME</p>  
                                <img class="visitor1" src="./img/icon.png" alt="" height="60px" width="30px">
                            </div>
                            <input type="text" name="id-input" class="id-input" maxlength="15" required>
                            <div class="pw">
                                <p class="login-name">PW</p>
                                <img class="visitor2" src="./img/icon.png" alt="" height="60px" width="30px">
                            </div>
                            <input type="password" name="pw-input" class="pw-input"  required>
                        </div>
                        <p class="login-mention"> 입력하신 이름이 로그인에 사용되는 아이디입니다</p>
                        <button class="for-login"><a href="/login.php"> 뒤로가기</a> </button>
                        <button type="submit" class="for-join"> 회원가입</button>
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