<?php

defined('IN_DCR') or exit('No permission.'); 

/**
 * 全站共用function
 * ===========================================================
 * 版权所有 (C) 2006-2020 我不是稻草人，并保留所有权利。
 * 网站地址: http://www.dcrcms.com
 * ----------------------------------------------------------
 * 这是免费开源的软件；您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * 不允许对程序修改后再进行发布。
 * ==========================================================
 * @author:     我不是稻草人 <junqing124@126.com>
 * @version:    v1.0.3
 * @package class
 * @since 1.0.8
*/
 
/**
 * 对字符串进行加密
 * @param string $s 要加密的字符串
 * @return string 加密后的字符串
 */
function encrypt($s)
{
	return crypt(md5($s),'dcr');
}

/**
 * 对字符串进行加密 这里调用encrypt函数,为encrypt函数的别名
 * @param string $s 要加密的字符串
 * @return string 加密后的字符串
 */
function jiami($s)
{
	return encrypt($s);
}

/**
 * 生成javascript跳转 并自动跳转
 * @param string $msg 显示信息
 * @param string $url 要跳转的地址
 * @param string $istop 是不是在父窗口中跳转
 * @return boolean 跳转到相应的网址
 */
function show_next($msg, $url, $istop = 0)
{
	if( strlen($msg) > 0 )
	{
		if( $istop )
		{
			$mymsg = "<script type='text/javascript'>alert(\"" . $msg . "\");top.location.href=\"" . $url . "\";</script>";
		}else
		{
			$mymsg = "<script type='text/javascript'>alert(\"" . $msg . "\");location.href=\"" . $url . "\";</script>";
		}
	}else
	{
		if( $istop )
		{
			$mymsg = "<script type='text/javascript'>top.location.href=\"" . $url . "\";</script>";
		}else
		{
			$mymsg = "<script type='text/javascript'>location.href=\"" . $url . "\";</script>";
		}
	}
	echo $mymsg;
	exit;
}

/**
 * 返回上一页
 * @param string $msg 显示信息
 * @return boolean 显示一个alert提示信息
 */
function show_back($msg = '')
{
	if( !empty($msg) )
	{
		echo "<script>alert(\"" . $msg . "\");history.back();</script>'";
	}else
	{
		echo "<script>history.back();</script>'";
	}
	exit;
}

/**
 * 跳转
 * @param string $url 要跳转的地址
 * @return boolean 跳转到$url
 */
function redirect($url)
{
	echo "<script>location.href='" . $url . "';</script>'";
	exit;
}

/**
 * 截取字符串 能对中文进行截取
 * @param string $str 要截取的字条串
 * @param string $start 开始截取的位置
 * @param string $len 截取的长度
 * @return string 截取后的字符串
 */
function my_substr($str, $start, $len)
{
    $tmpstr = "";
    $strlen = $start + $len;
    for($i = 0; $i < $strlen; $i++)
    {
        if( ord( substr($str, $i, 1) ) > 0xa0 )
        {
            $tmpstr .= substr($str, $i, 3);
            $i += 2;
        } else
            $tmpstr .= substr($str, $i, 1);
    }
    return $tmpstr;
}

/**
 * 写入cookie
 * @param string $key cookie名
 * @param string $value cookie值
 * @param string $kptime cookie有效期
 * @param string $pa cookie路径
 * @return boolean 返回true
 */
function put_cookie($key, $value, $kptime = 0, $pa = "/")
{
	setcookie($key,$value,time()+$kptime,$pa);
}

/**
 * 删除cookie
 * @param string $key cookie名
 * @return boolean 返回true
 */	
function drop_cookie($key)
{
	setcookie($key,'',time()-360000,"/");
}

/**
 * 获取cookie值
 * @param string $key cookie名
 * @return string 获取的cookie的值
 */		
function get_cookie($key)
{
	if( !isset($_COOKIE[$key]))
	{
		return '';
	}
	else
	{
		return $_COOKIE[$key];		
	}
}

/**
 * 获取当前IP
 * @return string 本机的IP
 */	
