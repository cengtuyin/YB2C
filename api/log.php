<?php
/*
*   API - 日志
*   请输入文本
*   Time : 2025-11-21
*/

// 标记为无需登录
$NotLogin = true;
include_once("../config.php");

$DataType = $_REQUEST["type"];

if(!$DataType) ReturnData(array("code"=>400,"msg"=>"参数缺失"));

switch ($DataType) {
    case 'start':
        $Project = array(   "version_name"=>$_REQUEST["version_name"],      // 项目版本名称
                            "version_code"=>$_REQUEST["version_code"],      // 项目版本代码
                            "system"=>$_REQUEST["system"],                  // Android || IOS || Windows || Ubuntu ...
                            "device_cs"=>$_REQUEST["device_cs"],            // 设备厂商
                            "device_xh"=>$_REQUEST["device_xh"]);           // 设备型号
        
        break;
    
    default:
        break;
}

?>