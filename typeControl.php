<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "add":
		?>
		<script>
		function check(){
		if(!$('input[name=title]').val()){alert('餐厅名称不能为空');$('input[name=title]').focus();return false;}
		}
		</script>
		<center>
		<div style='width:320px;height:230px;margin-top:20px;' align='left'>
		<form action='admin.php?mod=type&act=save' method='post'  onsubmit='return check()' class='myform'>
		<span class='myspan' style='width:80px;'>餐厅名称：</span><input name='title' style='width:160px;'><br>
		<span class='myspan' style='width:80px;'>序号：</span><input name='sort' style='width:60px;'><br>
		<center><input type='submit' class='submit'value='Save'></center></form>
		</div></center>
		<?php
	break;
	
	case "edit":
		$id=intval($_GET['id']);
		$Arr=getone("select * from  restaurant  where id=$id"); 
		echo "
		<script>
		function check(){
			if(!$('input[name=title]').val()){alert('餐厅名称不能为空');$('input[name=title]').focus();return false;}
		}
		</script>
		<center>
		<div style='width:320px;height:230px;margin-top:20px;' align='left'>
		<form action='admin.php?mod=type&act=save&id=$id' method='post'  onsubmit='return check()' class='myform' enctype='multipart/form-data' >
		<span class='myspan' style='width:80px;'>餐厅名称：</span><input name='title' style='width:160px;' value='{$Arr['title']}'><br>
		<span class='myspan' style='width:80px;'>序号：</span><input name='sort' style='width:160px;' value='{$Arr['sort']}'><br>
		<br>  
		<center><input type='submit' class='submit' value='保存'></center></form>
		</div></center>";
	break;
	
	case "save":
		$id=intval($_GET['id']);
		if($id){
			$query="update restaurant set title='{$_POST['title']}',sort='{$_POST['sort']}' where id=$id";
			if(isset($_POST['title'])&&query($query)){
				echo "<script>alert('编辑成功');location='admin.php?mod=type&act=list'</script>";
			}
		}
		else{
			$query="insert into restaurant set 
			title='{$_POST['title']}',
			sort='{$_POST['sort']}'";
			if(isset($_POST['title'])&&query($query))
			echo "<script>alert('新增成功');location='admin.php?mod=type&act=list'</script>";
		}	
	break;
	
	case "list":
		echo "<form style='padding:0px;margin:0px;' action='admin.php?mod=type&act=list' method='post'>
		<span class='status'>&nbsp;&nbsp;餐厅名称</span>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name='title' value='{$_REQUEST['title']}' style='padding:0px;margin:0px;'>
		<input type='submit' value='搜索'>   <input type='button' onclick=\"location='admin.php?mod=type&act=add'\" value='新增'></form>";   
		$fsql="";$fpage="";
		if(isset($_REQUEST['title'])&&$_REQUEST['title']){$fsql.=" and title like '%{$_REQUEST['title']}%'";$fpage="&title={$_REQUEST['title']}";}
 		$countsql="select count(*) from restaurant where 1=1 $fsql";
		$pagesql="select * from restaurant where 1=1 $fsql order by sort desc,id desc";
		$bottom="?mod=type&ac=list";
		$datasql=page($countsql,$pagesql,$bottom,15);
		echo "<form name='delform' id='delform' action='?mod=type&act=alldel' method='post' class='margin0'>
		<table style='width:98%;' align='center' border='1'>
		<tr   height='30' align='center'>
		<td>名称</td><td>序号</td><td>管理</td></tr>";
		if($datasql){
			while($rs=fetch($datasql[1])){
				echo "<tr height='20'>
				<td align='left'><input   type=checkbox value='{$rs['id']}'  name='allidd[]' id='allidd'>{$rs['title']}</td>
				<td align='center'>{$rs['sort']}</td> 
				<td align='center'>		  
				<a href='admin.php?mod=type&act=edit&id={$rs['id']}'>编辑</a>  &nbsp; &nbsp;
				<a href='javascript:if(confirm(\"您确定要删除该餐厅吗?\")) location=\"admin.php?mod=type&act=alldel&id={$rs['id']}\" '>删除</a>  &nbsp; &nbsp; 
				</td>
				</tr>";
			}
			echo "<tr><td colspan=3 align='right'>
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
			query("delete from restaurant where id=$id");
			query("delete from goods where cid=$id");
	    }	
		echo "<script>alert('删除成功!');location='admin.php?mod=type&act=list'</script>";
	break;
}
?>