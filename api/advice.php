<?php
/*
*   API - 用户反馈
*   请输入文本
*   Time : 2025-11-21
*/

// 标记为无需登录
$NotLogin = true;
include_once("../config.php");

$AdviceData = array();
foreach (array("title","content") as $value) {
    if(!@$_REQUEST[$value]) ReturnData(array("code"=>400,"msg"=>"参数缺失"));
    $AdviceData[$value] = @$_REQUEST[$value];
}
$AdviceData["type"] = @$_REQUEST["type"]?:"未知";
$AdviceData["status"] = "on";
$AdviceData["userid"] = $NowUserID!=="null"?$NowUserID:"未知";
$AdviceData["ip"] = GetClientIP();
$AdviceData["time"] = date("Y-m-d h:i:s");

newAdvice($AdviceData);

ReturnData(array("code"=>200,"msg"=>"提交成功"));


?>