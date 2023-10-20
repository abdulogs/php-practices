<?php

require_once 'app.php';

/* Database class */
class DB extends app {


	/*** Properties ***/
	private static $execute = [];
	private static $query;
	private static $sql = "";
	private static $table;


	/*** Table ***/
	public static function table($name){
		$table = " FROM " . $name . " ";
		self::$sql .= $table;
		return __CLASS__;
	}

	/*** Tables ***/
	public static function tables($names){
		$tables = " ";
		foreach ($names as $name) {
			$tables .= $name . ", ";
		}
		$tables = substr($tables, 0, -2);
		self::$sql .= $tables . " ";
		return __CLASS__;
	}

	/*** Columns ***/
	public static function columns($columns){
		$cols = " SELECT ";
		if ($columns !== "*" || $columns !== "all"){
			foreach ($columns as $name) {
				$cols .= $name . ", ";
			}
			self::$sql .= substr($cols, 0, -2);
		} else if ($columns == "*" || $columns == "all"){
			self::$sql .= " * ";
		}
	
		return __CLASS__;
	}

	/*** Columns more ***/
	public static function columnsmore($columns){
		$cols = ", ";
		foreach ($columns as $name) {
			$cols .= $name . ", ";
		}
		$cols = substr($cols, 0, -2);
		self::$sql .= $cols;
		return __CLASS__;
	}

	/*** Operator ***/
	public static function operator($operator = " AND "){
		$operator = " " . $operator . " ";
		self::$sql .= $operator;
		return __CLASS__;
	}

	/*** Brackets ***/
	public static function bracket($bracket = " ( ") {
		$bracket = " " . $bracket . " ";
		self::$sql .= $bracket;
		return __CLASS__;
	}

	/*** Distinct ***/
	public static function distinct(){
		$distinct =  " DISTINCT ";
		self::$sql .= $distinct;
		return __CLASS__;
	}

	/*** Null ***/
	public static function null($col, $type = "yes"){
		$type = ($type == "yes") ? "IS" : "IS NOT";
		$distinct =  " WHERE {$col} {$type} NULL";
		self::$sql .= $distinct;
		return __CLASS__;
	}

	/*** Delete ***/
	public static function delete($table){
		$delete = " DELETE FROM {$table}";
		self::$sql .= $delete;
		return __CLASS__;
	}

	/*** Update ***/
	public static function update($table ,$data) {
		$query = " UPDATE {$table} ";
		self::$execute = array_merge(array_values($data), self::$execute);
		$keys = array_keys($data);
		$query .= " SET ";

		$cols = "";
		for ($i = 0; $i < count($data); ++$i) {
			$cols  .= $keys[$i] . " = ? ,";
		}
		$cols = substr($cols, 0, -1);

		$query .= $cols;
		self::$sql .= $query;
		return __CLASS__;
	}

	/*** Update plus ***/
	public static function updateplus($col, $increment = 1){
		$updateplus = " SET {$col} = {$col} + {$increment}";
		self::$sql .= $updateplus;
		return __CLASS__;
	}

	/*** Update minus ***/
	public static function updateminus($col, $decrement = 1){
		$updateminus = " SET {$col} = {$col} - {$decrement}";
		self::$sql .= $updateminus;
		return __CLASS__;
	}

	/*** Insert ***/
	public static function insert($table, $data){
		$query = " INSERT INTO {$table} ";
		self::$execute = array_merge(array_values($data), self::$execute);
		$query .= "(" . implode(",", array_keys($data)) . ")";
		$vals = "";
		for ($i = 0; $i < count($data); ++$i) {
			$vals .= "?,";
		}
		$vals = substr($vals, 0, -1);
		$query .= " VALUES (" . $vals . ")";
		self::$sql .= $query;
		return __CLASS__;
	}

	/*** Union ***/
	public static function union($type = ""){
		$union = " UNION " . $type;
		self::$sql .= $union;
		return __CLASS__;
	}

	/*** Group By ***/
	public static function groupby($col){
		$group = " GROUP BY " . $col;
		self::$sql .= $group;
		return __CLASS__;
	}

