<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");
require_once(MY_ROOT_DB_LIB);
session_start();

$conn = null;

try {
    // POST 처리
        // parameter 획득(id, page, 제목, deadline)
        // img는 밑에서 동적 처리를 하기 때문에 여기서 획득하지 않는다.
        // id 획득
        $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

        // page 획득
        $page_todo = isset($_GET["page_todo"]) ? (int)$_GET["page_todo"] : 1;

        $page_checklist = isset($_GET["page_checklist"]) ? (int)$_GET["page_checklist"] : 1;

        //
        $checked = $_POST["chk"];
        // var_dump($checked);
        // exit;
        if($id < 1) {
            throw new Exception("파라미터 오류 : P");
        }
    
        // PDO Instance
        $conn = my_db_conn();

        $arr_todolist_id = [
            "list_id" =>  $id
        ];

        $checked_ids = empty($checked) ? -1 : implode(", ", $checked);
        $arr_checklists_input_id = [
            "checklists_id" => $checked_ids
        ];

        // var_dump($checked_ids);
        // exit;

        // beginTransaction / Transaction Start
        $conn->beginTransaction();


        save_checklists($conn, $arr_todolist_id, $arr_checklists_input_id);

        // commit
        $conn->commit();

        // detail 페이지로 이동
        header("Location: /todo_list_detail.php?id=".$id."&page_todo=".$page_todo."&page_checklist=".$page_checklist);
        exit;
    } catch(Throwable $th) {
    if(!is_null($conn) && $conn -> inTransaction()) {
        $conn -> rollBack();
    }

    echo $th ->getMessage();
    exit;
}