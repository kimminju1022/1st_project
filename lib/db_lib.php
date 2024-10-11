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
 * @param $id : 유저 id
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
 * 필요 값 = :limit, :offset
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


}