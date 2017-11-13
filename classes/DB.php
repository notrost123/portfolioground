<?php

	class DB {

		private static $_instance = null;
		private $_pdo,               //new PDO
				$_query, 			 // $PDO->prepare();
				$_error = false, 	
				$_results, 			
				$_count = 0,		//rowCount();
				$_errorInfo = ''; 


		private function __construct() {
				/*
					By instanciating it (by Assigning it in private and create an instance to call it) it will stop the 
				*/
			try{

				$txtHost = Config::get('mysql/host');
				$txtDB = Config::get('mysql/db');
				$txtDBUsername = Config::get('mysql/username');
				$txtDBPassword = Config::get('mysql/password');


				$this->_pdo = new PDO("mysql:host=".$txtHost."; dbname=". $txtDB. "", $txtDBUsername , $txtDBPassword); 
				//echo "connected";
			} catch(PDOException $e) {	
				echo "ERROR -DB-27: ";
				die($e->getMessage());
			}

		}

		public static function getInstance(){
			/* 
				This is a sample of Instanciation, so that the connection of database will not iterate.
			*/
			if(!isset(self::$_instance)){
		
				self::$_instance = new DB();

			}
			
			return self::$_instance; 

		}

		public function query($sql, $params = array()) {
			$this->_error = false;
			$this->_errorInfo = ""; 
			if($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if(count($params)){
					foreach($params as $param) {
						/*
							by initializing $x = 1, technically, it means that we want to param value to the First ($x = 1) Question mark, so no and so fort. 
						*/
						$this->_query->bindValue($x, $param);
						$x++; 
					}
				}

			//	echo $this->_query->debugDumpParams() ."<br>";

				if($this->_query->execute()){
						$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); //*
						$this->_count = $this->_query->rowCount(); //* 
				} else {
					$this->_error = true; //*
					echo "ERROR FOUND:  <br><pre>";
					print_r($this->_query->errorInfo());
					echo "</pre>";
					$this->_errorInfo = $this->_query->errorInfo();
				}

			}

			return $this;

		}

		public function action($action, $table, $where = array()){
			if(count($where) >= 3){
				$operators = array('=', '>', '<', '>=', '<=' , 'IS' );

				$field = $where[0];
				$operator = $where[1];
				$value = $where[2];

				if(in_array($operator, $operators)){
					$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				}

				if(!$this->query($sql, array($value))->getErrors()){
					return $this; 
				}
			
			}

		}

		public function select($table, $where){
			return $this->action('SELECT *', $table, $where);
		}

		public function delete($table, $where){
			return $this->action('DELETE ', $table, $where);
		}

		public function insert($table, $fields = array()){
		
			$keys = array_keys($fields);
			//var_dump($keys);
			$values = null; 
			$x = 1;

			foreach($fields as $field){
				$values .= "?";
				if($x <count($fields)){
					$values .= ", ";
				}
				$x++;
			}

			$sql = "INSERT INTO {$table} (".implode(', ' ,$keys).") values ({$values})";

			if(!$this->query($sql, $fields)->getErrors()){
				return true; 
			}
			return false;

		}

		public function update($table, $id, $fields = array()){
			$set = '';
			$x = 1;

			foreach($fields as $name => $value){
				$set .= "{$name} = ?";
				if($x < count($fields)){
					$set .= ", ";
				}
				$x++; 
			} 

			$sql = "UPDATE {$table} SET {$set} WHERE Autokey = {$id}";

			//echo $sql ;
			if(!$this->query($sql, $fields)->getErrors()){
				return true;
			}
			return false; 

		}

		public function fetchArray(){
			return $this->_results;	
		}


		public function getErrors() {
			return $this->_error; 
		}

		public function count(){
			return $this->_count; 
		}

		public function getErrorInfo(){

			return $this->_errorInfo; 

		}

		public function first(){
			foreach($this->_results as $objRes){
				return $objRes;
				break;

			}

		}





	}

?>