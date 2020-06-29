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
	
	
	case "comment":
		$id=intval($_GET['id']);
		if(!$_POST['content']){
			echo "<script>alert('评论内容不能为空！');location='index.php?mod=goods&act=show&id=$id'</script>";
			die();
		}
		$query="insert into comment set 
		cid='$id',
		content='{$_POST['content']}',
		author='{$_POST['author']}',
		uid='{$_SESSION['userid']}',
		ptime='".time()."'";
		if(query($query)){
			echo "<script>alert('评论成功！');location='index.php?mod=goods&act=show&id=$id'</script>";
			die();
		}
		else die($query);
	break;
	
	case "show":
		$Arr=getone("select * from goods where id=".intval($_GET['id']));
		$C=getone("select * from restaurant  where id={$Arr['cid']}");
		echo "
		<div style='margin-top:20px;margin-left:100px;'>
		 <div style='width:306px;float:left;border:solid 1px #ccc;'>
		   <img src='{$Arr['picurl']}' style='border:solid 3px #fff;width:300px;height:200px;'>
		 </div>
		 <div style='width:350px;float:right;font-weight:bold;line-height:3' align='left'>
		   所属餐厅：{$C['title']}<br>
		   菜品名称：{$Arr['title']}<br>
		   菜品售价：{$Arr['price']}<br>
		   订购数量：<input name='num' value=1 style='width:30px;'><br>
		   <img src='images/buy.gif' style='margin-top:30px;cursor:pointer' onclick=\"location='index.php?mod=goods&act=buy&id={$_GET['id']}&num='+$('input[name=num]').val()\">
		 </div>
		 <div style='clear:both'></div>
		</div>
		<div style='font-weight:bold;line-height:3;margin-left:100px;' align='left'>
			 菜品描述：{$Arr['content']}
		</div>
		<hr>
		<div>"; 
		$commentQuery=getArr("select * from comment where cid='{$_GET['id']}'");
		foreach($commentQuery as $cArr){
			echo "<div style='border-bottom:dashed 1px #ccc;padding:5px;'>";
			echo $cArr['author']?$cArr['author']:"匿名";
			echo ":{$cArr['content']} &nbsp;&nbsp; [".date("Y-m-d H:i:s",$cArr['ptime'])."]";
			echo"</div>";
		}
		echo"
		</div>";
		if(isset($_SESSION['userid'])){
		echo"
		<div style='margin-top:20px;'>
		  <b>顾客评论：</b>
		  <form action='?mod=goods&act=comment&id={$_GET['id']}' method='post'>
			昵称：<input name='author'><br>
			内容：<textarea style='width:300px;height:100px;' name='content'></textarea>
			<input type='submit' value='提交' class='submit'>
		  </form>
		</div>";
		}
	break;
	
	case "buy":
		$id=intval($_GET['id']);
		$num=intval($_GET['num']);
		$_SESSION['goods'][$id]=$_SESSION['goods'][$id]+$num;
		echo "<script>location='index.php?mod=goods&act=cart'</script>";
	break;
	
	case "cart":
		$zongjia=0;
		echo "<br><br><b>我的购物车</b>
		<table style='width:400px;' align='center'>";
		foreach($_SESSION['goods']as $key=>$value){
		   $retrunStr="";		 
		   $Goods=getone("select * from goods where id={$key}");
		   $retrunStr.="菜品：{$Goods['title']},单价：{$Goods['price']},数量：{$value},总价：";
		   $retrunStr.=$Goods['price']*$value;
		   $retrunStr.=" 元<br>";
		   $zongjia+=$Goods['price']*$value;	     
		   echo "<tr><td>{$retrunStr}</td></tr>";
		}
		echo "</table><br><b>购物车总计：</b> $zongjia 元";
		if($zongjia>0)echo"<center><div>
		<input type='button' onclick=\"location='index.php?mod=goods'\" value='继续购物' class='submit'>		 
		<input type='button' onclick=\"location='index.php?mod=goods&act=confirm'\" value='提交订单' class='submit'>		 
		</div></center>";
	break;
	
	case "confirm":
		$zongjia=0;
		$order=time();
		$gid="";
		?>
		<script>
			function checkorder(){
		if(!$('input[name=sname]').val()){alert('买家姓名不能为空');$('input[name=sname]').focus();return false;}
		if(!$('input[name=saddress]').val()){alert('送货地址不能为空');$('input[name=saddress]').focus();return false;}
		}
		</script>
		<?php
		echo "<form action='index.php?mod=orders&act=add' method='post' onsubmit='return checkorder()'>
		<input type='hidden' name='title' value='$order'><br>
		<b>确认您的订单</b>
		<table style='width:400px;' align='center'>";
		$num=1;
		foreach($_SESSION['goods']as $key=>$value){
			$retrunStr="";		 
			$Goods=getone("select * from goods where id={$key}");
			$retrunStr.="菜品：{$Goods['title']},单价：{$Goods['price']},总价：{$value},Subtotal：";
			$retrunStr.=$Goods['price']*$value;
			$retrunStr.=" 元<br>";
			$gid.=$gid?",{$key}:{$value}":"{$key}:{$value}";
			$zongjia+=$Goods['price']*$value;	     
			echo "<tr style='height:30px;'><td>{$num}{$retrunStr}</td></tr>";
			$num++;
	   }
	   echo "</table>
	   <br>	 
	   <b>订单总计：</b> $zongjia  元
	   <input type='hidden' name='gid' value='$gid'>
	   <input type='hidden' name='paymoney' value='$zongjia'>	<br><br>
	   <b>配送方式：</b><br>
		&nbsp; &nbsp; &nbsp; &nbsp;付款方式：<select   name='spay' value='' style='width:150px;'>
		<option>货到付款</option> <option>支付宝</option> <option>网银</option>
		</select><br>
		&nbsp; &nbsp; &nbsp; &nbsp;买家姓名：<input   name='sname' value='' style='width:150px;'><br>
		&nbsp; &nbsp; &nbsp; &nbsp;送餐地址：<input   name='saddress' value='' style='width:400px;'><br>
		&nbsp; &nbsp; &nbsp; &nbsp;买家备注：<input   name='sbz' value='' style='width:400px;'><br>
	   ";
	   if($zongjia>0)echo"<center><div><input type='submit' value='保存订单' class='submit'></div></center>";
	   echo"</form>";
	break;	
}
?>
