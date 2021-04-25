<?php


	namespace app\core\database;

	/**
	 * Class ORM
	 * This class is used to collect some useful database methods
	 * @package app\core\database
	 */
	class ORM
	{

		/**
		 * Database connection
		 * @var $connection
		 */
		protected $connection;
		/**
		 * Selected relation/table
		 * @var $table
		 */
		protected $table;

		/**
		 * Get all records of the target table
		 * @return array
		 */
		public function all() {
			// Get the target table
			$table = $this->table;
			// Execute query and return results
			return $this->query("SELECT * FROM $table");
		}

		/**
		 * Get a row of the target table
		 * @param array $where
		 * @return array
		 */
		public function row($where = []) {
			// Get the target table
			$table = $this->table;
			// Get WHERE condition
			$where = $this->get_where_condition($where);
			// Get params to bind
			$bindings = $this->bindings;

			// Execute query and return results
			return $this->query("SELECT * FROM $table $where LIMIT 1", $bindings);
		}

		/**
		 * Get records by id
		 * @param $id
		 * @return array
		 */
		public function id($id) {
			// Get the target table
			$table = $this->table;
			// Execute query and return results
			return $this->query("SELECT * FROM $table WHERE id = ?", [$id]);
		}

	}