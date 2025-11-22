<?php
/*
*   API - 新建墙
*   Time : 2025-11-19
*/

include_once("../config.php");

$WallData_info = array("platform","status","contact","qqnum","certified","operator_type","register_time","details","logo");
$WallData = array();
foreach ($WallData_info as $value) {
    if(!$_REQUEST[$value]) ReturnData(array("code"=>400,"msg"=>"参数缺失"));
    $WallData[$value] = $_REQUEST[$value];
}
$WallData["register_ip"] = GetClientIP();
$WallData["register_time"] = date("Y-m-d h:i:s");

// 此处应该增加更多数据判断，但我懒
if(!is_numeric($WallData["qqnum"])) ReturnData(array("code"=>401,"msg"=>"错误的QQ号"));

$WallDatas = json_decode(file_get_contents("$RunPath/admin/walls_data.json"),true);
foreach ($WallDatas as $key -> $value) {
    if($value["qqnum"] == $WallData["qqnum"])  ReturnData(array("code"=>402,"msg"=>"该墙已经被添加过了"));
}

// 基于时间生成的 MD5 作为标识
$WallID = md5(microtime(true));
array_push($WallDatas, array("wall_$WallID"=>$WallData));

file_put_contents("$RunPath/admin/walls_data.json",json_encode($WallDatas,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

putUserData(array("qwall"=>array($WallID)));

ReturnData(array("code"=>200,"msg"=>"注册成功"));

?>