<?php
// 데이터베이스 관련 상수
define("MY_HOST", "localhost");
define("MY_PORT", "3306");
define("MY_USER", "root");
define("MY_PASSWORD", "php504");
define("MY_DB_NAME", "traval_todolist");
define("MY_CHARSET", "utf8mb4");
define("MY_DSN", "mysql:host=".MY_HOST.";port=".MY_PORT.";dbname=".MY_DB_NAME.";charset=".MY_CHARSET);

// 경로 관련 상수
define("MY_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("MY_DB_LIB",MY_ROOT."/lib/db_lib.php");

// 값 관련 상수
define("MY_BOARD_CARD_COUNT", 8);