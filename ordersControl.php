<?php
$_GET['act']=isset($_GET['act'])?$_GET['act']:"add";
switch($_GET['act']){
	case "add":
		$query=query("insert into orders set 
		title='{$_POST['title']}',
		gid='{$_POST['gid']}',
		uid='{$_SESSION['userid']}',
		paymoney='{$_POST['paymoney']}',
		sname='{$_POST['sname']}',
		saddress='{$_POST['saddress']}',
		spay='{$_POST['spay']}',
		sbz='{$_POST['sbz']}',
		status=1, 
		ptime=".time()
		);
		$_SESSION['goods']=array();
		echo "<script>alert('订单提交成功!');location='index.php?mod=orders&act=list'</script>";
	break;
	
	case "list":
		$countsql="select count(*) from orders where uid='{$_SESSION['userid']}' ";
		$pagesql="select * from orders where uid='{$_SESSION['userid']}' order by id desc ";
		$bottom="index.php?mod=orders&act=list";
		$datasql=page($countsql,$pagesql,$bottom,15);
		echo "
		<table style='width:98%;' align='center' border='1'>
		<tr  bgcolor='#eeeeee' height='30' align='center'>
			<td>订单号</td>
			<td>详情</td>
			<td>总价</td>
			<td>状态</td>
			<td>订单时间</td>
		</tr>";
		if($datasql){
			while($rs=fetch($datasql[1])){
				 $goodsArr=explode(",",$rs['gid']);
				 $retrunStr="";
				 $zongjia=0;
				 foreach($goodsArr as $new){
					 $tempArr=explode(":",$new);
					 $Goods=getone("select * from goods where id={$tempArr[0]}");
					 if($Goods['cid'])$C=getone("select title from restaurant  where id={$Goods['cid']}");
					 $retrunStr.="{$C['title']}：{$Goods['title']},单价：{$Goods['price']},数量：{$tempArr[1]},总价：";
					 $retrunStr.=$Goods['price']*$tempArr[1];
					 $retrunStr.=" 元<br>";
					 $zongjia+=$Goods['price']*$tempArr[1];
				 }	     
				 echo "<tr height='20'>
				  <td align='center'>{$rs['title']}</td>
				  <td align='left'>{$retrunStr}</td>
				  <td align='center'>{$zongjia}</td>
				  <td align='center'>".checkOrdersStatus($rs['status'])."</td>
				  <td align='center'>".date("Y-m-d H:i:s",$rs['ptime'])."</td>
				  </tr>
				  <tr><td colspan=5>
				   付款方式：{$rs['spay']}<br>
				   配送地址：{$rs['saddress']} &nbsp; &nbsp; {$rs['sname']}[收]<br>
				   买家备注：{$rs['sbz']}<br>		  
				  </td>
				  </tr>";
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