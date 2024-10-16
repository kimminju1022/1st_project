<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php"); //config파일의 정보를 가져와 쓴다
require_once(MY_ROOT_DB_LIB); //db_lib 파일의 정보를 가져와 쓴다
session_start();

// input post처리-------------
$conn = null;

try {
    $conn = my_db_conn();
    $id = isset($_SESSION["id"]) ? (int)$_SESSION["id"] : 0;
    $content = isset($_POST["content"]) ? $_POST["content"] : "";

    if($id < 0){
        throw new Exception("error : id can't find.");
    }

    $arr_prepare = [
        "user_id" => $id,
        "content" => $content
    ];
    // beginTransaction / Transaction Start
    $conn->beginTransaction();


    insert_guestbook_board($conn, $arr_prepare);

    // commit
    $conn->commit();

    header("Location: /visit.php");
    exit;
} catch (Throwable $th) {
    if (!is_null($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    echo $th->getMessage();
    exit;
}

?>
