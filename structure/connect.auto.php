<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class connect extends Capsule {
	public static $g_con;
	private static $query_id;
	private static $_query;

	public static function init() {
		$capsule = new Capsule;
		$capsule->addConnection([
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'emerald',
			'username'  => 'root',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		]);
		try {
			$capsule->setAsGlobal();
			$capsule->bootEloquent();
			self::$g_con = $capsule->getConnection()->getPdo();
		} catch (PDOException $e) {
			@file_put_contents('error_log',@file_get_contents('error_log') . $e->getMessage() . "\n");
			die(include 'views/maintenance.cy.php');
		}
	}

	public static function prepare($query) {
		self::$query_id = self::$g_con->prepare($query);
		return self::$query_id;
	}

	public static function rows($table) {
			if(is_array($table)) {
				$rows = 0;
				foreach($table as $val) {
					$q = self::$g_con->prepare("SELECT * FROM `".$val."`");
					$q->execute();
					$rows += $q->rowCount();
				}
				return $rows;
			}
			$q = self::$g_con->prepare("SELECT * FROM `".$table."`");
			$q->execute();
			return $q->rowCount();
		}

	public static function query($query) {
		return self::$g_con->query($query);
	}

	public static function build($data = array()) {
		if ( !empty($data['select']) )
    	{
    		self::_buildSelect( $data['select'], $data['from'], isset($data['where']) ? $data['where'] : '', isset( $data['add_join'] ) ? $data['add_join'] : array());
    	}
    	if ( !empty($data['group']) )
    	{
    		self::_buildGroupBy( $data['group'] );
    	}
		if ( !empty($data['order']) )
    	{
    		self::_buildOrderBy( $data['order'] );
    	}
		if ( isset($data['limit']) && is_array( $data['limit'] ) )
    	{
    		self::_buildLimit( $data['limit'][0], (isset($data['limit'][1]) ? $data['limit'][1] : 0) );
    	}
	}

	private static function _buildGroupBy( $group )
	{
    	if ( $group )
    	{
    		self::$_query .= ' GROUP BY ' . $group;
    	}
    }

    private static function _buildLimit( $offset, $limit=0 )
	{
		$offset = intval( $offset );
		$offset = ( $offset < 0 ) ? 0 : $offset;
		$limit  = intval( $limit );

    	if ( $limit )
    	{
    		self::$_query .= ' LIMIT ' . $offset . ',' . $limit;
    	}
    	else
    	{
    		self::$_query .= ' LIMIT ' . $offset;
    	}
	}

	private static function _buildSelect( $get, $table, $where, $add_join=array()) {
		if( !count($add_join) )
		{
			if( is_array( $table ) )
			{
				$_tables	= array();
				foreach( $table as $tbl => $alias )
				{
					$_tables[] = $tbl . ' ' . $alias;
				}
				$table	= implode( ', ', $_tables );
			}
			else
			{
				$table = $table;
			}
	    	self::$_query .= "SELECT {$get} FROM " . $table;

	    	if ( $where != "" )
	    	{
	    		self::$_query .= " WHERE " . $where;
	    	}
	    	return;

    	} else {

	    	$select_array   = array();
	    	$from_array     = array();
	    	$joinleft_array = array();
	    	$where_array    = array();
	    	$final_from     = array();
	    	$select_array[] = $get;
	    	$from_array[]   = $table;
	    	$hasLeft		= false;
	    	$hasInner		= false;

	    	if ( $where )
	    	{
	    		$where_array[]  = $where;
	    	}
	    	if ( is_array( $add_join ) and count( $add_join ) )
	    	{
	    		foreach( $add_join as $join )
	    		{
	    			if ( ! is_array( $join ) )
	    			{
	    				continue;
	    			}
	    			if ( !empty($join['select']) )
	    			{
	    				$select_array[] = $join['select'];
	    			}
	    			if ( empty($join['type']) OR $join['type'] == 'left' )
	    			{
	    				$hasLeft = true;
	    				$tmp = " LEFT JOIN ";
	    				foreach( $join['from'] as $tbl => $alias )
						{
							$tmp .= $tbl.' '.$alias;
						}
	    				if ( isset($join['where']) )
	    				{
	    					$tmp .= " ON ( ".$join['where']." ) ";
	    				}
	    				$joinleft_array[] = $tmp;
	    				unset( $tmp );
	    			}
	    			else if ( $join['type'] == 'inner' )
	    			{
	    				$hasInner = true;
	    				$from_array[]  = $join['from'];

	    				if ( $join['where'] )
	    				{
	    					$where_array[] = $join['where'];
	    				}
	    			}
	    			else
	    			{
	    			}
	    		}
	    	}

	    	foreach( $from_array as $i )
			{
				foreach( $i as $tbl => $alias )
				{
					$final_from[] = $tbl . ' ' . $alias;
				}
			}

	    	$get     = implode( ","     , $select_array   );
	    	$table   = ( $hasLeft === true && $hasInner === true ) ? '(' . implode( ",", $final_from ) . ')' : implode( ",", $final_from );
	    	$where   = implode( " AND " , $where_array    );
	    	$join    = implode( "\n"    , $joinleft_array );

	    	self::$_query .= "SELECT {$get} FROM {$table}";

	    	if ( $join )
	    	{
	    		self::$_query .= " " . $join . " ";
	    	}
	    	if ( $where != "" )
	    	{
	    		self::$_query .= " WHERE " . $where;
	    	}
		}
	}

	private static function _buildOrderBy( $order )
	{
    	if ( $order )
    	{
    		self::$_query .= ' ORDER BY ' . $order;
    	}
	}

	public static function execute($data = array())
    {
    	$res = null;

    	if ( self::$_query != "" )
    	{
    		$res = self::$g_con->prepare(self::$_query);
    		$res->execute($data);
    	}
    	self::$_query   	= "";
    	return $res;
    }
}

