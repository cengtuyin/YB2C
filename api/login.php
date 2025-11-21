<?php
/*
*   API - 登录
*   使用 用户名 || 邮箱 作为登录参数
*   Time : 2025-11-19
*/

// 标记为无需登录
$NotLogin = true;
include_once("../config.php");
if(!$AllConfig["login"]["allow"]) ReturnData(array("code"=>4001,"msg"=>$AllConfig["login"]["message"]?:"系统可能出现异常"));

$UserName = $_REQUEST["user"];
$UserPassword = $_REQUEST["password"];

if(!$UserName || !$UserPassword) ReturnData(array("code"=>400,"msg"=>"参数缺失"));

if(!$UserID = findUserID($UserName,"login")){
    ReturnData(array("code"=>401,"msg"=>"未找到账号"));
}

if($UserData = getUserData($UserID)){
    if(getUserTokens($UserID,"login") >= $AllConfig["login"]["allowmoretoken_max"]) ReturnData(array("code"=>406,"msg"=>"真的假的，你有那么多设备？"));
    $TokenData = @getUserToken($UserID)[0];
    if($TokenData && @$TokenData["passworderrornumber"] >= $AllConfig["login"]["password_error_amount"]){
        // 封禁 AlllConfig.login.password_error_bantime 分钟
        putUserData(array("ban"=>array("time"=>time()+($AllConfig["login"]["password_error_bantime"]),"msg"=>"密码错误过多")), true, $UserID);
        $TokenData["passworderrornumber"] = 0;
        putUserToken($TokenData,true,$UserID);
        ReturnData(array("code"=>404,"msg"=>"错误次数过多，请等待 114514 秒"));
    }
    if($UserData["ban"]['time'] >= time()) ReturnData(array("code"=>405,"msg"=>"错误次数过多，请等待 ".($UserData["ban"]['time'] - time())." 秒"));
    if($UserData["password"] === $UserPassword){
        $UUID = CreateANewUUID();
        // 将传输给用户的 UUID 进行加密，以迷惑
        $crypto = new AESCrypto($AES_TOKEN);
        setcookie("token", $crypto->encrypt($UUID), time() + $AllConfig["login"]["tokentime"], "/");
        $TokenData = array( "type"=>"login",
                            "userid"=>$UserID,
                            "time"=>time(),
                            "ip"=>GetClientIP(),
                            "uuid"=>$UUID,
                            "passworderrornumber"=>0);
        putUserToken($TokenData,false,$UserID);
        // putUserData(array("lastip"=>GetClientIP(),"lastlogintime"=>date("Y-m-d h:i:s")), true, $UserID);
        ReturnData(array("code"=>200,"msg"=>"登录成功"));
    }else{
        // 频率限制
        $TokenData["passworderrornumber"]++;
        putUserToken($TokenData,true,$UserID);
        ReturnData(array("code"=>403,"msg"=>"密码错误"));
    }
}else{
    ReturnData(array("code"=>402,"msg"=>"账号数据异常，请联系客服解决！错误码：$UserID"));
}

function CreateANewUUID(){
    global $RunPath;
    $UUID = create_uuid();
    if(is_file("$RunPath/tmp/token/$UUID.json")) $UUID = CreateANewUUID();
    return $UUID;
}

?>