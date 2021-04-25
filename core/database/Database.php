<?php


	namespace app\core\database;

	use PDO;

	/**
	 * Class Database
	 * This class is used to deal with database in general
	 * creating connection, Executing queries, CRUD ...
	 * @package app\core\database
	 */
	class Database extends Crud
	{

		// Database credentials
		private $hostname;
		private $username;
		private $password;
		private $database;

		/**
		 * Database connection
		 * @var $connection
		 */
		protected $connection;
		/**
		 * Selected table
		 * @var $table
		 */
		protected $table = null;

		/**
		 * Database constructor
		 * @param $config
		 */
		public function __construct($config) {
			// Set database credentials
			$this->hostname = $config['hostname'] ?? 'localhost';
			$this->username = $config['username'] ?? 'root';
			$this->password = $config['password'] ?? '';
			$this->database = $config['database'] ?? 'my_framework';

			// Connect to the database
			$this->connect();
		}

		/**
		 * Connect to the database
		 */
		public function connect() {
			try {
				// Create new PDO object
				$this->connection = new PDO(
					"mysql:host=$this->hostname;dbname=$this->database",
					$this->username,
					$this->password
				);

				// Set the charset to utf8, to support multiple characters
				$this->connection->exec("SET CHARACTER SET utf8");

			} catch (\PDOException $e) { }
		}

		/**
		 * Close the database connection
		 */
		public function disconnect() {
			$this->connection = null;
		}

		/**
		 * Get the connection
		 * @return PDO
		 */
		public function connection() {
			return $this->connection;
		}

		/**
		 * Set the target table
		 * @param $table
		 * @return $this
		 */
		public function table($table) {
			// If table exists in database
			if ($this->table_exists($table))
				// Set the table
				$this->table = $table;
			// Return the Database object
			return $this;
		}

		/**
		 * Check if table exists in database
		 * @param $table
		 * @return bool
		 */
		public function table_exists($table) {
			// Execute the search table query
			$result = $this->query("SHOW TABLES LIKE '$table'");
			// return true/false
			return count($result) > 0;
		}

		/**
		 * Parse params into the SQL query
		 * @param $statement
		 * @param $params
		 */
		protected function bind_params($statement, $params) {
			// Loop through params
			foreach ($params as $key => $value) {
				// Bind value
				$statement->bindValue(
					(is_string($key)) ? ":$key" : $key + 1,
					$value,
					(is_int($value)) ? PDO::PARAM_INT : PDO::PARAM_STR
				);
			}
		}

		/**
		 * Get the query execution result
		 * @param $stmt
		 * @param $operation
		 * @return array|integer|null
		 */
		private function get_result($stmt, $operation) {
			// If the query was SELECT or SHOW
			if ($operation === 'SELECT' or $operation === 'SHOW') {
				// Return array of records
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} elseif ( // Else if
				$operation === 'INSERT' or
				$operation === 'UPDATE' or
				$operation === 'DELETE'
			) {
				// Return the affected rows number
				return $stmt->rowCount();
			}

			// Else
			return null;
		}

		/**
		 * Execute the given query
		 * @param $query
		 * @param array $params
		 * @return array|integer|null
		 */
		public function query($query, $params = []) {
			// Get the query
			$query = trim(str_replace("\r", ' ', $query));
			// Prepare the query
			$stmt = $this->connection->prepare($query);
			// Parse params
			$this->bind_params($stmt, $params);
			// Execute the query
			$stmt->execute();

			// Get the query type
			$operation = strtoupper(explode(' ', preg_replace("/\s+|\t+|\n+/", " ", $query))[0]);
			// Return results
			return $this->get_result($stmt, $operation);
		}

	}