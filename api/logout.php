<?php
/*
*   API - 退出登录
*   横扫 Token 做回自己！ ( LoginToken )
*   Time : 2025-11-21
*/

// 标记为无需登录
$NotLogin = true;
include_once("../config.php");

// 有数据则仅退出正在使用的 Token
$onlyNowToken = @$_REQUEST["onlynowtoken"];

$returndata = isLoginS();
setcookie("token", "", 0, "/");

// Token无效，无法进一步处理
if(@$UserID) $NowUserID = $UserID;
else $returndata?ReturnData(array("code"=>200,"msg"=>"退出成功")):false;



$UserTokenData = getUserToken($NowUserID);
foreach ($UserTokenData as $key=>$value) {
    $TokenData = @getUserToken($value["uuid"]);
    if(($value["type"] === "login" && $TokenData["userid"] === $NowUserID && $onlyNowToken?($TokenData["uuid"] === $NowToken):true) || !is_file("$RunPath/tmp/token/".$value["uuid"].".json")){
        @unlink("$RunPath/tmp/token/".$value["uuid"].".json");
        unset($UserTokenData[$key]);
    }
}
// 清空账号的 LoginToken
putUserToken($UserTokenData,false,$NowUserID);

ReturnData(array("code"=>200,"msg"=>"退出完成"));

?>