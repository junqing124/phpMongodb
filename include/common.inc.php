<?php

define('IN_DCR', TRUE); 
define('WEB_INCLUDE', str_replace("\\", '/', dirname(__FILE__) ) );
define('WEB_DR', str_replace("\\", '/', substr(WEB_INCLUDE, 0, -8) ) );

@set_magic_quotes_runtime(0);
$magic_quotes = get_magic_quotes_gpc();

/* 初始化设置 */
@ini_set('memory_limit', '64M');
@ini_set('session.cache_expire', 180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);
@ini_set('display_errors', 1);

//配置文件
require_once(WEB_INCLUDE . '/app.info.php');
require_once(WEB_INCLUDE . '/config.common.php');
header('Content-type:text/html;charset=' . $web_code);

if( $web_tiaoshi )
{
	error_reporting( E_ALL ^ E_NOTICE );
}else
{
	error_reporting( 0 );
}

//检查和注册外部提交的变量
foreach($_REQUEST as $_k=>$_v)
{
	if( strlen($_k)>0 && preg_match('/^(GLOBALS)/i',$_k) )
	{
		exit('Request var not allow!');
	}
}

function _get_request(&$svar)
{
	global $db_type, $magic_quotes;
	if(!$magic_quotes)
	{
		//开了转义
		if(is_array($svar))
		{
			foreach($svar as $_k => $_v) $svar[$_k] = _get_request($_v);
		}else
		{
			if($db_type == 1)
			{
				$svar = my_sqlite_escape_string($svar);
			}elseif($db_type == 2)
			{
				$svar = addslashes($svar);
			}
		}
	}else
	{
		//没有开转义..兼容sqlite
		if(is_array($svar))
		{
			foreach($svar as $_k => $_v) $svar[$_k] = _get_request($_v);
		}else
		{
			if($db_type == 1)
			{
				$svar = stripslashes($svar);
				$svar = my_sqlite_escape_string($svar);
			}
		}
	}
	return $svar;
}

$req_data = array();
foreach( array('_GET', '_POST', '_COOKIE') as $_request )
{
    foreach( $$_request as $_k => $_v )
    {
        ${$_k} = _get_request($_v);
        if( '_COOKIE' != $_request )
        {
            $req_data[$_k] = _get_request($_v);
        }
    }
}
unset($_GET, $_POST);

//时区
if(PHP_VERSION > '5.1')
{
	@date_default_timezone_set('PRC');
}

//用户访问的网站host
$web_clihost = 'http://'.$_SERVER['HTTP_HOST'];

//全局常用函数
require_once(WEB_INCLUDE . '/common.func.php');

//程序版本
$version = $app_version;

function error_notice($err_no, $err_str, $err_file, $err_line)
{
    cls_app:: log('文件' . $err_file . '第' . $err_line . '行发生错误(' . $err_no . '):' . $err_str);
}

//set_error_handler( "error_notice", ~E_NOTICE & ~E_STRICT );
?>