<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");

function my_db_conn(){
  //  PDO 옵션 설정
  $my_otp = [
    // Prepared Statement. 쿼리문을 준비할 때, PHP와 DB 어디에서 에뮬레이션을 할 것인가
    PDO::ATTR_EMULATE_PREPARES    => false, // DB에서 하겠다는 뜻

    // PDO에서 예외를 처리하는 방법 (에러 등을 어떻게 처리할 것인가)
    PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,

    // DB 정보를 가져와서 fetch하는 방법
    PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC
  ];

  // DB 접속
  return new PDO(MY_DSN, MY_USER, MY_PASSWORD, $my_otp);
}

/**
 * 매니저 확인용 함수.
 * 매니면 true, 아니면 false 반환
 * @param $conn : PDO 클래스
 * @param $id : id
 */
function is_manager_account(PDO $conn, int $id ){

  $sql = 
      " SELECT          "
      ." users.id       "
      ." FROM           "
      ." users          "
      ." WHERE          " 
      ." ismanager = 1; "
      ;

  $stmt = $conn->query($sql);

  $result = $stmt->fetchAll();

  foreach($result as $value){
    if($value["id"] === $id){
      return true;
    }
  }

  return false;
}

/**
 * pagination 조회할 때 쓰는 함수
 */
function select_pagination_visit(PDO $conn, array $arr_param){
  $sql =
        " SELECT              "
        ."               *    "
        ." FROM               "
        ."       guest_books  "
        ." WHERE              "
        ." deleted_at IS NULL "
        ." ORDER BY           "
        ."           id DESC  "
        ." , created_at DESC  "
        ." LIMIT :list_cnt    "
        ." OFFSET :offset     "
        ;

        $stmt = $conn->prepare($sql);
        $result_flg = $stmt->execute($arr_param);
        
        if($result_flg === false){
          throw new Exception("쿼리실행실패");
        }
        
       return $stmt->fetchAll();
      }

function select_pagination_board(PDO $conn, array $arr_param){
  $sql =
        " SELECT             "
        ."               *   "
        ." FROM              "
        ."         todolists "
        ." ORDER BY          "
        ."           id DESC "
        ." , created_at DESC "
        ."           id DESC "
        ." LIMIT :list_cnt   "
        ." OFFSET :offset    "
        ;

        $stmt = $conn->prepare($sql);
        $result_flg = $stmt->execute($arr_param);
        
        if($result_flg === false){
          throw new Exception("쿼리실행실패");
        }
        
        return $stmt->fetch();
      }

/**
 * todo list 카드에 출력할 때 쓰는 함수.
 * 다음 정보가 출력됨.
 * todolist id
 * , todolist 제목
 * , todolist 마감기한
 * , todolist에 있는 체크리스트
 * 
 * @param $arr_param = :limit, :offset
 */
function get_todolist_board(PDO $conn, array $arr_param){
  $sql = 
  " SELECT id, name, deadline  "
  ." FROM todolists            "
  ." WHERE deleted_at IS NULL  "
  ." AND deadline >= CURDATE() "
  ." ORDER BY deadline ASC     "
  ." LIMIT :limit              "
  ." OFFSET :offset            "
  ; 

  $stmt = $conn -> prepare($sql);
  $stmt -> execute($arr_param);

  $result_todo = $stmt -> fetchAll();

  foreach($result_todo as $key => $item) {
    $arr_prepare = [
      "list_id" => $item["id"]
    ];

    $result_chk_list = get_checklist_forboard($conn, $arr_prepare);
    $result_todo[$key]["contents"] = $result_chk_list;
  }

  // for($i = 0; $i < count($result_todo); $i ++){
  //   $arr_prepare = [
  //     "list_id" => $result_todo[$i]["id"]
  //   ];

  //   $checklists = [];
  //   $result_checklists = get_checklist_forboard($conn, $arr_prepare);

  //   foreach($result_checklists as $key => $value){
  //     $checklists[$key] = $value["content"];
  //   }

  //   $result_todo[$i]["content"] = $checklists;
  // }

  return $result_todo;
}

/**
 * 사용금지. board에 checklist 출력할 때 사용하는 함수
 */

