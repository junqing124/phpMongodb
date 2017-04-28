// JavaScript Document
//删除集合下的子集
function remove_collection_sub( db_name, collection, id )
{
	var action = 'remove_collection_sub';
	var action_arr={db_name:db_name, collection:collection, id:id, action:action};
	$.post("action.php",action_arr, function(data)
	{
		if( 'ok' == data )
		{
			$('#collection_' + id ).remove();
		}else
		{
			alert('删除失败');
		}
	}); 
}

//删除数据库
function remove_db( db_name )
{
	var action = 'remove_db';
	var action_arr={db_name:db_name, action:action};
	$.post("action.php",action_arr, function(data)
	{
		if( 'ok' == data )
		{
			window.parent.menu.location = window.parent.menu.location;
		}else
		{
			alert('删除失败');
		}
	}); 
}

//删除数据库
function create_db( )
{
	var action = 'create_db';
	var action_arr={db_name:$('#db_name').val(), action:action};
	$.post("action.php",action_arr, function(data)
	{
		if( 'ok' == data )
		{
			$('#db_name').val('');
			window.parent.menu.location = window.parent.menu.location;
		}else
		{
			alert('删除失败');
		}
	}); 
}
//删除数据库
function create_collection( db_name )
{
	var action = 'create_collection';
	var action_arr={db_name:db_name, collection_name:$('#collection_name').val(), action:action};
	$.post("action.php",action_arr, function(data)
	{
		if( 'ok' == data )
		{
			$('#collection_name').val('');
			window.location = window.location;
		}else
		{
			alert('删除失败');
		}
	}); 
}

//删除数据库
function remove_collection( db_name, collection_name )
{
	var action = 'remove_collection';
	var action_arr={db_name:db_name, collection_name:collection_name, action:action};
	$.post("action.php",action_arr, function(data)
	{
		if( 'ok' == data )
		{
			window.location = window.location;
		}else
		{
			alert('删除失败');
		}
	}); 
}