<?php
header("content-type:text/html;charset=utf-8");
$_GET['act']=isset($_GET['act'])?$_GET['act']:"login";
switch($_GET['act']){
	case "login":
		?>
		<script>
		function checkForm(){
		  if(!$('input[name=username]').val()){alert('账号不能为空');$('input[name=username]').focus();return false;}
		  if(!$('input[name=password]').val()){alert('密码不能为空');$('input[name=password]').focus();return false;}
		}
		</script>    
		<div style='margin:auto;width:300px;margin-top:50px;' align='left'>
		<b>请先登录</b>
		<form class='myform' action='index.php?mod=user&act=logingo' method='post' onsubmit='return checkForm()' style='width:350px;'>
		<span class='myspan' style='width:120px;'>会员账号：</span><input name='username' style='width:150px;'><br><br>
		<span class='myspan' style='width:120px;'>登录密码：</span><input name='password' type='password' style='width:150px;'><br><br>
		<center> <input type='submit' value='登录' class='submit'> <input type='button'  onclick="location='index.php?mod=user&act=reg'" value='注册' class='submit'> </center>
		</form>
		</div>
		<?php
	break;
	
	case "logingo":
		$row=getone("select * from user where username='{$_POST['username']}' and password='".md5($_POST['password'])."'");
		if($row['id']){
		  if($row['tag']==6){
			$_SESSION['username']=$row['username'];
			$_SESSION['userid']=$row['id'];
			$_SESSION['password']=$row['password'];
			$_SESSION['goods']=array();
			echo "<script>location='index.php?mod=goods&act=list'</script>";
		  }
		  elseif($row['tag']==8){
			$_SESSION['adminname']=$row['username'];
			$_SESSION['adminid']=$row['id'];
			$_SESSION['adminpassword']=$row['password'];
			$_SESSION['goods']=array();
			echo "<script>location='admin.php'</script>";
		  }
		}
		else {echo "<script>alert('用户名或密码错误！');location='index.php?mod=user&act=login'</script>";}	
	break;
	
	case "out":
		if(isset($_SESSION['username'])){
			$_SESSION['username']="";
			$_SESSION['userid']="";
			$_SESSION['password']="";
			unset($_SESSION['username'],$_SESSION['userid'],$_SESSION['password']);
			echo "<script>location='index.php?mod=user&act=login'</script>";
		}
		if(isset($_SESSION['adminname'])){
			$_SESSION['adminname']="";
			$_SESSION['adminid']="";
			$_SESSION['adminpassword']="";
			unset($_SESSION['username'],$_SESSION['userid'],$_SESSION['password']);
			echo "<script>location='index.php?mod=user&act=login'</script>";
		}
	break;
	
	case "edit":
		checkLoginStatus();
		?>
		<script>
		function checkForm(){
		  if(!$('input[name=oldpassword]').val()){alert('原密码不能为空');$('input[name=oldpassword]').focus();return false;}
		  if(!$('input[name=password]').val()){alert('新密码不能为空');$('input[name=password]').focus();return false;}
		}
		</script>
		<div style='width:430px;height:230px;margin-top:20px;margin:120px auto;' align='left'>
		<form class='myform' action='index.php?mod=user&act=editgo' method='post' onsubmit='return checkForm()' >
		<span class='myspan' style='width:70px;'>原密码：</span><input name='oldpassword'   style='width:150px;'><br><br>
		<span class='myspan' style='width:70px;'>新密码：</span><input name='password' type='password' style='width:150px;'><br><br>
		<center> <input type='submit' value='保存' class='submit'></center>
		</form>
		</div>
		<?php
	break;
	
    case "editgo":
		checkLoginStatus();
		$U=getone("select * from user where id='{$_SESSION['userid']}'"); 
		if($U['password']!=md5($_POST['oldpassword'])){
		   echo "<script>alert('原密码不正确');location='?mod=user&act=edit'</script>";
		   die();
		}
		else if(query("update user set password='".md5($_POST['password'])."' where id='{$_SESSION['userid']}'")){
		   echo "<script>alert('编辑成功');location='?mod=user&act=edit'</script>";
		   die();
		}
	break;
	
	case "reg":
		?>
		<script>
		function checkForm(){
		  if(!$('input[name=username]').val()){alert('用户名不能为空');$('input[name=username]').focus();return false;}
		  if(!$('input[name=password]').val()){alert('密码不能为空');$('input[name=password]').focus();return false;}
		}
		</script>
		<div style='width:430px;height:230px;margin-top:20px;margin:120px auto;' align='left'>
		<form class='myform' action='index.php?mod=user&act=save' method='post' onsubmit='return checkForm()' >
		<span class='myspan' style='width:70px;'>用户名：</span><input name='username'   style='width:150px;'><br><br>
		<span class='myspan' style='width:70px;'>密码：</span><input name='password' type='password' style='width:150px;'><br><br>
		<span class='myspan' style='width:70px;'>年龄：</span><input name='age'   style='width:150px;'><br><br>
		<span class='myspan' style='width:70px;'>性别：</span><select name='gender'   style='width:150px;'><option>男</option><option>女</option></select><br><br>
		
		<center> <input type='submit' value='注册' class='submit'></center>
		</form>
		</div>
		<?php
	break;
	
	case "save":
		$finde=@getone("select * from user where username='{$_POST['username']}'");
		if($finde['id']){
		 echo "<script>alert('该用户名已存在，请重新注册');location='index.php?mod=user&act=reg'</script>";
		 die();
		}
		if(query("insert into  user set 
		username='{$_POST['username']}',
		password='".md5($_POST['password'])."',
		gender='{$_POST['gender']}',
		age='{$_POST['age']}',
		tag='6'
		"));
		echo "<script>alert('注册成功，请登录！');location='index.php?mod=user&act=login'</script>";
	break;	
}
?>