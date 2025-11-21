<?php
/*
*   YB2C - 参数配置&常用方法
*   By : yin - 3435691746
*   Time : 2025-11-18 - 2025-11-21
*
*   注意 : 应该在 Nginx 中禁止访问 /tmp /data 避免安全问题！
*           location ^~ /(tmp|data) {
*               deny all;
*           }
*   
*   控制面板账号 : admin 123456     !!!     记得修改密码，最好UserID也改
*/

// 运行中需要将下面函数的注释移除，开发环境中需要注释，不然看不到报错
// error_reporting(0);

/* - 常改配置 - */                                                      // --- 常改配置 ---
$Debug = true;                                                         // 调试模式 - 关闭 : 加密数据 & 无限调试断点
$WhiteListMode = false;                                                 // 白名单模式 - 限制可访问IP ( 所有界面及Api ) #用于本系统被当作另一个网站的接口站时推荐使用 ps:控制面板也限制
$WhiteList = array("127.0.0.1");                                        // 允许的请求IP
// 全局泛用配置                                                         // --- 全局配置 ---
$AllConfig = array( "name"=>"YB2C",                                     // 给该面板起一个别名罢！
                    "isCloudFlare"=>true,                               // 是否为使用 CloudFlare，用于获取用户 IP
                    "openUserLog"=>true,                                // 记录用户行为
                    "AllInEncode"=>false,                               // 全局 Api 输出加密 AES-256-CBC Key:$AES_API  ( 不要开启，控制面板没写解密 hh )
                    "default"=>array(                                   // --- 默认配置 ---
                        "FindUserID"=>array("name","mail","lastip")),   // 作为匹配依据的参数
                    "login"=>array(                                     // --- 登录配置 ---
                        "allowmoretoken"=>true,                         // 允许同时登录多个Token ( 仍遵守时效限制 )
                        "allowmoretoken_max"=>30,                       // 最大可同时登录Token量
                        "tokentime"=>1*60*60,                           // 登录有效期 1 小时 ( 秒 )
                        "iptoken"=>false,                               // 对Token使用IP权鉴 ( 仅在登录的IP下有效，如果网络变动则失效 )
                        "allow"=>true,                                  // 允许登录
                        "message"=>"系统正在维护中",                    // 对用户的提示
                        "FindUserID"=>array("name","mail"),             // 作为匹配依据的参数
                        "password_error_amount"=>3,                     // 允许密码错误的次数
                        "password_error_bantime"=>15*60),               // 密码错误达到阈值后封禁 15 分钟 ( 秒 )
                    "register"=>array(                                  // --- 注册配置 ---
                        "ipmax"=>3,                                     // 限制一个IP可注册的账号量
                        "allow"=>true,                                  // 允许注册
                        "message"=>"系统正在维护中"));                  // 对用户的提示
// 全局权限配置                                                         // --- 权限组 ---   #如果你想添加权限组仅用于标识，可以添加一个没有任何权能的用户组，并使用 ifUserPermission("没有任何权能的用户组","group",$UserID):bool 作为判断
$AllPermission = array( "user"=>bindec("0000000000"),                   // User     普通用户的权限，注册后自动授予
                        "admin"=>bindec("1111111111"));                 // Admin    注意: 控制面板与用户系统采用同一套账号系统与API。此权限组用于控制面板访问，请不要将此权限组用于开发中使用！

/* - 系统路径 - */                                                      // --- 系统路径 ---
$RunPath = "F:/wwwroot/background.24h.fyi";                             // 本系统运行目录 ( config.php 所在的路径 )

/* - 加解密密钥 - */                                                    // --- 加解密密钥 --- #应该注意更换密钥而非默认
$AES_TOKEN = "0123456789abcdef0123456789abcdef";                        // 登录Token
$AES_API = "0123456789abcdef0123456789abcdef";                          // 全局 Api 加密

/* - 自定义返回 - */                                                    // --- 自定义返回---
$Return_DangerTips = false;                                             // 危险请求的返回 如: 无权限的访问、不合理的请求、可能造成漏洞的请求
function DangerTips(){
    global $Return_DangerTips;
    if(!$Return_DangerTips) return;
    header('HTTP/1.1 404 Not Found');
    include_once("$RunPath/404.php");
    exit;
}


/* - 以下的不要修改 - */
$YB2CInfo = array(  "version_name"=>"1.0.0",
                    "version_code"=>1);
