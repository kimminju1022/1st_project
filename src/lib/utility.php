<?php

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
  if(!session_id()) {
  session_start();
  }
  $_SESSION["id"] = $id;
  $_SESSION["manager"] = $ismanager;
}

function logout(){
  if(!session_id()) {
    session_start();
  }
  unset($_SESSION["id"]);
  unset($_SESSION["manager"]);

  session_destroy();

  Header("Location: /login.php");
}

function go_login(){
  if(!session_id()) {
    session_start();
  }
  $id = isset($_SESSION["id"]) ? $_SESSION["id"] : null;

  if(is_null($id)){
    Header("Location: /login.php");
    exit;
  }
}