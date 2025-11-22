<?php
/*
*   API - 墙列表
*   Time : 2025-11-19
*/

include_once("../config.php");

$RWallData = array();
$WallDatas = json_decode(file_get_contents("$RunPath/admin/walls_data.json"),true);
if($UserData = getUserData()){
    foreach ($UserData["qwall"] as $value) {
        $RWallData[$value] = $WallDatas[$value];
    }
    ReturnData(array("code"=>200,"data"=>$RWallData));
}else{
    ReturnData(array("code"=>400,"msg"=>"账号数据异常，请联系客服解决！错误码：$NowUserID"));
}

?>