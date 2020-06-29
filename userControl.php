<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "list":
		echo "<form style='padding:0px;margin:0px;' action='admin.php?mod=user&act=list' method='post'>
		<span class='status'>&nbsp;&nbsp;会员名称&nbsp;&nbsp;&nbsp;&nbsp;</span><input name='username' value='{$_REQUEST['username']}' style='padding:0px;margin:0px;'>
		<input type='submit' value='搜索'> <input type='button' onclick='location=\"admin.php?mod=user&act=reg\"' value='注册'>
		</form>";   
		$fsql="";$fpage="";
		if(isset($_REQUEST['username'])&&$_REQUEST['username']){
			$fsql.=" and username like '%{$_REQUEST['username']}%'";
			$fpage="&username={$_REQUEST['username']}";
		}  
		$countsql="select count(*) from user where 1=1 $fsql";
		$pagesql="select * from user where 1=1 $fsql order by tag desc";
		$bottom="?action=list";
		$datasql=page($countsql,$pagesql,$bottom,15);
		echo "<form name='delform' id='delform' action='?mod=user&act=alldel' method='post' class='margin0'>
		<table style='width:98%;' align='center' border='1'>
		<tr  height='30' align='center'><td>账号</td><td>类型</td><td>管理</td></tr>";
		if($datasql){
			while($rs=fetch($datasql[1])){
			  echo "<tr height='20'  bgcolor='#FFFFFF'>
			  <td align='left'><input   type=checkbox value='{$rs['id']}'  name='allidd[]' id='allidd'>{$rs['username']}</td>
			  <td align='center'>";switch($rs['tag']){case 8:echo "管理员";break;case 6:echo "会员";break;}echo"</td>
			  <td align='center'>		  
				<a href='admin.php?mod=user&act=edit&id={$rs['id']}'>编辑</a>  &nbsp; &nbsp;
				<a href='javascript: if(confirm(\"您确定要删除该会员吗？\")) location=\"admin.php?mod=user&act=alldel&id={$rs['id']}\" '>删除</a> 
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
	
	case "reg":
	    ?>
		<script>
		function check(){
		  if(!$('input[name=username]').val()){alert('会员名称不能为空');$('input[name=username]').focus();return false;}
		  if(!$('input[name=password]').val()){alert('密码不能为空');$('input[name=password]').focus();return false;}
		}
		</script>
		<center>
		<div style='width:420px;height:230px;margin-top:20px;' align='left'>
		<form action='?act=save' method='post'  onsubmit='return check()' class='myform'>
		账号：<input name='username' style='width:160px;'><br>
		密码：<input name='password' type='password' style='width:160px;'><br> 
		年龄：<input name='age' style='width:160px;'><br>
		性别：<input name='gender' style='width:160px;'><br> 
		类型：<select name='tag'><option value=8>管理员</option><option value=6>会员</option></select><br> 
		<center><input type='submit' value='注册' class='submit'></center></form>
		</div></center>
		<?php
	break;
	  	  
	case "edit":
		$id=intval($_GET['id']);
		$Arr=getone("select * from user where id=$id");
		echo "
		<script>
		function check(){
		  if(!$('input[name=username]').val()){alert('会员名称不能为空');$('input[name=username]').focus();return false;}	   
		}
		</script>
		<center>
		<div style='width:420px;height:230px;margin-top:20px;' align='left'>
		<form action='?act=save&id=$id' method='post' class='myform' onsubmit='return check()'>
		账号：<input name='username' value='{$Arr['username']}' style='width:160px;'><br>
		密码：<input name='password' type='password' style='width:160px;'><br> 
		年龄：<input name='age' value='{$Arr['age']}' style='width:160px;'><br>
		性别：<input name='gender'  value='{$Arr['gender']}' style='width:160px;'><br> 
		
		类型：<select name='tag'>
		<option value=8>管理员</option>
		<option value=6 ";if($Arr['tag']==6)echo" selected";echo">会员</option>
		</select>
		<center><input type='submit' value='保存' class='submit'></center></form>
		</div></center>";
	break;

	case "save":
		$id=intval($_GET['id']);
		if($id){
			$find=getone("select id from user where username='{$_POST['username']}' and id!=$id");
			if($find['id']){
				echo "<script>alert('该会员已存在!');location='admin.php?mod=user'</script>";
				die();
			}
			if($_POST['password']) {$fsql=",password='".md5($_POST['password'])."'";}
			else $fsql="";
			$query="update user set
			username='{$_POST['username']}',
			age='{$_POST['age']}',
			gender='{$_POST['gender']}', 
			tag={$_POST['tag']}  $fsql
			where id={$id}";
			if(isset($_POST['username'])&&query($query))
			echo "<script>alert('编辑成功!');location='?act=list'</script>";
		}
		else{
			$find=getone("select id from user where username='{$_POST['username']}'");
			if($find['id']){
				echo "<script>alert('该会员已存在!');location='?act=reg'</script>";
				die();
			}
			$query="insert into  user set
			username='{$_POST['username']}',
			password='".md5($_POST['password'])."',
			age='{$_POST['age']}',
			gender='{$_POST['gender']}', 
			tag={$_POST['tag']}";
			if(isset($_POST['username'])&&query($query))
			echo "<script>alert('注册成功!');location='?act=list'</script>";
		}
	break;
		
	case "alldel":
	  	$key=isset($_POST["allidd"])&&$_POST["allidd"]?$_POST["allidd"]:array(intval($_GET['id']));
	    for($i=0;$i<count($key);$i++){ 
		    $id=$key[$i];
		    $find=getone("select tag from user where id=$id");
			if($find['tag']>=8){
				$find=getone("select id from user where id!=$id and tag=8");
				if(!$find['id']){
					echo "<script>alert('您至少要保留一个管理员账号');location='?act=list'</script>"; 
					die();
				}
			}
			query("delete from user where id=$id");
	    }	
		echo "<script>alert('删除成功!');location='?act=list'</script>";
	break;
}
?>
