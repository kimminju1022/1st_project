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
  session_start();

  $_SESSION["id"] = $id;
  $_SESSION["manager"] = $ismanager;
}