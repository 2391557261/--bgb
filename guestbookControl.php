<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "list":
 		$countsql="select count(*) from guestbook ";
		$pagesql="select * from guestbook  order by id";
		$bottom="?mod=guestbook&act=list";
		$datasql=page($countsql,$pagesql,$bottom,15);
        echo "<table style='width:98%;margin-top:5px;' align='center' class='noborder' border=1>";
		if($datasql){
			 $js=1;
			 while($rs=fetch($datasql[1])){
				 echo "<tr>
				 <td align='left' style='padding:10px;font-size:16px;background-color:#eee' >
				 <div style='font-weight:bold'>{$rs['nickname']} [".date("Y-m-d H:i:s",$rs['ptime'])."] 说：{$rs['title']}</div>
				 <div style='margin:10px;font-size:13px;'>{$rs['content']}</div>";
				 if($rs['reply']){
					 echo "<div style='border:#333 dashed 1px;padding:10px; margin:10px 10px 10px 100px;'>{$rs['reply']}</div>";
				 }
				 echo"
				 </td><tr>";
			 }
				echo "<tr><td colspan=5 align='right'>
						 <div style='width:280px;float:left'></div>
						 <div  style='float:right'>{$datasql[2]}</div>
						 <div style='clear:both;'></div>
				  </td></tr>";
		}
		echo "</table> 
	    <div style='margin:20px;'>
	    <b>请您留言：</b>
	    <form action='?mod=guestbook&act=comment' method='post'>
	    昵称：<input name='nickname'><br>
		标题：<input name='title'  style='width:300px;'><br>
	    内容：<textarea style='width:300px;height:100px;' name='content'></textarea>
		<input type='submit' value='提交' class='submit'>
	    </form>
	    </div>";
	break;
	
	case "comment":
		$id=intval($_GET['id']);
		$userid=isset($_SESSION['userid'])?$_SESSION['userid']:0;
		if(!$_POST['content']){echo "<script>alert('留言内容不能为空！');location='index.php?mod=guestbook&act=list'</script>";die();}
		$query="insert into guestbook set 
		title='{$_POST['title']}',
		content='{$_POST['content']}',
		nickname='{$_POST['nickname']}',
		uid='$userid',
		ptime='".time()."'
		";
		if(query($query)){
			echo "<script>alert('留言成功！');location='index.php?mod=guestbook&act=list'</script>";
			die();
		}
		else die($query);
	break;
}
?>