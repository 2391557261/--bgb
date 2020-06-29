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
	case "edit":
		$id=intval($_GET['id']);
		$Arr=getone("select * from guestbook   where id=$id");
		echo "
		<center>
		<div style='width:450px;height:130px;margin-top:20px;' align='left'>
		<form style='width:420px;height:130px;' action='admin.php?mod=book&act=save&id=$id' method='post'>
		<span class='myspan' style='width:80px;'>昵称：</span><input name='nickname' style='padding:0px;margin:0px;' value='{$Arr['nickname']}'><br>
		<span class='myspan' style='width:80px;'>标题：</span><input name='title' style='padding:0px;margin:0px;' value='{$Arr['title']}'><br>
		<span class='myspan' style='width:80px;'>留言内容：</span><input name='content' style='padding:0px;margin:0px;' value='{$Arr['content']}'><br>
		<span class='myspan' style='width:80px;'>回复：</span><textarea name='reply' style='padding:0px;margin:0px;width:300px;height:100px'>{$Arr['reply']}</textarea><br>	
		<center><input type='submit' value='保存' class='submit'></center></form>
		</div></center>";	
	break;
	
	case "save":
		$id=intval($_GET['id']);
		$query="update guestbook set
		nickname='{$_POST['nickname']}',   
		title='{$_POST['title']}',
		content='{$_POST['content']}',
		reply='{$_POST['reply']}' 
		where id=$id";
		if(query($query)){
			echo "<script>alert('编辑成功');location='admin.php?mod=book&act=list'</script>";
		}
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
