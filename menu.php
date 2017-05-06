<?php
	require_once( "include/common.inc.php" );
?>
<html>
<head>
<title>menu</title>
<link rel="stylesheet" href="skin/css/base.css" type="text/css" />
<link rel="stylesheet" href="skin/css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript'>var curopenItem = '1';</script>
<script language="javascript" type="text/javascript" src="skin/js/frame/menu.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<base target="main" />
</head>
<body>
<table width='99%' height="100%" border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td style='padding-left:3px;padding-top:8px' valign="top">
	<!-- Item 1 Strat -->
      <dl class='bitem'>
        <dt onClick='showHide("items3_1")'><b>系统中心</b></dt>
        <dd style='display:block' class='sitem' id='items3_1'>
          <ul class='sitemu'>
            <li><a href='main.php' target='main'>管理首页</a></li>
          </ul>
        </dd>
      </dl>
      <dl class='bitem'>
        <dt onClick='showHide("items1_1")'><b>数据列表</b></dt>
        <dd style='display:block' class='sitem' id='items1_1'>
          <ul class='sitemu'>
          <?php
		  	$cls_mo = new MongoClient( $mongo_server );
			$db_list = $cls_mo->listDBs();
			if( is_array( $db_list['databases'] ) )
			{
				$index = 0;
				foreach( $db_list['databases'] as $db_info )
				{
			?>
            <li>
              <div class='items'>
                <div class='fllct'><a href='db.php?db_name=<?php echo urlencode( $db_info['name'] ); ?>' target='main'><?php echo $db_info['name']; ?></a><span style="float: right"><a href='db.php?db_name=<?php echo urlencode( $db_info['name'] ); ?>&show_detail=1' target='main'><img src='skin/images/frame/book1.gif' alt='详细数据' title='详细数据'/></a><a onClick="return confirm( '您确定要删除[<?php echo $db_info['name']; ?>]' )" href="javascript:remove_db('<?php echo $db_info['name']; ?>');void(0);"><img src='skin/images/frame/gtk-del.png' alt='删除数据库' title='删除数据库'/></a> </span></div>
              </div>
            </li>
            <?php
				$index++;
				}
			}
		  ?>
          </ul>
        </dd>
      </dl>
      <!-- Item 1 End -->
      <!-- Item 2 Strat -->
      <dl class='bitem'>
        <dt onClick='showHide("items2_1")'><b>系统帮助</b></dt>
        <dd style='display:block' class='sitem' id='items2_1'>
          <ul class='sitemu'>
            <li><a href='http://www.dcrcms.com' target='_blank'>官方网站</a></li>
          </ul>
        </dd>
      </dl>
      <!-- Item 2 End -->
	  </td>
  </tr>
</table>
</body>
</html>