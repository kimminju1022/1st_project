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
 * todo list 카드에 출력할 때 쓰는 함수.
 * 다음 정보가 출력됨.
 * todolist id
 * , todolist 제목
 * , todolist 마감기한
 * , todolist에 있는 체크리스트
 * , todolist의 체크리스트 체크 여부
 * 
 * @param $arr_param = :limit, :offset
 */
function get_todolist_board(PDO $conn, array $arr_param){
  $sql = 
  " SELECT                              "
	." todo.id                            "
	." ,todo.name                         "
  ." ,todo.deadline                     "
	." ,checklists.content                "
	." ,checklists.ischecked              " 
  ." FROM (                             "
	." SELECT *                           "
	." FROM todolists                     "
	." WHERE todolists.deleted_at IS NULL "
  ." ORDER BY todolists.deadline        "
	." LIMIT :limit                       " // prepare statmente
	." OFFSET :offset                     " // prepare statmente
	." ) AS todo                          "
	." JOIN checklists                    "
  ." ON todo.id = checklists.list_id    "
  ." AND checklists.deleted_at IS NULL  "
  ." ;                                  "
  ;

  $stmt = $conn->prepare($sql);

  $result = $stmt->execute($arr_param);

  if(!$result){
    throw new Exception("Error : Query has problem -> get_todolist_board");
  }

  return $stmt -> fetchAll();
}

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
 * 방명록 정보 출력하는 함수
 * 
 * 다음 정보가 출력됨
 * id
 * , user_id
 * , content
 * , created_at
 * 
 * @param $arr_param : :limit, :offset 
 */
function get_guestbook_board(PDO $conn, array $arr_param){

  $sql = 
  " SELECT                    "
  ." id                       "
  ." ,user_id                 "
  ." ,content                 "
  ." ,created_at              "
  ." FROM guest_books         "
  ." WHERE deleted_at IS NULL "
  ." ORDER BY created_at DESC "
  ." LIMIT :limit             "
  ." OFFSET :offset           "
  ." ;                        "
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
  
  if(check_user_name($conn, $arr_param["user_name"]) === false){
    return false;
  }
  
  $sql =
  " SELECT user_password, ismanager, id   "
  ." FROM users                           "
  ." WHERE user_password = :user_password "
  ." ; "
  ;

  $arr_prepare = [
    "user_password" => $arr_param["user_password"]
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
  $result_cnt = $stmt -> rowCount($arr_param);

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
  
  
  return null;
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
 * @param $arr_param : :ischecked, :id
 */
function save_checklist(PDO $conn, array $arr_param){

  $sql =
  " UPDATE checklists "
  ." SET "
  ." ischecked = :ischecked "
  ." ,updated_at = NOW() "
  ." WHERE "
  ." id = :id "
  ." ; ";

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_param);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> save_checklist");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> save_checklist");
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
    $arr_prepare = $arr_param;
  }
  else{
    $arr_prepare["id"] = $id;
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
  ." user_id = :id       "
  ." ;                   "
  ;

  $stmt = $conn -> prepare($sql);
  $result_flg = $stmt -> execute($arr_prepare);
  $result_cnt = $stmt -> rowCount();

  if(!$result_flg){
    throw new Exception("Error : Query has problem -> delete_todolist, checklists");
  }

  if(!($result_cnt === 1)){
    throw new Exception("Error : Query count has problem -> delete_todolist, checklists");
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