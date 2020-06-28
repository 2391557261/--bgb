<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "list":
		echo "<form style='padding:0px;margin:0px;' action='admin.php?mod=book&act=list' method='post'>
		<span class='status'>&nbsp;&nbsp;留言管理</span>&nbsp;&nbsp;&nbsp;&nbsp;
		</form>";   
		$fsql="";$fpage="";		
 		$countsql="select count(*) from guestbook where 1=1 $fsql";
		$pagesql="select * from guestbook where 1=1 $fsql order by id desc";
		$bottom="?mod=book&ac=list";
		$datasql=page($countsql,$pagesql,$bottom,15);
		echo "<form name='delform' id='delform' action='?mod=book&act=alldel' method='post' class='margin0'>
		<table style='width:98%;' align='center' border='1'>
		<tr height='30' align='center'>
		<td>昵称</td>
		<td>标题</td>
		<td>内容</td>
		<td>时间</td>
		<td>管理</td>
		</tr>";
		if($datasql){
			while($rs=fetch($datasql[1])){
				echo "<tr height='20' style='background-color:#fff'>
				<td align='left'><input   type=checkbox value='{$rs['id']}'  name='allidd[]' id='allidd'>{$rs['nickname']}</td>
				<td align='left'>{$rs['title']}</td>
				<td align='left'>{$rs['content']}</td>
				<td align='center'>".date("Y-m-d H:i:s",$rs['ptime'])."</td>
				<td align='center'>		  
				<a href='admin.php?mod=book&act=edit&id={$rs['id']}'>编辑</a>  &nbsp; &nbsp;
				<a href='javascript:if(confirm(\"您确定要删除吗?\")) location=\"admin.php?mod=book&act=alldel&id={$rs['id']}\" '>删除</a>
				</td>
				</tr>";	
			}
			echo "<tr><td colspan=5 align='right'>
					 <div style='width:280px;float:left'>{$datasql['pl']}{$datasql['pldelete']}</div>
					 <div  style='float:right'>{$datasql[2]}</div>
					 <div style='clear:both;'></div>
			  </td></tr>";
		}
		echo "</table></form>";		
	break;
	
	
	case "alldel":
		$key=isset($_POST["allidd"])&&$_POST["allidd"]?$_POST["allidd"]:array(intval($_GET['id']));
		for($i=0;$i<count($key);$i++){ 
			$id=$key[$i];
			query("delete from guestbook where id=$id");
		}
	    echo "<script>alert('删除成功');location='admin.php?mod=book&act=list'</script>";
	break;
}
?>