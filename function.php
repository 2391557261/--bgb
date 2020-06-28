<?php
function checkLoginStatus($tag='user'){
	if($tag=='user'){
		if(!isset($_SESSION['username'])) echo "<script>location='index.php?mod=user&act=login'</script>";
	}
	elseif($tag=='admin'){
		if(!isset($_SESSION['adminname'])||!$_SESSION['adminname']) {
		  echo "<script>location='index.php?mod=user&act=out'</script>";
		  die("No permission");
		}
		else{
		  $A=getone("select * from user where username='{$_SESSION['adminname']}' and password='{$_SESSION['adminpassword']}' and tag=8");
		  if(!$A['id']){
		  echo "<script>location='index.php?mod=user&act=out'</script>";
		  die("No permission");
		  }
		}
	}    
}

function checkOrdersStatus($status){
	 switch($status){
		case "1":return "未付款";break;
		case "2":return "付款失败";break;
		case "3":return "已付款";break;
		case "4":return "已发货";break;
		case "5":return "已签收";break;
	}
}

function upfilename($exname){
	$dir = "Img/";
	while(true){
		if(!is_file($dir.$i.".".$exname)){
			$name=$i.".".$exname;
			break;
		} 
		$i++;
	} 
	return $dir.time().$name;
} 
	
function contrast($var1,$var2,$tag='select'){
	if($var1==$var2){
		if($tag=='select') return " selected";
	}
}
/*分页函数*/
function page($countsql,$pagesql,$url,$num=20){
  $page=isset($_GET['page'])?(intval($_GET['page'])>0?intval($_GET['page']):1):1;
  $count=getone($countsql);
  $total=$count['count(*)'];	
  if($total){
    $total_page=ceil($total/$num);
    $page=($page>$total_page)?$total_page:$page;
    $offset=($page-1)*$num;
    $returns[1]=query($pagesql." limit $offset,$num");
	    $str.=" <div class='ex_page_link'>&nbsp;<a href='{$url}&page=1' ><span class='ex_page_bottm'><center>首页</center></span></a>";
        $tempshang=$page-1;$tempxia=$page+1;
        if($page!=1) $str.="&nbsp;<a href='{$url}&page={$tempshang}'><span class='ex_page_bottm'><center>上一页</center></span></a>";
        for($i=1;$i<=$total_page;$i++)
           if($page==$i) $str.="&nbsp;<a href='{$url}&page={$i}'><span class='ex_page_bottm ex_page_sec' style='width:20px;' ><center>{$i}</center></span></a>";
	       else  if($i-$page>=-$num&&$i-$page<=$num) $str.="&nbsp;<a href='{$url}&page={$i}'><span class='ex_page_bottm' style='width:20px;' ><center>{$i}</center></span></a>";
        if($page!=$total_page)  $str.="&nbsp;<a href='{$url}&page={$tempxia}'><span class='ex_page_bottm'><center>下一页</center></span></a>";
        $str.=" 
		&nbsp;<span  class='ex_page_bottm'><center><a href='{$url}&page={$total_page}'>尾页</a></center></span>
		&nbsp;<span  class='ex_page_bottm' ><center>{$page}/{$total_page}</center></span></div>";
	$returns[2]=$str;
	$returns['pl']="
	 <span class='op' onclick=\"SelectAll('selectAll','delform','allidd')\">全选/</span>
	 <span class='op' onclick=\"SelectAll('','delform','allidd')\">反选/</span>
	 <span class='op' onclick=\"SelectAll('selectNo','delform','allidd')\">不选</span>";
     $returns['pldelete']="<span class='op2' onclick=\"if(checkdelform('delform','allidd')&&confirm('您真的要删除这些内容吗？')) delform.submit()\">批量删除</span>";
     return $returns;
	}
  else return false;	
}
/********************************数据库相关操作******************************************
/*                                                                                    *
/*                                                                                   */
//对mysql_close进行封装，目的是为今后的扩展预留数据库接口，如更换数据库或服务器环境发生变化*
function close(){
	global $conn;
	mysql_close($conn);
	//mysqli_close($conn);
}
/*对mysql_query进行封装，目的是为今后的扩展预留数据库接口，如更换数据库或服务器环境发生变化*/
function query($sql){
	global $conn;
	return mysql_query($sql,$conn);
	//return mysqli_query($conn,$sql);
}
/*对mysql_fetch_assoc进行封装，目的是为今后的扩展预留数据库接口，如更换数据库或服务器环境发生变化*/
function fetch($rs){
	return mysql_fetch_assoc($rs);
	//return mysqli_fetch_assoc($rs);
}
/*取出最近执行的id*/
function insert_id(){
	return mysql_insert_id();
	//return mysql_insert_id();
}
/*取出一条*/
function getone($query){
	return fetch(query($query));
}
/*根据sql语句取出数据，返回数组*/
function getArr($query){
	$arr=array();
	$query=query($query);
	while($row=fetch($query)){
		$arr[]=$row;
	}
	return $arr;
}

?>