function get_checklist_forboard(PDO $conn, array $arr_param){
  $sql =
  " SELECT content "
  ." FROM checklists "
  ." WHERE deleted_at IS NULL "
  ." AND checklists.content IS NOT NULL "
  ." AND checklists.content != '' "
  ." AND list_id = :list_id "
  ." ORDER BY id "
  ." LIMIT 4 "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> get_checklist_forboard");
  }

  return $stmt -> fetchAll();
}

// /**
  //  * todo list 카드에 출력할 때 쓰는 함수.
  //  * 다음 정보가 출력됨.
  //  * todolist id
  //  * , todolist 제목
  //  * , todolist 마감기한
  //  * , todolist에 있는 체크리스트
  //  * , todolist의 체크리스트 체크 여부
  //  * 
  //  * @param $arr_param = :limit, :offset
  //  */
  // function get_todolist_board(PDO $conn, array $arr_param){
  //   $sql = 
  //   " SELECT                              "
  // 	." todo.id                            "
  // 	." ,todo.name                         "
  //   ." ,todo.deadline                     "
  // 	." ,checklists.content                "
  // 	." ,checklists.ischecked              " 
  //   ." FROM (                             "
  // 	." SELECT *                           "
  // 	." FROM todolists                     "
  // 	." WHERE todolists.deleted_at IS NULL "
  //   ." ORDER BY todolists.deadline        "
  // 	." LIMIT :limit                       " // prepare statmente
  // 	." OFFSET :offset                     " // prepare statmente
  // 	." ) AS todo                          "
  // 	." JOIN checklists                    "
  //   ." ON todo.id = checklists.list_id    "
  //   ." AND checklists.deleted_at IS NULL  "
  //   ." ;                                  "
  //   ;

  //   $stmt = $conn->prepare($sql);

  //   $result = $stmt->execute($arr_param);

  //   if(!$result){
  //     throw new Exception("Error : Query has problem -> get_todolist_board");
  //   }

  //   return $stmt -> fetchAll();
  // }

/**
 * todolist 상세페이지 출력할 때 쓰는 함수
 * 
 * 다음 정보가 출력됨
 * todolist_id
 * ,name
 * ,deadline
 * ,checklist_id
 * ,content
 * ,ischecked
 * 
 * 필요 정보 : todolist_id
 * 
 * @param $arr_param : todolist_id 배열 또는 숫자가 들어가야 함
 */
function get_todolist_detail(PDO $conn, array | int $arr_param){
  $sql =
  " SELECT "
  ." todolists.id AS todolist_id "
  ." ,todolists.NAME AS name"
  ." ,todolists.deadline "
  ." ,checklists.id AS checklist_id "
  ." ,checklists.content "
  ." ,checklists.ischecked "
  ." FROM "
  ." todolists "
  ." JOIN "
  ." checklists "
  ." ON "
  ." todolists.id = checklists.list_id "
  ." AND "
  ." todolists.id = :id "
  ." AND "
  ." checklists.deleted_at IS NULL "
  ." ; "
  ;

  $id = $arr_param;
  $arr_prepare = [];
  if(gettype($id) === "integer"){
    $arr_prepare["id"] = $id;
  }
  else{
    $arr_prepare = $arr_param;
  }

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> get_todolist_detail");
  }

  return $stmt -> fetchAll();
}

/**
 * 오늘까지 할 일 체크리스트 출력하는 함수
 * 
 * 다음 정보가 출력됨
 * checklists.content
 * 
 * @param $arr_param : :limit, :offset 
 */
function get_checklist_today(PDO $conn, array $arr_param){
  $sql =
  " SELECT checklists.content "
  ." FROM checklists "
  ." WHERE checklists.deleted_at IS NULL "
  ." AND checklists.ischecked = FALSE "
  ." AND checklists.content IS NOT NULL "
  ." AND checklists.content != '' "
  ." AND checklists.list_id IN "
  ." (SELECT todolists.id "
  ." FROM todolists "
  ." WHERE todolists.deadline = CURDATE() "
  ." AND todolists.deleted_at IS NULL"
  ." ) "
  ." ORDER BY checklists.id "
  ." LIMIT :limit  "
  ." OFFSET :offset "
  ." ; ";

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> get_checklist_today");
  }

  return $stmt -> fetchAll();
}