if($WhiteListMode){
    $ClientIP = GetClientIP();
    if(!in_array($ClientIP,$WhiteList)){
        DangerTips();
        die("YB2C - 白IP模式 : 您的IP未在容许之中");
    }
}
$NowToken = "null";
$NowUserID = "null";
if(@$_COOKIE["token"]){
    $crypto = new AESCrypto($AES_TOKEN);
    $NowToken = @$crypto->decrypt($_COOKIE["token"]);
    $NowUserID = @getUserToken($NowToken)["userid"];
    
    // 防目录穿透
    if(@strpos($NowToken,".") !== false || @strpos($NowUserID,".") !== false){
        // include_once("$RunPath/tips/danger_cookie.php");
        DangerTips();
        die("YB2C - 设备环境异常，请稍后再试");
    }
}

if(!@$NotLogin) isLogin();
if(@$PagePermission){
    if(!ifUserPermission($PagePermission,"group",$NowUserID)){
        DangerTips();
        die("YB2C - 你无权操作！");
    }
}
if(@$isWeb){
    if($Debug) echo "<!-- YB2C Debug Modeing -->";
    else echo '<!-- YB2C Debugger Working --><script src="./src/js/debugger/?rand='.create_uuid().'"></script>';
}
$ClientIP = GetClientIP();
$S_isIn = false;
if ($handle = fopen("$RunPath/tmp/day/Data_UserIP.txt", "r")) {
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if (empty($line)) continue;
        if (strpos($line, $ClientIP) !== false) {
            $S_isIn = true;
            break;
        }
    }
    fclose($handle);
}
if(!$S_isIn){
    $S_file = fopen("$RunPath/tmp/day/Data_UserIP.txt", 'a');
    if ($S_file) {
        fwrite($S_file, "[".date("Y-m-d H:i:s",time())."] - ".$ClientIP."\n");
        fclose($S_file);
    }
}

