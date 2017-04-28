<?php
	require_once( "include/common.inc.php" );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>main</title>
<base target="_self">
<link rel="stylesheet" type="text/css" href="skin/css/base.css" />
<link rel="stylesheet" type="text/css" href="skin/css/main.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body leftmargin="8" topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div style='float:left'> <img height="14" src="skin/images/frame/book1.gif" width="20" />&nbsp;欢迎使用我不是稻草人用PHP开发的管理MongoDB助手，有什么建议和BUG请<a href="http://www.dcrcms.com/hudong.php" target="_blank" style="color:red;">反应给我</a>。 </div>
      <div style='float:right;padding-right:8px;'>
        <!--  //保留接口  -->
      </div></td>
  </tr>
  <tr>
    <td height="1" background="skin/images/frame/sp_bg.gif" style='padding:0px'></td>
  </tr>
</table>
<?php
	$mo = new Mongo( $mongo_server );	
	$db_admin = $mo->admin;	
	$mongodb_info = $db_admin->command(array('buildinfo'=>true));
	//p_r( $mongodb_info );
?>
<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px">
  <tr bgcolor="#EEF4EA">
    <td colspan="2" background="skin/images/frame/wbg.gif" class='title'><span>基本信息</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td width="10%" bgcolor="#FFFFFF">Mongo版本：</td>
    <td width="90%" bgcolor="#FFFFFF"><?php echo $mongodb_info['version'] ?></td>
  </tr>  
</table>
<table width="98%" align="center" border="0" cellpadding="4" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px">
  <tr bgcolor="#EEF4EA">
    <td colspan="2" background="skin/images/frame/wbg.gif" class='title'><span>系统工具</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td width="10%" bgcolor="#FFFFFF">创建数据库</td>
    <td width="90%" bgcolor="#FFFFFF">名称:<input type="text" id="db_name" name="db_name">&nbsp;&nbsp;<input type="button" onClick="create_db()" value="创建"></td>
  </tr>  
</table>
</body>
</html>