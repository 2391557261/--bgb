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