function get_ip()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else if(!empty($_SERVER["REMOTE_ADDR"]))
	{
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else
	{
		$cip = '';
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = isset($cips[0]) ? $cips[0] : 'unknown';
	unset($cips);
	
	return $cip;
}

/**
 * 获取顶级域名
 * @param string $url 要操作的地址
 * @return string $url的顶级域名
 */	
function get_top_url($url = '')
{
	if(empty($url))
	{
		$url = $_SERVER['SERVER_NAME'];
	}
	$t_url = parse_url($url);
	$t_url = $t_url['path'];
	
	return $t_url;
}
	
/**
 * 显示提示信息
 * @param string $msg 信息内容
 * @param string $msg_type 信息类型1为一般信息 2为错误信息
 * @param string $back 返回地址 如果有多个则传入数组
 * @param string $msgTitle 信息标题
 * @param boolean $is_show_next_tip 为true时显示下你可以下一步操作,为false时不显示
 * @param boolean $is_show_back 为true时显示返回,为false时不显示 版本>=1.0.5
 * @return boolean(true) 显示一个提示信息
 */
function show_msg($msg, $msg_type = 1, $back = '', $msgTitle = '信息提示', $is_show_next_tip = true, $is_show_back = true)
{
	/*
	 *msg显示信息 如果要多条则传入数组
	 *msg_type信息类型1为一般信息 2为错误信息
	 *back为返回地址 如果有多个则传入数组
	 *msgTitle为信息标题
	 */
	if( is_array($msg) )
	{
		foreach($msg as $value)
		{
			if( $msg_type == 2 )
			{
				$msg_t .= "<li style='border-bottom:1px dotted #CCC;padding-left:5px;color:red;'>·$value</li>";
			}else{
				$msg_t .= "<li style='border-bottom:1px dotted #CCC;padding-left:5px;color:green;'>·$value</li>";
			}
		}
	}else
	{
		if( $msg_type == 2 )
		{
			$msg_t = "<li style='border-bottom:1px dotted #CCC;padding-left:5px;color:red;'>·$msg</li>";
		}else
		{
			$msg_t = "<li style='border-bottom:1px dotted #CCC;padding-left:5px;color:green;'>·$msg</li>";
		}
	}
	if($is_show_next_tip)
	{
		if($is_show_back)
		{
			$back_t = "<li style='border-bottom:1px dotted #CCC;padding-left:5px;'>·<a style='color:#06F; text-decoration:none' href='javascript:history.back()'>返回</a></li>";
		}
		if( is_array($back) )
		{
			foreach($back as $key=> $value )
			{
				$back_t .= "<li style='border-bottom:1px dotted #CCC;padding-left:5px;'>·<a style='color:#06F; text-decoration:none' href='$value'>$key</a></li>";
			}
		}
	}
	global $web_code;
	$msg_str = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=$web_code' /><title>信息提示页面</title></head><body><div style='width:500px; margin:0 auto; border:1px #09F solid; font-size:12px;'>
<div style='background-color:#09F; font-size:12px;padding:5px; font-weight:bold; color:#FFF;'>$msgTitle</div>
<div><ul style='list-style:none; line-height:22px; margin:10px; padding:0'>$msg_t</ul></div>";
	if( $is_show_next_tip )
	{
		$msg_str .= "<div style='border:1px #BBDFF8 solid; width:96%; margin:0 auto; margin-bottom:10px;'><div style='background-color:#BBDFF8; font-size:12px;padding:5px; font-weight:bold; color:#666;'>您可以：</div>
	<div><ul style='list-style:none; line-height:22px; margin:10px; padding:0'>$back_t</ul></div></div></div>";
	}
	$msg_str .= "</body></html>";
	//$msg_str.=$msg;
	echo $msg_str;
	exit;
}

/**
 * 获取随机字符串
 * @param int $len 字符串长度
 * @return string 产生的随机字符串
 */
function get_rand_str($len = 4)
{
	$chars = array("a","b","c","d","e","f","g", "h", "i", "j", "k","l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v","w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G","H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R","S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2","3", "4", "5", "6", "7", "8", "9");
	$charsLen = count($chars)-1;
	shuffle( $chars );
	$output = "";
	for( $i = 0; $i < $len; $i ++ )
	{
		$output .= $chars[mt_rand(0, $charsLen)];
	}
	
	return $output;
}

/**
 * 格式化输出数据
 * @param array $arr 要输出的数组
 * @param boolean $is_stop_output 是否停止输出流 如果为true则exit(); since>=1.0.7
 * @return true
 */	
function p_r($arr, $is_stop_output = false)
{
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
	if($is_stop_output)
	{
		exit;
	}
}

/**
 * 去除数组空白元素
 * @since 1.0.8
 * @param array $arr 要操作的数组
 * @return array 去重后的数组
 */	
function array_remove_empty(& $arr, $trim = true)   
{   
	foreach ($arr as $key => $value)
	{
		if(is_array($value))
		{
    		array_remove_empty($arr[$key]);   
    	}else
		{
    		$value = trim($value);   
    		if ($value == '')
			{   
    			unset($arr[$key]);   
    		}elseif ($trim)
			{   
    			$arr[$key] = $value;   
    		}   
    	}   
	}
}

/**
 * 页面输出信息 弄这个function的目的是想页面所有的测试信息都用这个。以后不想有测试信息直接注释p_r($str)就OK了 ^_^ 懒人一枚唉...
 * @since 1.1.0
 * @param string $str 信息内容
 * @return true
 */	
function msg( $str )
{
	p_r($str);
}

/**
 * 获取页面接收的post,get数据
 * @since 1.1.1
 * @param string $no_field 不要的字段
 * @return array
 */   
function get_req_data( $no_field = '' )
{
    global $req_data;
    $no_field_arr = array();
    if( ! empty($no_field) )
    {
        $no_field_arr = explode( ',', $no_field);
    }
    if( $no_field_arr )
    {
        foreach( $no_field_arr as $no_field_name )
        {
            unset( $req_data[$no_field_name] );
        }
    }
   
    $req_data['add_time'] = time();
    $req_data['update_time'] = time();
   
    return $req_data;
}
//设置select值
function select_value( $value, $select_id )
{
	$html = '';
	if( $select_id )
	{
		$html = "<script type=\"text/javascript\">
						$('#{$select_id}').val('{$value}');
                    </script>";
	}
	echo $html;
}
 
?>