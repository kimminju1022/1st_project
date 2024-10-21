<?php
if (!session_id()) {
  session_start();
}
require_once($_SERVER["DOCUMENT_ROOT"]."/config.php");

/**
 * 체크 되었는지 확인하는 함수
 * @param $value : 숫자 0, 1
 */
function ischecked(int $value){
  if($value === 1){
    return true;
  }
  return false;
}

function login(int $id, bool $ismanager){
  $_SESSION["id"] = $id;
  $_SESSION["manager"] = $ismanager;
}

function logout(){
  unset($_SESSION["id"]);
  unset($_SESSION["manager"]);

  session_destroy();

  Header("Location: /login.php");
}

function go_login(){
  $id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;

  if(is_null($id)){
    Header("Location: login.php");
    exit;
  }
}

function check_manager(){

  $ismanager = isset($_SESSION["manager"]) ? $_SESSION["manager"] : null;

  if(is_null($ismanager) || !$ismanager){
    Header("Location: /error.php");
  }
}