	/*** Having ***/
	public static function having($condition){
		$having = $condition;
		self::$sql .= $having;
		return __CLASS__;
	}

	/*** Count ***/
	public static function count($name, $alias = ""){
		$alias = "AS " . $alias;
		$count = " COUNT({$name}) {$alias}";
		self::$sql .= $count;
		return __CLASS__;
	}

	/*** Sum ***/
	public static function sum($name, $alias = ""){
		$alias = "AS " . $alias;
		$count = " SUM({$name}) {$alias}";
		self::$sql .= $count;
		return __CLASS__;
	}

	/*** Average ***/
	public static function avg($name, $alias = ""){
		$alias = "AS " . $alias;
		$count = " AVG({$name}) {$alias}";
		self::$sql .= $count;
		return __CLASS__;
	}

	/*** Min ***/
	public static function min($name, $alias = ""){
		$alias = "AS " . $alias;
		$count = " MIN({$name}) {$alias}";
		self::$sql .= $count;
		return __CLASS__;
	}

	/*** Max ***/
	public static function max($name, $alias = ""){
		$alias = "AS " . $alias;
		$count = " MAX({$name}) {$alias}";
		self::$sql .= $count;
		return __CLASS__;
	}

	/*** Where ***/
	public static function where($condition){
		$where = "";
		if (!empty($condition)) {
			self::$execute = array_merge(self::$execute, array_values($condition));
			$where .= " WHERE ( ";
			for ($i = 0; $i < count($condition); ++$i) {
				$where .= array_keys($condition)[$i] . " = ? AND ";
			}
			$where = substr($where, 0, -4)." ) ";
		} else {
			$where = " WHERE ";
		}
		self::$sql .= $where;
		return __CLASS__;
	}

	/*** Search ***/
	public static function search($condition){
		self::$execute = array_merge(self::$execute, array_values($condition));
		$where = " WHERE (";
		for ($i = 0; $i < count($condition); ++$i) {
			$where .= array_keys($condition)[$i] . " LIKE '%' ? '%' OR ";
		}
		$where = substr($where, 0, -4);
		self::$sql .= $where . " ) ";
		return __CLASS__;
	}

	/*** OR ***/
	public static function or($condition, $or = false){
		self::$execute = array_merge(self::$execute, array_values($condition));
		if ($or == False) {
			$where = " OR (";
		} else {
			$where = " WHERE (";
		}

		for ($i = 0; $i < count($condition); ++$i) {
			$where .= array_keys($condition)[$i] . " = ? OR ";
		}
		$where = substr($where, 0, -4);

		self::$sql .= $where . "  ) ";
		return __CLASS__;
	}

	/*** AND ***/
	public static function and($condition, $and = false){
		self::$execute = array_merge(self::$execute, array_values($condition));
		if ($and == False) {
			$where = " AND (";
		} else {
			$where = " WHERE ( ";
		}

		for ($i = 0; $i < count($condition); ++$i) {
			$where .= array_keys($condition)[$i] . " = ? AND ";
		}
		$where = substr($where, 0, -4);

		self::$sql .= $where . ") ";
		return __CLASS__;
	}

	/*** Between ***/
	public static function between($col, $condition, $operator = ""){
		self::$execute = array_merge(self::$execute, array_values($condition));
		$between = " " . $operator . " " . $col . " BETWEEN ";
		for ($i = 0; $i < count($condition); ++$i) {
			$between .= " ? AND";
		}
		$between = substr($between, 0, -4);
		self::$sql .= $between;
		return __CLASS__;
	}

	/*** In ***/
	public static function in($col, $condition, $operator = ""){
		self::$execute = array_merge(self::$execute, array_values($condition));
		$in = " " . $operator . " " . $col . " IN (";
		for ($i = 0; $i < count($condition); ++$i) {
			$in .= "?,";
		}
		$in = substr($in, 0, -1);
		self::$sql .= $in . ")";
		return __CLASS__;
	}

