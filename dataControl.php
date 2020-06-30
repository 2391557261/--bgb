<?php
header("content-type:text/html;charset=utf-8");
$_GET['act']=isset($_GET['act'])?$_GET['act']:"list";
switch($_GET['act']){
	case "list":
	    ?>
        <script src="My97DatePicker/WdatePicker.js"></script>
        <?php
		echo "<form style='padding:0px;margin:0px;' action='admin.php?mod=data&act=list' method='post'>
		&nbsp;&nbsp;<span class='status'>销售统计</span>&nbsp;&nbsp;&nbsp;&nbsp;
		统计区间：<input name='start' value='{$_REQUEST['start']}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd'})\">~
		<input name='end' value='{$_REQUEST['end']}' onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd'})\">
		<input type='submit' value='搜索'>
		</form>"; 
		$ctArr=array();
		$query=getArr("select * from restaurant ");
		foreach($query as $ctrs){
		    $ctArr[$ctrs['id']]['name']=$ctrs['title'];
		}
		
		$fsql="";
		if(isset($_REQUEST['start'])&&$_REQUEST['start']){
			$start=strtotime($_REQUEST['start']." 00:00:00");
			$fsql.=" and ptime>=".$start;
		}
		
		if(isset($_REQUEST['end'])&&$_REQUEST['end']){
			$start=strtotime($_REQUEST['end']." 23:59:59");
			$fsql.=" and ptime<=".$start;
		}
		
		$row=query("select * from  orders where 1=1  $fsql order by id desc  ");

		
		echo "<table style='width:98%;margin-top:20px;' align='center' border='1'>
		<tr height='30' align='center'><td>餐厅</td><td>金额</td></tr>";	
		while($rs=fetch($row)){     
		   $goodsArr=explode(",",$rs['gid']);
		   $retrunStr="";
		   $zongjia=0;
		   foreach($goodsArr as $new){
			   $tempArr=explode(":",$new);			  
			   $Goods=getone("select * from goods where id={$tempArr[0]}");
			   $ctArr[$Goods['cid']]['zj']+=$Goods['price']*$tempArr[1];
		   }
		//print_r($ctArr);	     
		}
		
		
		foreach($ctArr as $new){
		echo "<tr style='height:30px;background-color:#fff'>
		 <td>{$new['name']}</td>
		 <td align='center'>".intval($new['zj'])." 元</td>
		 </tr>";		 
		}
		echo "</table>";
	break;
}
?>