/**
 * 방명록 정보 출력하는 함수
 * 
 * 다음 정보가 출력됨
 * id
 * , user_id
 * , content
 * , created_at
 * , user_name
 * 
 * @param $arr_param : :limit, :offset 
 */
function get_guestbook_board(PDO $conn, array $arr_param){

  $sql = 
  " SELECT                                "                   
  ." guest_books.id                       "                      
  ." ,guest_books.user_id                 "                 
  ." ,guest_books.content                 "                
  ." ,guest_books.created_at              "
  ." ,users.user_name                     "            
  ." FROM guest_books                     "
  ." JOIN users                           "
  ." ON guest_books.user_id = users.id    "
  ." AND guest_books.deleted_at IS NULL   "
  ." ORDER BY guest_books.id DESC         "
  ." ,guest_books.created_at DESC         "
  ." LIMIT :limit                         "
  ." OFFSET :offset                       "          
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> get_guestbook_board");
  }

  return $stmt -> fetchAll();
}

/**
 * 방명록 글 작성할 때 쓰는 함수
 * 
 * 필요한 정보
 * user_id
 * ,content
 * 
 * @param $arr_param : :user_id, :content
 */

function insert_guestbook_board(PDO $conn, array $arr_param){

  $sql =
  " INSERT INTO guest_books( "
  ." user_id "
  ." ,content "
  ." ) " 
  ." VALUES( "
  ." :user_id "
  ." ,:content "
  ." ); ";

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_count = $stmt ->rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> insert_guestbook_board");
  }

  if(!($result_count === 1)){
    throw new Exception("Error : Query count has problem -> insert_guestbook_board");
  }

  return null;
}

/**
 * page count할 때 쓰는 함수
 */
function cnt_checklist_today(PDO $conn){
  $sql =
 " SELECT COUNT(*) AS COUNT "
  ." FROM checklists "
  ." WHERE checklists.content != '' "
  ." AND checklists.content IS NOT NULL "
  ." AND checklists.deleted_at IS NULL "
  ." AND checklists.ischecked = 0     "
  ." AND checklists.list_id IN  "
  ." ( SELECT todolists.id "
  ." FROM todolists "
  ." WHERE todolists.deleted_at IS NULL "
  ." AND todolists.deadline = CURDATE() "
  ." ) ";

  $stmt = $conn -> query($sql);
  $result =$stmt -> fetch();

  return $result["COUNT"];
}

function cnt_checklist_todo(PDO $conn){
  $sql =
 " SELECT COUNT(*) AS COUNT    "
  ." FROM todolists            "
  ." WHERE deleted_at IS NULL  "
  ." AND deadline >= CURDATE() "
  ;

  $stmt = $conn -> query($sql);
  $result =$stmt -> fetch();

  return $result["COUNT"];
}

function cnt_checklist_todo_history(PDO $conn){
  $sql =
 " SELECT COUNT(*) AS COUNT    "
  ." FROM todolists            "
  ." WHERE deleted_at IS NULL  "
  ." AND deadline < CURDATE() "
  ;

  $stmt = $conn -> query($sql);
  $result =$stmt -> fetch();

  return $result["COUNT"];
}


/**
 * 방명록 총 게시글 조회할 때 쓰는 함수
 * 필요정보
 */
function cnt_guestbook_board(PDO $conn){
  $sql =
  " SELECT                  "
  ."          COUNT(*)      "
  ." FROM                   "
  ."     guest_books        "
  ." WHERE                  "
  ."     deleted_at IS NULL "
  ;
  $stmt = $conn->query($sql);
  $result = $stmt->fetch();

  return $result["COUNT(*)"];
}


/**
 * 방명록 삭제할 때 쓰는 함수
 * 
 * 필요한 정보
 * : guest_books id
 * 
 * @param $arr_param : :id, int 또는 array가 들어갈 수 있음
 */