	/*** Join ***/
	public static function join($joins, $type = "INNER"){
		$join = "";
		$keys = array_keys($joins);
		for ($i = 0; $i < count($joins); $i++) {
			$join .= $type . " JOIN " . $keys[$i] . " ON ";
			foreach ($joins[$keys[$i]] as $key => $value) {
				$join .= $key . " = " . $value . " ";
			}
		}
		self::$sql .= $join;
		return __CLASS__;
	}

	/*** Self Join ***/
	public static function joinself($joins)
	{
		$join = " WHERE ";
		$keys = array_keys($joins);
		for ($i = 0; $i < count($joins); $i++) {
			foreach ($joins[$keys[$i]] as $key => $value) {
				$join .= $key . " {$keys[$i]} " . $value . " ";
			}
		}
		self::$sql .= $join;
		return __CLASS__;
	}

	/*** Custom Where ***/
	public static function custom($condition, $values){
		self::$execute = array_merge(self::$execute, $values);
		$where = " ";
		$keys = array_keys($condition);
		for ($i = 0; $i < count($condition); $i++) {
			foreach ($condition[$keys[$i]] as $key => $value) {
				$where .= $key . " {$keys[$i]} " . $value . " ";
			}
		}
		self::$sql .= $where;
		return __CLASS__;
	}

	/*** Order ***/
	public static function order($column, $type = ""){
		$order = " ORDER BY {$column} {$type}";
		self::$sql .= $order;
		return __CLASS__;
	}

	/*** Sort ***/
	public static function sort($type){
		$sort = " " . $type . " ";
		self::$sql .= $sort;
		return __CLASS__;
	}

	/*** Limit ***/
	public static function limit($limit = ""){
		$limit = " LIMIT " . $limit . " ";
		self::$sql .= $limit;
		return __CLASS__;
	}

	public static function exists($table, $columns, $where, $type = "one"){
		$check = DB::columns($columns)::table($table)::where($where)::execute();
		$check = DB::fetch($type);
		return $check;
	}

	/*** Paging ***/
	public static function paging($page, $limit){
		$offset = ($page - 1) * $limit;
		$limit = " LIMIT {$offset} , {$limit} ";
		self::$sql .= $limit;
		return __CLASS__;
	}

	/*** Execute ***/
	public static function execute(){
		try {
			self::$sql;
			$result = parent::$con->prepare(self::$sql);
			if (count(self::$execute) == 0) {
				$result->execute();
			} else {
				$result->execute(self::$execute);
			}

			self::$sql = "";
			array_splice(self::$execute, 0);
			return self::$query = $result;
		} catch (Exception $e) {
			$error = "";
			$error .= "<h3 style='font-size:20px;color:red;font-family:arial;margin:10px 0;'>Opps error detected</h3>";
			$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Code :</b> {$e->getCode()}</p>";
			$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Line number :</b> {$e->getLine()}</p>";
			$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Filename</b> :</b> {$e->getFile()}</p>";
			$error .= "<p style='font-size:14px;font-family:arial;margin:2px 0;'><b>Message</b> :</b> {$e->getMessage()}</p>";
			$error .= "<br>";
			echo $error;
		}
	}

	/*** Fetch ***/
	public static function fetch($type = "all"){
		$result = self::$query->setFetchMode(PDO::FETCH_ASSOC);
		if ($type == "all") {
			$result = self::$query->fetchall();
		}
		if ($type == "one") {
			$result = self::$query->fetch();
		}
		return $result;
	}

	/*** Last Id ***/
	public static function lastid(){
		$lastid = parent::$con->lastInsertId();
		return $lastid;
	}

	/*** Test ***/
	public static function test(){
		echo self::$sql;
		return __CLASS__;
	}

	/*** Bind Values ***/
	public static function values(){
		echo "<pre>";
		print_r(self::$execute);
		echo "</pre>";
		return __CLASS__;
	}

	/*** Connection End ***/
	function __destruct(){
		$this->con = NULL;
		if ($this->con == null) {
			return true;
		}
	}
}
