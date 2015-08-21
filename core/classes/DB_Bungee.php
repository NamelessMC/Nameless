<?php
class DB_Bungee {
	private static $_instance = null;
	private $_pdo, 
			$_query, 
			$_error = false, 
			$_results, 
			$_count = 0;

	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . $GLOBALS['mcdb']['inf_db']['host'] . ';dbname=' . $GLOBALS['mcdb']['inf_db']['db'], $GLOBALS['mcdb']['inf_db']['username'], $GLOBALS['mcdb']['inf_db']['password']);

		} catch(PDOException $e) {
			die("<strong>Error:<br /></strong><div class=\"alert alert-danger\">" . $e->getMessage() . "</div>Please check your Bungee database connection settings.");
		}
		
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB_Bungee();
		}
	
		return self::$_instance;
	
	}
	
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if(count($params)) {
				foreach($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
			
			
		}
		
		return $this;
		
	}

	public function createTable($name, $table_data, $other){
		$sql = "CREATE TABLE `{$name}` ({$table_data}) {$other}";
			if(!$this->query($sql)->error()) {
				return $this;
			}
		return false;
	}
	
	public function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=', '<>');
			
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			
			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}
	
	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}
	
	public function insert($table, $fields = array()) {
			$keys = array_keys($fields);
			$values = '';
			$x = 1;
			
			foreach($fields as $field) {
				$values .= '?';
				if ($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}
			
			
			$sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";
			
			if(!$this->query($sql, $fields)->error()){
				return true;
			}
			return false;
	}
	
	public function update($table, $id, $fields) {
		$set = '';
		$x = 1;
		
		foreach($fields as $name => $value){
			$set .= "{$name} = ?";
			
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
				
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		
		return false;
	}
	
	public function increment($table, $id, $field) {
		$sql = "UPDATE {$table} SET {$field} = {$field} + 1 WHERE id = {$id}";
		
		if(!$this->query($sql)->error()) {
			return true;
		}
		
		return false;
	}
	
	public function results() {
		return $this->_results;
	}
	
	public function first() {
		$results = $this->results();
		return $results[0];
	}
	
	public function error() {
		return $this->_error;
	}
	
	public function count() {
		return $this->_count;
	}

	public function lastid() {
		return $this->_pdo->lastInsertId();
	}
	
	public function orderAll($table, $order, $sort) {
		if(isset($sort)){
			$sql = "SELECT * FROM {$table} ORDER BY {$order} {$sort}";
		} else {
			$sql = "SELECT * FROM {$table} ORDER BY {$order}";
		}
				
		if(!$this->query($sql)->error()) {
			return $this;
		}
		return false;
	}

	public function orderWhere($table, $where, $order, $sort) {
		if(isset($sort)){
			$sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order} {$sort}";
		} else {
			$sql = "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order}";
		}
				
		if(!$this->query($sql)->error()) {
			return $this;
		}
		return false;
	}
}