function delete_guestbook_board(PDO $conn, array | int $arr_param){

  $sql =
  " UPDATE guest_books   "
  ." SET                 "
  ." updated_at = NOW()  "
  ." ,deleted_at = NOW() "
  ." WHERE               "
  ." id = :id            "
  ." ;                   ";

  $id = $arr_param;
  $arr_prepare = [];
  if(gettype($id) === "integer"){
    $arr_prepare["id"] = $id;
  } else {
    $arr_prepare = $arr_param;
  }

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);
  $result_count = $stmt ->rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> delete_guestbook_board");
  }

  if(!($result_count === 1)){
    throw new Exception("Error : Query count has problem -> delete_guestbook_board");
  }

  return null;
}

/**
 * DB에 그 계정이름을 가지고 있는지 확인하는 함수
 * 
 * 가지고 있으면 true, 없으면 false를 반환함 
 * 
 * @param $arr_param : :user_name , string 또는 array가 들어갈 수 있음
 */
function check_user_name(PDO $conn, array | string $arr_param){

  $sql = 
  " SELECT                  "
  ." user_name              "
  ." FROM                   " 
  ." users                  "
  ." WHERE                  "
  ." user_name = :user_name "
  ." ; ";

  $user_name = $arr_param;
  $arr_prepare = [];
  if(gettype($user_name) === "string"){
    $arr_prepare["user_name"] = $user_name;
  }
  else{
    $arr_prepare = $arr_param;
  }

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> check_user_id");
  }

  if($stmt -> rowCount() >= 1){
    return true;
  }

  return false;
}
/**
 * 계정이 있는지 확인하는 함수
 * 
 * 계정을 가지고 있으면 true, 안 가지고 있으면 false를 반환함
 * 
 * @param $arr_param : :user_name :user_password 
 */
function check_account(PDO $conn, array $arr_param, array &$user_data){
  
  $sql =
  " SELECT user_password, ismanager, id   "
  ." FROM users                           "
  ." WHERE user_password = :user_password "
  ." AND user_name = :user_name           "
  ." AND deleted_at IS NULL               "
  ." ; "
  ;

  $arr_prepare = [
    "user_password" => $arr_param["user_password"]
    ,"user_name" => $arr_param["user_name"]
  ];
  
  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> check_account");
  }

  if(!($result_cnt === 1)){
    return false;
  }

  $user_data = ($stmt -> fetch());

  return true;
}

/**
 * 회원가입 시 사용하는 함수
 * 
 * @param $arr_param : :user_name :user_password
 */
function insert_membership(PDO $conn, array $arr_param){
  if(check_user_name($conn, $arr_param["user_name"])){
    throw new Exception("이미 아이디가 있습니다.");
  }
  
  $sql =
  " INSERT INTO users( "
  ." user_name         "
  ." ,user_password    "
  ." )                 "
  ." VALUES(           "
  ." :user_name        "
  ." ,:user_password   "
  ." )                 "
  ." ;                 "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> insert_membership");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> insert_membership");
  }

  return null;
}

/** 
 * todolist 작성할 때 사용하는 함수 
 * 
 * @param $arr_param : :name, :deadline
 * @param $checklists : input에 적은 데이터 값들
*/
function insert_todolist(PDO $conn, array $arr_param, array $checklists){
  $sql =
  " INSERT INTO todolists( "
  ." name                  "
  ." ,deadline             "
  ." )                     "
  ." VALUES(               "
  ." :name                 "
  ." ,:deadline            "
  ." ); ";

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> insert_todolist");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> insert_todolist");
  }

  $lastID = $conn -> lastInsertId();

  foreach($checklists as $key => $value){
    $arr_prepare = [
      "list_id"   => $lastID
      ,"content"  => $value
      ,"input_id" => $key
    ];

    insert_checklist ($conn, $arr_prepare);
  }
  
  
  return $lastID;
}

/**
 * 체크리스트 추가 시 사용하는 함수
 * 일반적으로 잘 사용안할텐데, update할 때 사용할 듯
 * 
 * @param $arr_param : :list_id, :content
 */

