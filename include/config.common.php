<?php

defined('IN_DCR') or exit('No permission.'); 

$mongo_server = '';

if( empty( $mongo_server ) )
{
	$mongo_server = 'mongodb://10.10.40.99:12468';
	//$mongo_server = 'mongodb://192.168.6.163:12468';
	//$mongo_server = 'mongodb://192.168.1.147:12201';
	//$mongo_server = 'mongodb://192.168.1.147:12201';
	//$mongo_server = 'mongodb://192.168.6.20:12468';
	//$mongo_server = 'mongodb://192.168.1.42:12468';

}

$web_tiaoshi = 1;
?>