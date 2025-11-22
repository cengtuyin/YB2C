<?php

// 为网页
$isWeb = true;
// 仅权限组 admin 的账号可访问
$PagePermission = "admin";
include_once("../config.php");

$onData = getAdvicesData("on");
$offData = getAdvicesData("off");
?>
<style>
.tab-pane { display: none; }
.tab-pane.active { display: block; }
.tab-button.active { 
    color: #667eea !important; 
    border-bottom: 2px solid #667eea !important;
}
.chip {
    text-align:center;
    padding: 4px 8px 4px 8px;
    border-radius: 8px;
    color: #DFDFD6;
    border-style: solid;
    border-width: 1px;
    border-color: #DFDFD6;
}
</style>

<div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;">
    <ul style="display:flex;margin:0;padding:0;list-style:none;">
        <li>
            <button class="tab-button active" data-tab="0" style="padding:12px 28px;background:transparent;border:none;color:#667eea;cursor:pointer;border-bottom:2px solid #667eea;">
                待处理 ( <?php echo count($onData) ?> )
            </button>
        </li>
        <li>
            <button class="tab-button" data-tab="1" style="padding:12px 28px;background:transparent;border:none;color:#98989F;cursor:pointer;border-bottom:2px solid transparent;">
                已处理 ( <?php echo count($offData) ?> )
            </button>
        </li>
    </ul>
</div>

<div class="tab-pane active">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>反馈列表</span>
        </div>
        <div style="display:flex;width:100%;">
            <table>
                <tr class="TableThree"><th>邮箱</th><th class="TableDisplayNone">时间</th><th>描述</th><th>操作</th></tr>
                <?php if(count($onData) === 0): ?>
                    <tr><td colspan="4" style="text-align:center;">无数据</td></tr>
                <?php else: ?>
                    <?php foreach ($onData as $AdviceData): $UserData = getUserData($AdviceData["userid"]); ?>
                        <tr class="TableThree">
                            <td><?php echo $UserData["mail"] ?></td>
                            <td class="TableDisplayNone"><?php echo $AdviceData["time"] ?></td>
                            <td><?php
                                    $tag = array();
                                    foreach (array("优化","建议","修复","修改","增加","添加") as $value) {
                                        if(strstr($AdviceData["content"],$value) !== false && !in_array($value,$tag)){
                                            $tag[] = $value;
                                            echo "<span class='chip'>$value</span>";
                                        }
                                    }
                                    if($tag === []) echo "<span class='chip'>null</span>";
                                ?></td>
                            <td><button class="Main_Button_NH">查看</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<div class="tab-pane">
    <div class="Main_Cord Header_NameView" style="width:100%;height:auto;margin-bottom: 18px;display:block;place-items: flex-start;">
        <div style="width:100%;display: flex;place-items: center;border-bottom: 2px dashed #32363F;">
            <img src="./favicon.ico" />
            <span>反馈列表</span>
        </div>
        <div style="display:flex;width:100%;">
            <table>
                <tr class="TableThree"><th>邮箱</th><th class="TableDisplayNone">时间</th><th>描述</th><th>操作</th></tr>
                <?php if(count($offData) === 0): ?>
                    <tr><td colspan="4" style="text-align:center;">无数据</td></tr>
                <?php else: ?>
                    <?php foreach ($offData as $AdviceData): $UserData = getUserData($AdviceData["userid"]); ?>
                        <tr class="TableThree">
                            <td><?php echo $UserData["mail"] ?></td>
                            <td class="TableDisplayNone"><?php echo $AdviceData["time"] ?></td>
                            <td><?php
                                    $tag = array();
                                    foreach (array("优化","建议","修复","修改","增加","添加") as $value) {
                                        if(strstr($AdviceData["content"],$value) !== false && !in_array($value,$tag)){
                                            $tag[] = $value;
                                            echo "<span class='chip'>$value</span>";
                                        }
                                    }
                                    if($tag === []) echo "<span class='chip'>null</span>";
                                ?></td>
                            <td><button class="Main_Button_NH">查看</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>