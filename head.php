<?php
session_start();
include_once('connect.php');
include_once('function.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="images/inc.css" type="text/css" rel="stylesheet" />
<title>校园订餐网</title>
<script src="js/jquery.js"></script>
<script src="js/function.js"></script>
</head>
<body style=" background-image:url(images/bg.jpg)">


<div style='width:1000px; margin:auto; background-color: #E2D2C9;min-height:800px;'>
  <div style="width:100%;height:150px; background-image:url(images/top.jpg); background-repeat:no-repeat; background-position:right;"></div>
  <div style="width:100%;height:50px; background-color:#422F24; line-height:2.5;" class="indexmenu">
      
      <span style="display:inline-block; padding:5px;color:#fff"><a href='index.php?mod=goods&act=list' style="font-weight:bolder;">所有餐厅</a> | </span>
     <?php
      $typeQuery=getArr("select * from restaurant order by sort desc");
      foreach($typeQuery as $typeArr){
	    echo "<span style='display:inline-block; padding:5px;color:#fff'>
		<a href='index.php?mod=goods&act=list&cid={$typeArr['id']}' style='font-weight:bolder;margin-top:20px;'>{$typeArr['title']}</a>  | 
		</span>";
        $num++;
       }
	 ?>
     <?php if(!isset($_SESSION['username'])){?>       <span style="display:inline-block; padding:5px;color:#fff"><a href='index.php?mod=user&act=login' style="font-weight:bolder;">会员登录</a> | </span>  <?php } ?>
     <span style="display:inline-block; padding:5px;color:#fff"><a href='index.php?mod=guestbook&act=list' style="font-weight:bolder;">留言投诉</a> | </span>
     <span style="display:inline-block; padding:5px;color:#fff"><a href='index.php?mod=sale&act=list' style="font-weight:bolder;">售后服务</a> | </span>
     <?php if(isset($_SESSION['adminname'])&&$_SESSION['adminname']){?><span style="display:inline-block; padding:5px;color:#fff"><a href='admin.php' style="font-weight:bolder;">后台管理</a></span><?php }?>
  </div>


  <?php if(isset($_SESSION['username'])&&strstr($_SERVER['SCRIPT_NAME'],"admin.php")==false) {?>
  <div style="width:200px; float:left;min-height:800px; margin-top:10px;"> 
    <div style="margin:0px 5px 5px 5px; position:relative">
    <div style="border:solid 1px #32231B; padding:5px; background-color: #32231B; color:#FF0; font-size:16px; font-weight:bold;">管理中心</div> 
    <div style="border:solid 1px #32231B; padding:5px;">
	 <?php
     if(strstr($_SERVER['SCRIPT_NAME'],"admin.php")===false){
       if(isset($_SESSION['username'])&&$_SESSION['username']) {?>
         <div style="border:solid 1px #ccc; padding:10px; background:#F8F3EF; margin-bottom:10px; cursor:pointer" onclick="location='index.php?mod=user&act=edit'">修改密码</div>
         <div style="border:solid 1px #ccc; padding:10px; background:#F8F3EF; margin-bottom:10px; cursor:pointer" onclick="location='index.php?mod=orders&act=list'">我的订单</div>
         <div style="border:solid 1px #ccc; padding:10px; background:#F8F3EF; margin-bottom:10px; cursor:pointer" onclick="location='index.php?mod=goods&act=cart'">购物车</div>
      <?php }?>
      <?php if(isset($_SESSION['username'])){?>
               <div style="border:solid 1px #ccc; padding:10px; background:#F8F3EF; margin-bottom:10px; cursor:pointer" onclick="location='index.php?mod=user&act=out'">退出登录</div>
      <?php }?>
    <?php }?>
    </div>
   </div>
 </div>
 <?php }?> 
  
