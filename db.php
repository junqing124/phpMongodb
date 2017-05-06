<?php
	require_once( "include/common.inc.php" );
	ini_set('mongo.long_as_object', 1);
	//ini_set('mongo.native_long', 1);
	$page_start_time = microtime();	
	$url = $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
	$url_option = array();
	$parse_url = parse_url($url);
	$url_main = 'http://' . $parse_url['path'];
	$search_num = 3;
	if($parse_url['query'])
	{
		//url有参数
		$url_arr = preg_split('/&/' , $parse_url['query']);
		if(is_array($url_arr))
		{
			foreach($url_arr as $key=>$value)
			{
				$c = preg_split('/=/', $value);
				if($c[0] == 'page')
				{
				}else
				{
					array_push($url_option,$c[0].'='.$c[1]);
				}
			}
		}
	}

	if(is_array($url_option))
	{
		$url_option_str_t = implode('&', $url_option);
	}
	if(strlen($url_option_str_t) > 0){
		$url_option_str .= '&' . $url_option_str_t;
	}
	
	if( 'change_page_list_num' == $action )
	{
		put_cookie( 'index_page_list_num', $num, 1000000 );
		$page_list_num = $num;
	}else
	{
		$page_list_num_cookie = get_cookie( 'index_page_list_num' );
		$page_list_num = !empty( $page_list_num_cookie ) ? intval( $page_list_num_cookie ) : 10;
	}
	$cls_mo = new MongoClient( $mongo_server );
	$cls_db = $cls_mo->selectDB($db_name);
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>main</title>
<link rel="stylesheet" type="text/css" href="skin/css/base.css" />
<link rel="stylesheet" type="text/css" href="skin/css/main.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript">
function change_page_list_num()
{
	var url = "?action=change_page_list_num<?php echo $url_option_str; ?>&num=" + document.getElementById("page_list_num").value;
	window.location = url;
}
</script>
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
<table width="98%" align="center" border="0" cellpadding="3" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px;margin-top:8px;">
  <tr>
    <td background="skin/images/frame/wbg.gif" bgcolor="#EEF4EA" class='title'><span>工具</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>
    	<div>数据库名:<?php echo $db_name; ?></div>
    	<div>创建集合:<input type="text" id="collection_name" name="collection_name">&nbsp;<input type="button" onClick="create_collection('<?php echo $db_name; ?>')" value="创建">
      	</div>
      </td>
  </tr>
</table>
<table width="98%" align="center" border="0" cellpadding="3" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px;margin-top:8px;">
  <tr>
    <td background="skin/images/frame/wbg.gif" bgcolor="#EEF4EA" class='title'><span>集合列表</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>
		<?php
			//集合名
			$co_list = $cls_db->listCollections();
			if( $co_list )
			{
				foreach( $co_list as $co_info )
				{
					if( $show_detail )
					{
						$co_count_detail = $cls_db->execute("return db.{$co_info->getName()}.stats().count");
						$co_storage_size_detail = $cls_db->execute("return db.{$co_info->getName()}.stats().storageSize");
						/*p_r( $co_detail );
                        $co_detail = $cls_db->execute("return db.{$co_info->getName()}.stats().storageSize");
                        p_r( $co_detail );
                        exit;*/
						$co_count = $co_count_detail['retval'];
						//p_r( $co_detail );
						//exit;
						$co_mb_size = round( $co_storage_size_detail["retval"] / 1024 / 1024, 2) . "MB";
						$detail_str = "[{$co_count}->{$co_mb_size}]";
					}
		?>
      <div class="collection_list">
        <table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top"><a href="db.php?db_name=<?php echo $db_name; ?>&collection=<?php echo $co_info->getName(); ?>"><?php echo $co_info->getName(); ?><?php echo $detail_str; ?></a></td>
            <td valign="top"><a href="db.php?db_name=<?php echo $db_name; ?>&collection=<?php echo $co_info->getName(); ?>"><img valign='bottom' src='skin/images/frame/addnews.gif' alt='浏览集合' title='浏览集合' align="bottom"/></a></td>
            <td valign="top"><a onClick="return confirm( '您确定要删除[<?php echo $co_info->getName(); ?>]' )" href="javascript:remove_collection('<?php echo $db_name; ?>','<?php echo $co_info->getName(); ?>');void(0);"><img src='skin/images/frame/gtk-del.png' alt='删除集合' title='删除集合' valign='bottom' /></a></td>
          </tr>
        </table>
      </div>
      <?php
				}
			}
		?></td>
  </tr>
