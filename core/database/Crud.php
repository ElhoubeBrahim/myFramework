<?php


	namespace app\core\database;

	/**
	 * Class Crud
	 *
	 * This class is used to perform CRUD actions to the database
	 * CRUD = Create Read Update Delete
	 *
	 * @package app\core\database
	 */
	class Crud extends ORM
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
		 * Store params to bind temporary
		 * @var $bindings
		 */
		protected $bindings;

		/**
		 * Transform array of [col => val] to a string "WHERE col = val AND ..."
		 * @param array $where
		 * @return string
		 */
		public function get_where_condition($where = []) {
			// Init bindings params
			$this->bindings = [];

			// If $where is not empty
			if (count($where) > 0) {
				// Loop through conditions
				foreach ($where as $col => $val) {
					// Transform [col => val] to "col = ?"
					$where[] = "$col = ?";
					// Add the value to the bindings params
					$this->bindings[] = $val;
					// Remove the array of [col => val]
					unset($where[$col]);
				}
				// Transfome ["col1 = ?", "col2 = ?"] to "col1 = ? AND col2 = ?"
				$where = implode(' AND ', $where);
				// Add WHERE satatement before
				$where = "WHERE $where";
				// Return the where conditions string
				return $where;
			}

			// Else
			// Return empty string
			return '';
		}

		/**
		 * Perform a SELECT query to the current table
		 * @param array $params
		 * @return array
		 */
		public function select($params = []) {
			// Get the target table
			$table = $params['table'] ?? $this->table;
			// Get columns names [col1, col2, col3] => "col1, col2, col3"
			$columns = isset($params['columns']) ? implode(', ', $params['columns']) : '*';
			// Get WHERE condition
			$where = $this->get_where_condition($params['where'] ?? []);
			// Get the params to bind
			$bindings = $this->bindings;

			// Execute the query and return results
			return $this->query("SELECT $columns FROM $table $where", $bindings);
		}

		/**
		 * Perform an INSERT data query to the current table
		 * @param array $data
		 * @return integer
		 */
		public function insert($data = []) {
			// Get the target table
			$table = $this->table;

			// Get columns names [col1, col2, col3] => "col1, col2, col3"
			$columns = implode(', ', array_keys($data));
			// Get columns values
			$params = array_values($data);
			// Create placeholder for values [val1, val2, val3] => "?, ?, ?"
			$values = substr(str_repeat('?, ', count($params)), 0, -2);

			// Execute the query and return affected rows number
			return $this->query("INSERT INTO $table ($columns) VALUES ($values)", $params);
		}

		/**
		 * Perform an UPDATE data query to the current table
		 * @param array $data
		 * @return integer
		 */
		public function update($data = [], $where = []) {
			// Get the target table
			$table = $this->table;
			// Get the WHERE condition
			$where = (count($data) > 0) ? $this->get_where_condition($where) : '';
			// Init bindings array
			$bindings = [];

			// Loop through data to update
			foreach ($data as $col => $val) {
				// Transform [col => val] to "col = ?"
				$data[] = "$col = ?";
				// Add the value to the bindings params
				$bindings[] = $val;
				// Remove the array of [col => val]
				unset($data[$col]);
			}
			// Transform ["col1 = ?", "col2 = ?"] to "col1 = ?, col2 = ?"
			$data = implode(', ', $data);
			// Merge the bindings arrays
			$bindings = array_merge($bindings, $this->bindings);

			// Execute query and return the affected rows number
			return $this->query("UPDATE $table SET $data $where", $bindings);
		}

		/**
		 * Perform a DELETE data query to the current table
		 * @param array $where
		 * @return integer
		 */
		public function delete($where = []) {
			// Get the target table
			$table = $this->table;
			// Get the WHERE condition
			$where = $this->get_where_condition($where);
			// Get bindings
			$bindings = $this->bindings;

			// Execute query and return affected rows number
			return $this->query("DELETE FROM $table $where", $bindings);
		}

		/**
		 * Search a sentense into the current table column
		 * @param $column
		 * @param $string
		 * @return array
		 */
		public function search($column, $string) {
			// Get the target table
			$table = $this->table;
			// Execute the search query and return results
			return $this->query("SELECT * FROM $table WHERE $column LIKE ?", [$string]);
		}

	}