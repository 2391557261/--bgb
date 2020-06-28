<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "add":
		?>
		<script>
		function check(){
		if(!$('input[name=title]').val()){alert('菜品名称不能为空');$('input[name=title]').focus();return false;}
		if(!$('input[name=upload]').val()){alert('菜品图片不能为空');$('input[name=upload]').focus();return false;}
		}
		</script>
		<center>
		<div style='width:320px;height:230px;margin-top:20px;' align='left'>
		<form action='admin.php?mod=goods&act=save' method='post'  onsubmit='return check()' class='myform' enctype='multipart/form-data' >
		<span class='myspan' style='width:80px;'>所属餐厅：</span><select name='cid' style='width:160px;'>
		<?php
		$classQuery=getArr("select * from restaurant order by sort desc");
		foreach($classQuery as $classArr){
		    echo "<option value='{$classArr['id']}'>{$classArr['title']}</option>";
		}
		?>
		</select><br>
		<span class='myspan' style='width:80px;'>菜品名称：</span><input name='title' style='width:160px;'><br>
		<span class='myspan' style='width:80px;'>价格：</span><input name='price' style='width:60px;'><br>
		<span class='myspan' style='width:80px;'>上传图片：</span><input type='file' style='width:220px;' id='upload' name='upload'><br>
		<span class='myspan' style='width:80px;'>菜品简介：</span><textarea  name='content' style='width:220px;height:60px;'></textarea><br>  
		<center><input type='submit' class='submit'value='保存'></center></form>
		</div></center>
	    <?php
	break;
	
	case "edit":
		$id=intval($_GET['id']);
		$Arr=getone("select * from  goods  where id=$id"); 
		echo "
		<script>
		function check(){
			if(!$('input[name=title]').val()){alert('菜品名称不能为空');$('input[name=title]').focus();return false;}
		}
		</script>
		<center>
		<div style='width:320px;height:230px;margin-top:20px;' align='left'>
		<form action='admin.php?mod=goods&act=save&id=$id' method='post'  onsubmit='return check()' class='myform' enctype='multipart/form-data' >
		<span class='myspan' style='width:80px;'>所属餐厅：</span><select name='cid' style='width:160px;'>";
		
		$classQuery=getArr("select * from restaurant  order by sort desc");
		foreach($classQuery as $classArr){
			echo "<option value='{$classArr['id']}' ".contrast($classArr['id'],$Arr['cid']).">{$classArr['title']}</option>";
		}
		
		echo"</select><br>
		<span class='myspan' style='width:80px;'>菜品名称：</span><input name='title' style='width:160px;' value='{$Arr['title']}'><br>
		<span class='myspan' style='width:80px;'>价格：</span><input name='price' style='width:160px;' value='{$Arr['price']}'><br>
		<span class='myspan' style='width:80px;'>上传图片：</span><input type='file' style='width:220px;' id='upload' name='upload'><br>
		<span class='myspan' style='width:80px;'>菜品简介：</span><textarea  name='content' style='width:220px;height:60px;'>{$Arr['content']}</textarea><br>  
		<br>  
		<center><input type='submit' class='submit' value='保存'></center></form>
		</div></center>";
	break;
	
	case "save":
		$id=intval($_GET['id']);
		if($id){
			$exname=strtolower(substr($_FILES['upload']['name'],(strrpos($_FILES['upload']['name'],'.')+1)));
			$uploadfile = upfilename($exname);
			$exetxt=array("jpg","gif","png");
			if (!in_array ($exname,array("exe","php","js","asp","aspx","jsp","html","htm"),true)&&in_array ($exname,$exetxt,true)&&$_FILES['upload']['size']>0&&move_uploaded_file($_FILES['upload']['tmp_name'],$uploadfile))
			$_POST['picurl']=$uploadfile; 
			if($_POST['picurl'])$fsql="picurl='{$_POST['picurl']}',";else $fsql="";
			//
			$query="update goods set cid='{$_POST['cid']}',title='{$_POST['title']}',price='{$_POST['price']}', $fsql content='{$_POST['content']}' where id=$id";
			if(isset($_POST['title'])&&query($query)){
				echo "<script>alert('编辑成功！');location='admin.php?mod=goods&act=list'</script>";
			}
		}
		else{
			//
			$exname=strtolower(substr($_FILES['upload']['name'],(strrpos($_FILES['upload']['name'],'.')+1)));
			$uploadfile = upfilename($exname);
			$exetxt=array("jpg","gif","png");
			if (!in_array ($exname,array("exe","php","js","asp","aspx","jsp","html","htm"),true)&&in_array ($exname,$exetxt,true)&&$_FILES['upload']['size']>0&&move_uploaded_file($_FILES['upload']['tmp_name'],$uploadfile))
			$_POST['picurl']=$uploadfile; 
			//
			$query="insert into goods set 
			cid='{$_POST['cid']}',
			title='{$_POST['title']}',
			picurl='{$_POST['picurl']}',
			price='{$_POST['price']}',
			content='{$_POST['content']}', 
			ptime=".time();
			if(isset($_POST['title'])&&query($query))
			echo "<script>alert('新增成功');location='admin.php?mod=goods&act=list'</script>";
		}	
	break;
	
	case "list":
		echo "<form style='padding:0px;margin:0px;' action='admin.php?mod=goods&act=list' method='post'>
		<span class='status'>&nbsp;&nbsp;菜品管理</span>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name='title' value='{$_REQUEST['title']}' style='padding:0px;margin:0px;'>
		<input type='submit' value='搜索'>   <input type='button' onclick=\"location='admin.php?mod=goods&act=add'\" value='新增'></form>";   
		$fsql="";$fpage="";
		if(isset($_REQUEST['title'])&&$_REQUEST['title']){$fsql.=" and title like '%{$_REQUEST['title']}%'";$fpage="&title={$_REQUEST['title']}";}
		if(isset($_REQUEST['cid'])&&$_REQUEST['cid']){$fsql.=" and cid like '%{$_REQUEST['cid']}%'";$fpage="&cid={$_REQUEST['cid']}";}
		
		$countsql="select count(*) from goods where 1=1 $fsql";
		$pagesql="select * from goods where 1=1 $fsql order by id desc";
		$bottom="?mod=goods&ac=list";
		$datasql=page($countsql,$pagesql,$bottom,15);
		echo "<form name='delform' id='delform' action='?mod=user&act=alldel' method='post' class='margin0'>
		<table style='width:98%;' align='center' border='1'>
		<tr   height='30' align='center'>
		<td>餐厅</td><td>名称</td><td>价格</td><td>图片</td><td>简介</td><td>发布时间</td><td>管理</td></tr>";
		if($datasql){
			while($rs=fetch($datasql[1])){
				$C=@getone("select * from restaurant where id='{$rs['cid']}'");
			    echo "<tr height='20'  style='background-color:#fff'>
				<td align='left'><input   type=checkbox value='{$rs['id']}'  name='allidd[]' id='allidd'>{$C['title']}</td>
				<td align='left'>{$rs['title']}</td>
				<td align='center'>{$rs['price']}</td> 
				<td align='center'><a href='{$rs['picurl']}' target='_blank'>点击查看</a></td>
				<td align='center'>{$rs['content']}</td>
				<td align='center'>".date("Y-m-d H:i:s",$rs['ptime'])."</td>
				<td align='center'>		  
				  <a href='admin.php?mod=goods&act=edit&id={$rs['id']}'>编辑</a>  &nbsp; &nbsp;
				  <a href='javascript:if(confirm(\"您确定要删除该菜品吗? \")) location=\"admin.php?mod=goods&act=alldel&id={$rs['id']}\" '>删除</a> 
				</td>
				</tr>";
			}
			echo "<tr><td colspan=7 align='right'>
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
			query("delete from goods where id=$id");
	 	}	
		echo "<script>alert('删除成功!');location=admin.php'?mod=goods&act=list'</script>";
	break;
}

?>
