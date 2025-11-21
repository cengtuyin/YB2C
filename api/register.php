<?php
/*
*   API - 注册
*   UserID 可以是任何文本(需要符合Linux文件命名规则)
*   Time : 2025-11-19
*/


// 标记为无需登录
$NotLogin = true;
include_once("../config.php");
if(!$AllConfig["register"]["allow"]) ReturnData(array("code"=>4001,"msg"=>$AllConfig["register"]["message"]?:"系统可能出现异常"));

// 用户基本数据
$UserData = array();
$UserData["name"] = $_REQUEST["user"];
$UserData["mail"] = $_REQUEST["mail"];
$UserData["password"] = $_REQUEST["password"];
$UserData["register_ip"] = GetClientIP();
$UserData["register_time"] = date("Y-m-d h:i:s");

// 用户配置
$UserConfig = array();
// 用户权限配置
$UserPermission = array();
$UserPermission["normal"] = 0;
$UserPermission["group"] = array("user");
$UserConfig["permission"] = $UserPermission;

// 限制一个IP可注册的账号量
if(findUserIDs($UserData["register_ip"]) >= $AllConfig["register"]["ipmax"]){
    ReturnData(array("code"=>404,"msg"=>"IP注册量达到上限"));
}

foreach (array("user","mail","password") as $value) {
    if(!$_REQUEST[$value]) ReturnData(array("code"=>400,"msg"=>"参数缺失"));
}

// if(!is_numeric($UserData["qq"])) ReturnData(array("code"=>403,"msg"=>"错误的QQ号"));

// 因 用户名 也被用于登录，所以禁止重复
if(findUserID($UserData["name"],"login") || findUserID($UserData["mail"],"login")){
    ReturnData(array("code"=>401,"msg"=>"邮箱或用户名重复！"));
}

// 基于时间生成的 MD5 作为 UserID
$UserID = md5(microtime(true));
$UserPath = "../data/user/$UserID";
// 避免同一时刻注册
if(file_exists($UserPath)) ReturnData(array("code"=>402,"msg"=>"系统繁忙，请重试"));

mkdir("$UserPath");
putUserData($UserData,false,$UserID);
putUserConfig($UserConfig,false,$UserID);

ReturnData(array("code"=>200,"msg"=>"注册成功"));

?>