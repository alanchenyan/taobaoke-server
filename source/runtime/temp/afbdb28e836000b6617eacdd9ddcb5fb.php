<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\XMAPP\htdocs\jm-server\web/../source/application/web\view\viewinfo\info.html";i:1555744687;}*/ ?>


﻿<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<meta name="msapplication-tap-highlight" content="no">
<meta name="full-screen" content="yes">
<meta name="x5-fullscreen" content="true">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?= $info['title'] ?></title>
 
 <link rel="stylesheet" href="/web/assets/web/css/web.css">
 
 
<style>
        a{
        text-decoration:none;
        color:#000000;
        }
          img {
         width: 100%;
         text-align: center;
          }
          body{
          	 background-color: #fff;
          }
       </style>
</head>
<body>
  <div style="width: 95%; margin-left: 2.5%;">
  <span style="font-size: 22px;"> 
<?= $info['title'] ?></span>
</div> 

<div class="width" style="width: 95%; margin-left: 2.5%;">
<div class="content">

<div class="item"  >
<div class="video_info" >
<div class="info_detail clearfix">
<div class="fl people">
<p style="font-size: 14px;">  <span style="color: #483D8B; margin-right: 10px;"><?= $info['author'] ?></span>  <span style="color:  #828282"> <?= $info['createtime'] ?> </span></p>
</div>
 
</div>


<div class="doc-description"><?= htmlspecialchars_decode($info['container']) ?>



 </div>

 
 <span class="comment fr" style="font-size: 13px;color:   #828282">阅读量：<?= $info['clicks'] ?></span> 

 

</body>

 
</html>