<?php

// establish connection
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("Error connecting to database");
mysql_select_db(DB_NAME) or die ("Error selecting database");

// set user name for error messages
if(isset($_SESSION['user'])) { $user_error_name = $_SESSION['user']; } else { $user_error_name = "guest"; }

// escape characters for sql
function esc($value) {
	if(get_magic_quotes_gpc()) $str = stripslashes($value);
	
	// replace special characters
	$str = replace_schars($str);
	
	return mysql_real_escape_string(trim($str));
}

// select
function samq($table, $what="*", $join=NULL, $where=NULL, $order=NULL) {
	global $user_error_name;
	
	// build query
	$q = "SELECT " . trim($what) . " FROM " . trim($table);
	if(!is_null($join))  $q.= " " . trim($join);
	if(!is_null($where)) $q.= " WHERE " . trim($where);
	if(!is_null($order)) $q.= " ORDER BY " . trim($order);

	// capture result and return as array
	$result = mysql_query($q) or error($q, mysql_error(), $user_error_name);
	for($i = 0; $result_array[$i] = mysql_fetch_assoc($result); $i++) ;
	array_pop($result_array);
	return $result_array;
}

// insert
function samq_i($table, $columns, $values) {
	global $user_error_name;
	
	// build query
	$q = "INSERT INTO " . trim($table);

	// columns
	if(!is_null($columns)) {
		$q.= " (";
		$count = count($columns);
		$i = 1;
		foreach($columns as $x) {
			$q.= esc($x);
			if($i != $count) $q.= ",";
			$i++;
		}
		$q.= ")";
	}

	// values
	$q.= " VALUES (";
	$count = count($values);
	$i = 1;
	foreach($values as $x) {
		if(is_null($x)) { $q.= "NULL"; } else {
			if(!is_numeric(trim($x))) $q.= "'";
			$q.= esc($x);
			if(!is_numeric(trim($x))) $q.= "'";
		}
		if($i != $count) $q.= ",";
		$i++;
	}
	$q.= ")";

	// execute query
	mysql_query($q) or error($q, mysql_error(), $user_error_name);
	return true;
}

// update
function samq_u($table, $columns, $values, $where=NULL) {
	global $user_error_name;
	
	// convert lists to arrays, and combine
	$combined = array_combine($columns,$values);
	$count = count($combined);

	// build query
	$q = "UPDATE " . trim($table) . " SET";
	$i = 1;
	foreach($combined as $column => $value) {
		$q.= " " . esc($column) . " = ";
		
		if(is_null($value)) { $q.= "NULL"; } else {
			if(!is_numeric(trim($value))) $q.= "'";
			$q.= esc($value);
			if(!is_numeric(trim($value))) $q.= "'";
		}
		if($i != $count) $q.= ",";
		$i++;
	}
	$q.= " ";
	if(!is_null($where)) $q.= "WHERE " . trim($where);
	
	// execute query
	mysql_query($q) or error($q, mysql_error(), $user_error_name);
	return true;
}

// delete
function samq_d($table, $where=NULL) {
	global $user_error_name;
	
	// build query
	$q = "DELETE FROM " . trim($table);
	if(!is_null($where)) $q.= " WHERE " . trim($where);

	// execute query
	mysql_query($q) or error($q, mysql_error(), $user_error_name);
	return true;
}

// custom query
function samq_c($q,$r=NULL) {
	global $user_error_name;
	
	// execute and capture result
	$result = mysql_query($q) or error($q, mysql_error(), $user_error_name);

	// if result requested, convert to array
	if($r==1) {
		for($i = 0; $result_array[$i] = mysql_fetch_assoc($result); $i++);
		array_pop($result_array);
	}
	
	// if result requested, return array
	if($r==1) { return $result_array; } else { return true; }
}
?>