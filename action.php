<?php
	require_once( "include/common.inc.php" );
	$cls_mo = new MongoClient( $mongo_server );
	if( 'remove_collection_sub' == $action )
	{
		$cls_db = $cls_mo->selectDB($db_name);
		$cls_collection = $cls_db->selectCollection($collection);
		$return = $cls_collection->remove( array( '_id' => new MongoId( $id ) ), array('justOne' => true) );
		echo 'ok';
	}else if( 'remove_db' == $action )
	{
		$cls_db = $cls_mo->selectDB($db_name);
		$return = $cls_db->drop();
		echo 'ok';
	}else if( 'create_db' == $action )
	{
		$cls_db = $cls_mo->selectDB($db_name);
		$cls_db->createCollection('test');
		$cls_db->dropCollection('test');
		echo 'ok';
	}else if( 'create_collection' == $action )
	{
		$cls_db = $cls_mo->selectDB($db_name);
		$cls_db->createCollection( $collection_name );
		echo 'ok';
	}else if( 'remove_collection' == $action )
	{
		$cls_db = $cls_mo->selectDB($db_name);
		$cls_db->dropCollection( $collection_name );
		echo 'ok';
	}else
	{
		echo '非法操作';
	}
	
?>