</table>
<table width="98%" align="center" border="0" cellpadding="3" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px;margin-top:8px;">
  <tr>
    <td background="skin/images/frame/wbg.gif" bgcolor="#EEF4EA" class='title'><span>搜索</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>
    <form id="frm_search" action="db.php">
    <input type="hidden" name="db_name" value="<?php echo $db_name; ?>">
    集合名
    <select name="collection" id="collection">
    <?php 
			if( $co_list )
			{
				foreach( $co_list as $co_info )
				{
	?>
    	<option value="<?php echo $co_info->getName(); ?>"><?php echo $co_info->getName(); ?></option>
        <?php }} ?>
    </select>
    <?php echo select_value( $collection, 'collection' ); ?>&nbsp;
		<?php for( $i = 0; $i <= $search_num; $i++ ){
			$field_name_var = 'field_name_' . $i;
			$field_value_var = 'field_value_' . $i;
			$type_var = 'type_' . $i;
			?>
		<br><br>
		字段名&nbsp;<input type="text" name="field_name_<?php echo $i; ?>" id="field_name_<?php echo $i; ?>" value="<?php echo $$field_name_var ?>">
    <select name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
    	<option value="=">=</option>
    	<option value=">">></option>
    	<option value="<"><</option>
    	<!--<option value="in">in</option>-->
    	<option value="<>">!=</option>
    	<option value="like">like</option>
    </select>
    <?php echo select_value( $$type_var, 'type_' . $i ); ?>
    &nbsp;
    <input type="text" name="field_value_<?php echo $i; ?>" value="<?php echo $$field_value_var ?>">
		<?php } ?>
		<br>
    <br><input type="button" onclick="document.getElementById('frm_search').submit()" value="搜索">
    </form>
	</td>
  </tr>