// 验证登录并返回页面
function isLogin(){
    global $RunPath;
    global $AllConfig;
    switch (isLoginS()) {
        case -1:
            // 未登录
            // include_once("./tips/notlogin.php");
            include_once("$RunPath/web/login.php");
            exit;
        case 1:
            // Token失效
            // include_once("./tips/notlogin.php");
            include_once("$RunPath/web/login.php");
            exit;
        case 2:
            // 账号封禁
            // include_once("./tips/accountban.php");
            include_once("$RunPath/web/login.php");
            exit;
        default:
            break;
    }
}
// 验证登录_内部引用
function isLoginS(){
    global $RunPath;
    global $AllConfig;
    global $NowToken;
    global $NowUserID;
    if($NowToken === "null" || $NowUserID === "null") return -1;
    if(!is_file("$RunPath/tmp/token/$NowToken.json")) return 1;
    $NowTokenData = getUserToken($NowToken);
    $UserTokenData = getUserToken($NowUserID);
    $UserData = getUserData($NowUserID);
    
    // Token AllConfig.login.tokentime 失效 || 登录信息不匹配
    if(time() - $NowTokenData["time"] >= $AllConfig["login"]["tokentime"] || !in_array($NowTokenData,$UserTokenData) || ($AllConfig["login"]["iptoken"]?($NowTokenData["ip"]!==GetClientIP()):false)){
        // 此处仅删除了 /tmp/token 下的Token缓存，账户里的token没有被删除
        @unlink("$RunPath/tmp/token/$NowToken.json");
        return 1;
    }
    
    // 账号封禁
    if($UserData["ban"]['time'] >= time()) return 2;
    return 0;
}
// 用户权鉴     $Permission: 010101000 || GROUPNAME , $type: permission || group
function ifUserPermission($Permission,$type = "group",$userid = "null"){
    global $RunPath;
    global $UserPermission;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    $UserConfig = getUserConfig($userid);
    // 直接通过权限组进行判断
    if($type === "group") return in_array($Permission,$UserConfig["permission"]["group"]);
    // 计算用户权限 UserPermission + UserPermissionGroup
    $UserPermission = $UserConfig["permission"]["account"];
    foreach ($UserConfig["permission"]["group"] as $PermissionGroup) {
        $UserPermission = $UserPermission | $AllPermission[$PermissionGroup];
    }
    if($type === "permission") return $Permission & $UserPermission == $Permission;
}
// 用户名&邮箱查找用户ID Mode:default || login使用AllConfig.login.FindeUserID
function findUserID($Name,$Mode = "default"){
    global $RunPath;
    global $AllConfig;
    foreach (getUserIDList() as $UserID){
        foreach (json_decode(file_get_contents("$RunPath/data/user/$UserID/user.json"),true) as $key=>$values){
            if(in_array($key,$AllConfig[$Mode]["FindUserID"]) && $values == $Name) return $UserID;
        }
        // if($UserData["name"] === $Name || $UserData["mail"] === $Name) return $UserID;
    }
    return false;
}
// findUserID 的数量查询版本
function findUserIDs($Name,$Mode = "default"){
    global $RunPath;
    global $AllConfig;
    $i = 0;
    foreach (getUserIDList() as $UserID){
        foreach (json_decode(file_get_contents("$RunPath/data/user/$UserID/user.json"),true) as $key=>$values){
            if(in_array($key,$AllConfig[$Mode]["FindUserID"]) && $values == $Name) $i++;
        }
    }
    return $i;
}
// 参数为空则取现登录账户 用户ID||UUID
function getUserToken($userid = "null"){
    global $RunPath;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    if(is_file("$RunPath/tmp/token/$userid.json")) return json_decode(file_get_contents("$RunPath/tmp/token/$userid.json"),true);
    // if(isLoginS() !== true) return false;
    return json_decode(file_get_contents("$RunPath/data/user/$userid/token.json"),true);
}
// getUserToken 的取数量版本    不会验证 Token 是否有效
function getUserTokens($userid = "null",$type = "login"){
    global $RunPath;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    $i = 0;
    foreach ($UserTokenData = getUserToken($userid) as $key=>$value) {
        if(@$value["type"] === $type) $i++;
    }
    return $i;
}
// 修改Token数据，默认为替换。替换必须带有 uuid 参数
function putUserToken($TokenData, $AddMode = false, $userid = "null"){
    global $RunPath;
    global $AllConfig;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    // if(isLoginS() !== true) return false;
    $UserTokenData = (array)array_values(@getUserToken($userid)?:[]);
    $TokenDataKey = 0;
    if($AddMode){
        // 更新Token
        if(!$AllConfig["login"]["allowmoretoken"]) $UserTokenData[0] = array_merge($UserTokenData[0], $TokenData);
        else{
            foreach ($UserTokenData as $key => $value) {
                if($value["uuid"] === $TokenData["uuid"]){
                    $TokenDataKey = $key;
                    $UserTokenData[(int)$key] = array_merge($value, $TokenData);
                }
            }
        }
    }else{
        // 删除无效Token，并添加
        if(!$AllConfig["login"]["allowmoretoken"]){
            @unlink("$RunPath/tmp/token/".$UserTokenData[0]["uuid"].".json");
            $UserTokenData[0] = $TokenData;
        }else{
            foreach ($UserTokenData as $key => $value) {
                if(time() - @$value["time"] >= $AllConfig["login"]["tokentime"] || !is_file("$RunPath/tmp/token/".@$value["uuid"].".json")){
                    @unlink("$RunPath/tmp/token/".@$value["uuid"].".json");
                    unset($UserTokenData[$key]);
                }
            }
            $TokenDataKey = (int)count($UserTokenData); // 避免出现莫名其妙的Bug
            if($TokenData) $UserTokenData[$TokenDataKey] = $TokenData;
        }
    }
    @mkdir("$RunPath/tmp/token");
    if(@$UserTokenData[$TokenDataKey]["uuid"]) file_put_contents("$RunPath/tmp/token/".$UserTokenData[$TokenDataKey]["uuid"].".json",json_encode($UserTokenData[$TokenDataKey],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    file_put_contents("$RunPath/data/user/$userid/token.json",json_encode($UserTokenData,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}
// 参数为空则取现登录账户 用户ID||UUID
function getUserData($userid = "null"){
    global $RunPath;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    // if(is_file("$RunPath/tmp/token/$userid.json")) return json_decode(file_get_contents("$RunPath/tmp/token/$userid.json"),true);
    // if(isLoginS() !== true) return false;
    return json_decode(file_get_contents("$RunPath/data/user/$userid/user.json"),true);
}
// 修改用户数据，默认为增加(修改)
function putUserData($UserData, $AddMode = true, $userid = "null"){
    global $RunPath;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    // if(isLoginS() !== true) return false;
    if($AddMode){
        $UserData = array_merge(getUserData($userid), $UserData);
    }
    file_put_contents("$RunPath/data/user/$userid/user.json",json_encode($UserData,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}
// 参数为空则取现登录账户 用户ID||UUID
function getUserConfig($userid = "null"){
    global $RunPath;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    // if(is_file("$RunPath/tmp/token/$userid.json")) return json_decode(file_get_contents("$RunPath/tmp/token/$userid.json"),true);
    // if(isLoginS() !== true) return false;
    return json_decode(file_get_contents("$RunPath/data/user/$userid/config.json"),true);
}
// 修改用户数据，默认为增加(修改)
function putUserConfig($UserConfig, $AddMode = true, $userid = "null"){
    global $RunPath;
    if($userid === "null"){
        global $NowUserID;
        $userid = $NowUserID;
    }
    if($userid == 0) return false;
    // if(isLoginS() !== true) return false;
    if($AddMode){
        $UserConfig = array_merge(getUserData($userid), $UserConfig);
    }
    file_put_contents("$RunPath/data/user/$userid/config.json",json_encode($UserConfig,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}
// 新建议
function newAdvice($AdviceData){
    global $RunPath;
    file_put_contents("$RunPath/data/advice/".$AdviceData["userid"]."_".date("Y_m_d_h_i_s").".json",json_encode($AdviceData,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
// 建议列表
function getAdvicesData($status = "on"){
    global $RunPath;
    $AdviceDatas = array();
    foreach (scandir("$RunPath/data/advice") as $Advice){
        if($Advice == "." || $Advice == "..") continue;
        $AdviceData = json_decode(file_get_contents("$RunPath/data/advice/$Advice"),true);
        if($status === "all" || $status === $AdviceData["status"]) array_push($AdviceDatas,$AdviceData);
    }
    return $AdviceDatas;
}
// 获取用户列表
function getUserIDList(){
    global $RunPath;
    $UserIDs = array();
    foreach (scandir("$RunPath/data/user") as $value){
        if($value == "." || $value == "..") continue;
        array_push($UserIDs,$value);
    }
    return $UserIDs;
}
// 获取BanIP列表
function getBanIPList(){
    global $RunPath;
    return array_filter(explode("\n", file_get_contents("$RunPath/data/banip.txt")));;
}



// 常规回执，统一使用方便后续加密
function ReturnData($data){
    global $AllConfig;
    if($AllConfig["AllInEncode"]){
        global $AES_API;
        $crypto = new AESCrypto($AES_API);
        echo $crypto->encrypt(json_encode($data,JSON_UNESCAPED_UNICODE));
    }else{
        header('Content-Type: application/json');
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// 生成一个UUID
function create_uuid() {
    $chars = md5(uniqid(mt_rand(), true));
    return substr($chars, 0, 8) . '-'.
    substr($chars, 8, 4) . '-'.
    substr($chars, 12, 4) . '-'.
    substr($chars, 16, 4) . '-'.
    substr($chars, 20, 12);
}

// 取用户IP
function GetClientIP() {
    global $AllConfig;
    $ip = false;
    if ($AllConfig["isCloudFlare"] && @$_SERVER["HTTP_CF_CONNECTING_IP"]) {
        return $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    if (!empty(@$_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty(@$_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}


// AES 加密
class AESCrypto {
    private $key;
    private $method = 'AES-256-CBC';
    public function __construct($key) {
        if (strlen($key) !== 32) {
            throw new Exception("Key must be 32 bytes for AES-256");
        }
        $this->key = $key;
    }
    public function encrypt($plaintext) {
        global $Debug;
        if($Debug) return $plaintext;
        $iv = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt(
            $plaintext,
            $this->method,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $result = $iv . $ciphertext;
        return base64_encode($result);
    }
    public function decrypt($ciphertext) {
        global $Debug;
        if($Debug) return $ciphertext;
        $data = base64_decode($ciphertext);
        if (strlen($data) < 16) {
            throw new Exception("Invalid ciphertext");
        }
        $iv = substr($data, 0, 16);
        $encrypted_data = substr($data, 16);
        return openssl_decrypt(
            $encrypted_data,
            $this->method,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}

?>