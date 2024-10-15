<?php
// 데이터베이스 관련 상수
define("MY_HOST", "112.222.157.156");
define("MY_PORT", "6512");
define("MY_USER", "team1");
define("MY_PASSWORD", "team1");
define("MY_DB_NAME", "team1");
define("MY_CHARSET", "utf8mb4");
define("MY_DSN", "mysql:host=".MY_HOST.";port=".MY_PORT.";dbname=".MY_DB_NAME.";charset=".MY_CHARSET);

// 경로 관련 상수
define("MY_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("MY_ROOT_DB_LIB",MY_ROOT."/lib/db_lib.php");
define("MY_ROOT_UTILITY", MY_ROOT."/lib/utility.php");
define("MY_ROOT_ERRORPAGE", MY_ROOT."/error.php");

// 값 관련 상수
define("MY_BOARD_CARD_COUNT", 8);
define("CHECKLIST_INPUT_COUNT", 20);
define("MY_VISIT_COUNT", 4);