</table>
<?php
	if( !empty( $collection ) )
	{
?>
<table width="98%" align="center" border="0" cellpadding="3" cellspacing="1" bgcolor="#CBD8AC" style="margin-bottom:8px;margin-top:8px;">
  <tr>
    <td background="skin/images/frame/wbg.gif" bgcolor="#EEF4EA" class='title'><span><?php echo $collection; ?>集合</span></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td><?php
			$cls_collection = new MongoCollection($cls_db, $collection);
		
			//$page_list_num = 10;
			$total_page = 0;//总页数
			$page = intval($page) > 0 ? (int)$page : 1;	
			$record_num = $cls_collection->find()->count();
			$total_page = ceil( $record_num / $page_list_num );//总页数
			$page = intval($page) > $total_page - 1 ? $total_page : $page;
			$start = ($page - 1) * $page_list_num;
			//echo $page;
			$start = $start < 1 ? 0 : $start;
			$where_option = array();
			for( $i = 0; $i <= $search_num; $i++ )
			{
				$field_name_var = 'field_name_' . $i;
				$field_value_var = 'field_value_' . $i;
				$type_var = 'type_' . $i;
				$field_value = $$field_value_var;
				$field_name = $$field_name_var;
				$type = $$type_var;
				if ($field_value && $field_name)
				{
					switch ($type) {
						case '=':
							$where_option[$field_name] = $field_value;
							break;
						case '<>':
						case '>':
						case '<':
							if ('<' == $type) {
								$option_str = '$lt';
							} else if ('>') {
								$option_str = '$gt';
							} else if ('<>') {
								$option_str = '$ne';
							}
							//$where = array($field_name => array($option_str => $field_value));
							$where_option[$field_name] = array($option_str => $field_value);
							break;
						case 'in':
							//$where = array($field_name => array('$in' => explode(',', $field_value)));
							$where_option[$field_name] = array('$in' => explode(',', $field_value));
							break;
						case 'like':
							$where_option[$field_name] = new MongoRegex('/.*' . $field_value . '.*/i');
							//$where = array($field_name => new MongoRegex('/.*' . $field_value . '.*/i'));
							break;
					}
				}
			}

			$col_list = $cls_collection->find( $where_option )->sort( array( '_id'=>-1 ) )->limit( $page_list_num )->skip( $start );
			
			while( $col_list->hasNext() )
			{
				$col_info = $col_list->getNext();
				//p_r($col_info);
		?>
      <table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px" id="collection_<?php echo $col_info['_id']; ?>">
        <tr bgcolor="#FFFFFF">
          <td style="padding:10px;"><?php
						$col_keys = array_keys( $col_info );
						if( $col_keys )
						{
					?>
            <table width="100%" cellpadding="0" cellspacing="0">
              <?php
			  	  foreach( $col_keys as $key )
				  {
              ?>
              <tr onMouseMove="javascript:this.bgColor='#F0F0E0';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
                <td valign="top" width="100" style="padding-right:10px;text-align:right"><a href="javascript:document.getElementById('field_name_0').value='<?php echo $key; ?>'; void(0)"><?php echo $key; ?></a>:</td>
                <td><?php
				  if( is_array( $col_info[$key] ) )
				  {
					  p_r( $col_info[$key] );
				  }else
				  {
					  echo $col_info[$key];
				  }
			  ?></td>
              </tr>
              <?php } ?>
            </table>
            <?php
						}
					?></td>
        </tr>
        <tr bgcolor="#E7E7E7">
          <td height="24" bgcolor="#E7E7E7">&nbsp;<a onClick="return confirm('确定删除[<?php echo $col_info['_id'] ?>]');" href="javascript:remove_collection_sub('<?php echo $db_name; ?>','<?php echo $collection; ?>','<?php echo $col_info['_id']; ?>');void(0);">删除</a>&nbsp;&nbsp;<!--<a href="">修改</a>&nbsp;--></td>
        </tr>
      </table>
      <?php
				}
			?>
      <table width="98%" border="0" cellpadding="2" cellspacing="1" bgcolor="#D1DDAA" align="center" style="margin-top:8px">
        <tr align="right" bgcolor="#EEF4EA">
          <td height="36"><div style="float:right"> 
              <!--翻页代码 -->
              <?php
						
						$index_url = $url_main . '?page=1' . $url_option_str;
						$last_url = $url_main . '?page=' . $total_page . $url_option_str;
						$pre_url = $url_main . '?page=' . ($page - 1) . $url_option_str;
						$next_url = $url_main . '?page=' . ($page + 1) . $url_option_str;
                    ?>
              当前(<?php echo $page; ?>/<?php echo $total_page; ?>) <a href="<?php echo $index_url; ?>">首页</a> <a href="<?php echo $pre_url; ?>">上一页</a> <a href="<?php echo $next_url; ?>">下一页</a> <a href="<?php echo $last_url; ?>">末页</a>
            </div>
            <div style="text-align:left">每页显示条目数:&nbsp;
              <input type="text" id="page_list_num" name="page_list_num" value="<?php echo $page_list_num; ?>" size="5" />
              <input type="button" onClick="change_page_list_num()" value="确定" />
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
	}
?>
<?php
	//echo $page_start_time;
	$page_end_time = microtime();
	//echo $page_end_time;
	$start_time = explode(" ", $page_start_time);
	//p_r( $start_time );
	$end_time = explode(" ", $page_end_time);
	$total_time = $end_time[0] - $start_time[0] + $end_time[1] - $start_time[1];
	$time_cost = sprintf( "%s", $total_time );
	echo "<div style='margin-left:17px'>页面运行时间: $time_cost 秒</div>"; 
?>
</body>
</html>