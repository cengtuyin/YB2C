<?php
/*
*   API - 系统工具
*   将本Api定时允许，用于缓存释放 ( 以及日数据重置 )
*   Time : 2025-11-21
*/

// 标记为无需登录
$NotLogin = true;
include_once("./config.php");

// 如果该Api被外部访问，百分百可以确定是恶意攻击。
if(GetClientIP() !== "127.0.0.1") die("ERROR!!!");


echo "Delete ./tmp";

unlink("./tmp/day");
mkdir("./tmp/day");

/*unlink("./tmp/token");
mkdir("./tmp/token");*/

/*unlink("./tmp/try");
mkdir("./tmp/try");*/

echo "Successful.";

?>