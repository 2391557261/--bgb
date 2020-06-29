<?php
header("content-type:text/html;charset=utf-8");	
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "list":
		echo"
		<center><form action='index.php?mod=goods&act=list' method='post' style='margin-top:5px;'>
		<input name='search' style='width:300px;height:20px;font-size:20px;' value='{$_REQUEST['search']}'>
		<input type='submit' value='搜索' style='width:60px;height:25px;'>
		</form> </center>";
		$fsql=$psql="";
		if(isset($_GET['cid'])&&$_GET['cid']){$fsql.=" and cid='{$_GET['cid']}'";$psql="&cid={$_GET['cid']}";}
		if(isset($_POST['search'])&&$_POST['search']){$fsql.=" and title like '%{$_POST['search']}%'";}		
		$countsql="select count(*) from goods where 1=1 $fsql ";
		$pagesql="select * from goods where 1=1 $fsql order by id {$_GET['by']}";
		$bottom="index.php?mod=goods&act=list{$psql}";
		$datasql=page($countsql,$pagesql,$bottom,15);
		echo "<table style='width:98%;margin-top:5px;' align='center' class='noborder'><tr>";
		if($datasql){
			$js=1;
			while($rs=fetch($datasql[1])){
				echo "<td align='center' style='padding:10px;'>
				<div style='border:solid 1px #ccc;width:226px;'>
				<a href='index.php?mod=goods&act=show&id={$rs['id']}'><img src='{$rs['picurl']}' style='border:solid 3px #fff;width:220px;height:180px;'></a>
				<div style=' background-color:#BF9875; padding:2px;'>
				  <div style='margin:5px;font-weight:bold;width:215px' align='left'>{$rs['title']}</div>
				  <div style='margin:5px;font-weight:bold;width:215px;font-size:18px;' align='left'>{$rs['price']} ￥</div>
				</div>
				</div>
				
				</td>";
				if($js%3==0)echo "</tr><tr>";
				$js++;
		    }
		    echo "<tr><td colspan=5 align='right'>
				   <div style='width:280px;float:left'></div>
				   <div  style='float:right'>{$datasql[2]}</div>
				   <div style='clear:both;'></div>
			</td></tr>";
		}
		echo "</table>";
	break;
	
		
}
?>