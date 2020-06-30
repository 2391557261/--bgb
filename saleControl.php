<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "list":
	?>
    <div style=" border:solid 1px #033; margin:40px auto;width:90%; line-height:4; font-weight:bold; padding:10px;">
    地址：**省**市***** <br>
    邮编：888888<br>
    投诉电话：010-1000000<br>
    销售总监：刘某某   手机：13888888888<br>
    在线投诉：<a href='index.php?mod=guestbook&act=list' target="_blank">点此</a><br>
    </div>
    <?php
	break;
}
?>