function insert_checklist(PDO $conn, array $arr_param){
  $sql = 
  " INSERT INTO checklists( "
  ." list_id                "
  ." ,content               "
  ." ,input_id              "
  ." )                      "
  ." VALUES(                "
  ." :list_id               "
  ." ,:content              "
  ." ,:input_id             "
  ." )                      "
  ." ;                      "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> insert_checklist");
  }

  return null;
}


/**
 * 체크리스트 갱신할 때 쓰는 함수
 * 
 * @param $arr_todolist_id : :list_id
 * @param $arr_checklists_id : :checklists_id
 */
function save_checklists(PDO $conn, array $arr_todolist_id ,array $arr_checklists_id){

  $sql =
  " UPDATE checklists "
  ." SET "
  ." ischecked = 0 "
  ." ,updated_at = NOW() "
  ." WHERE "
  ." list_id = :list_id ";

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_todolist_id);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> save_checklist");
  }


  $checklists_id = $arr_checklists_id["checklists_id"];
  $sql =
  " UPDATE checklists "
  ." SET "
  ." ischecked = 1 "
  ." ,updated_at = NOW() "
  ." WHERE "
  ." id IN ( $checklists_id ) "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute();
  // $result_flg = $stmt -> execute($arr_checklists_id);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> save_checklist");
  }

  return null;
}

/**
 * todo list 삭제할 때 쓰는 함수
 * 
 * @param $arr_param : :id int 또는 array 사용 가능
 */
function delete_todolist(PDO $conn, array | int $arr_param){
  $sql=
  " UPDATE todolists     "
  ." SET                 "
  ." updated_at = NOW()  "
  ." ,deleted_at = NOW() "
  ." WHERE               "
  ." id = :id            "
  ." ;                   "
  ;

  $id = $arr_param;
  $arr_prepare = [];

  if(gettype($id) === "integer" ){
    $arr_prepare["id"] = $id;
  }
  else{
    $arr_prepare = $arr_param;
  }

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> delete_todolist, todo_list");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> delete_todolist, todo_list");
  }

  $sql=
  " UPDATE checklists    "
  ." SET                 "
  ." updated_at = NOW()  "
  ." ,deleted_at = NOW() "
  ." WHERE               "
  ." list_id = :id       "
  ." ;                   "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> delete_todolist, checklists");
  }

  return null;
}

function update_todolist(PDO $conn, array $arr_param, array $checklists){

  $sql = 
  " UPDATE todolists                 "
  ." SET                             "
  ." todolists.NAME = :name          "
  ." ,todolists.deadline = :deadline "
  ." ,updated_at = NOW()             "
  ." WHERE                           "
  ." id = :id                        "
  ." ;                               "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> update_todolist, todolist");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> update_todolist, todolist");
  }

  foreach($checklists as $key => $value){
    $arr_prepare = [
      "content"   => $value
      ,"list_id"  => $arr_param["id"]
      ,"input_id" => $key
    ];

    update_checklist($conn, $arr_prepare);
  }

  return;
}

/**
 * 체크리스트 수정하는 함수
 * 
 * @param $arr_param : :content, :list_id, :input_id
 */
function update_checklist(PDO $conn, array $arr_param){
  $sql =
  " UPDATE checklists "
  ." SET "
  ." updated_at = NOW() "
  ." , content = :content "
  ." , ischecked = 0   "
  ." WHERE "
  ." list_id = :list_id "
  ." AND input_id = :input_id "
  ." ; "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> update_checklist");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> update_checklist");
  }

  return;
}

function get_todolist_board_history(PDO $conn, array $arr_param){
  $sql = 
  " SELECT id, name, deadline  "
  ." FROM todolists            "
  ." WHERE deleted_at IS NULL  "
  ." AND deadline < CURDATE() "
  ." ORDER BY deadline ASC     "
  ." LIMIT :limit              "
  ." OFFSET :offset            "
  ; 

  $stmt = $conn -> prepare($sql);
  $stmt -> execute($arr_param);

  $result_todo = $stmt -> fetchAll();

  foreach($result_todo as $key => $item) {
    $arr_prepare = [
      "list_id" => $item["id"]
    ];

    $result_chk_list = get_checklist_forboard($conn, $arr_prepare);
    $result_todo[$key]["contents"] = $result_chk_list;
  }

  return $result_todo;
}