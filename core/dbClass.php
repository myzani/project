<?php
class dbClass{
	public $_pid = "";
	public $_lastId = 0;
	public $_whereData = " WHERE ";
	public $conn;
	protected $_likeData = "";
	protected $_query = "";
	protected $_orderStmt = "";
	protected $_joinParam = "";
	protected $_data = array();
	protected $_where = array();
	protected $_like = array();

    public function __construct()
    {
		try{
            $this->conn = new PDO(ENGINE.":host=".HOST.";dbname=".DBNAME, DBUSER, DBPWD);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			die("Could not connect to database" . $e->getMessage());
		}
//		return $this->conn;
	}

	public function insertData($tblName, $data){
		$this->_query = "INSERT INTO $tblName";

		$stmt = $this->buildQuery($data);
		$stmt->execute();
		if($stmt->rowCount())
			return $this->conn->lastInsertId();
	}

	public function updateData($tblName, $data){
		$this->_query = "UPDATE $tblName SET ";
		$stmt = $this->buildQuery($data);
		$stmt->execute();
		if($stmt->rowCount())
			return true;
	}

	public function deleteData($tblName, $data){
		$this->_query = "DELETE FROM $tblName ";
		$stmt = $this->buildQuery($data);
		$stmt->execute();
		if($stmt->rowCount())
			return true;
	}

	public function directQuery($query){
		$result = array();
		$this->_query = $query;
		$stmt = $this->prepQuery();
		$stmt->execute();
		$result = $this->queryFetch($stmt);

		return $result;
	}

	public function selectData($tblName, $data, $numRows = 0){
		$result = array();
		$this->_query = "SELECT * FROM $tblName ";
		$stmt = $this->buildQuery($data);
		$stmt->execute();
		$result = $this->queryFetch($stmt);

		return $result;
	}

	public function selectMultiTbl($multiData, $numRows = 0){
		// SELECT t1.name, t2.salary FROM employee AS t1 INNER JOIN info AS t2 ON t1.name = t2.name;
		// tblName 	=> 'tblSamp'
		// colName1 => 'colSamp'
		// joinType => 'joinSamp'
		//$this->_whereData = " WHERE ";
		$result = array();
		$col = array_values($multiData['column']);
		$tbl = array_values($multiData['table']);
		$this->_query  = 'SELECT ' . implode(', ', $col) . ' FROM ' . implode(', ', $tbl) . $this->_whereData;
		$this->_query .= (!empty($this->_pid)) ? $this->_pid : '';
		$this->_query .= $this->_orderStmt;
		$stmt = $this->prepQuery();
		$stmt = $this->bindParam($stmt, $this->_where);
		$stmt->execute();
		$result = $this->queryFetch($stmt);
		return $result;
	}

	protected function dataExist($tableName, $data) {
		// $this->_whereData
	}

	public function whereData($data, $logic = '') {
		$keys = array_keys($data);
		$this->_where = $data;
		for ($i=0; $i < count($data); $i++) {
			if($i+1 < count($data)) {
				$this->_whereData .= $keys[$i] . '= ? ' . $logic . ' ';
			}else{
			 	$this->_whereData .= $keys[$i] . '= ?';
			}
		}
	}

	public function joinParam($data, $logic = ''){
		$this->_joinParam = "ON ";
		$keys = array_keys($data);
		for ($i=0; $i < count($data); $i++) {
			if($i+1 < count($data)) {
				$this->_joinParam .= $keys[$i] . '= ? ' . $logic . ' ';
			}else{
			 	$this->_joinParam .= $keys[$i] . '= ?';
			}
		}
	}

	public function filterLike($data, $logic = ''){
		$this->_likeData .= "LIKE ";
		$this->_like = $data;
		for($i=0; $i < count($data); $i++){
			if($i+1 < count($data)) {
				$this->_likeData .= "'%?%'" . $logic . ' ';
			}else{
			 	$this->_likeData .= "'%?%'";
			}
		}
	}

	public function orderData($data, $sort='ASC'){
		$this->_orderStmt = ' ORDER BY ' . implode(',', $data) . ' ' . $sort;
	}

	//Construct a query
	protected function buildQuery($data = "", $numRows = 0){
		$keys = "";
		$val = "";
		$count = 0;
		$hasData = false;

		if(gettype($data) == "array"){
			$keys = array_keys($data);
			$val = array_values($data);
			$count = count($data);
			$hasData = true;
			$_data = $data;
		}

		$cmdKey = $this->queryKey();

		switch ($cmdKey) {
			case 'C':
				$this->_query = $this->_query . "(" . implode(",", $keys) . ") VALUES(";
				for ($i=0; $i < $count; $i++)
					($i+1 < $count) ? $this->_query .= '?,' : $this->_query .= '?) ';
				break;
			case 'R':
				$this->_query = str_replace('*', implode(",", $val), $this->_query) . $this->_orderStmt;
				break;
			case 'U':
				$data = array_merge($data, $this->_where);
				for ($i=0; $i < $count; $i++)
					($i+1 < $count) ? $this->_query .= $keys[$i] . "=?, " : $this->_query .= $keys[$i] . "=? ";
				break;
		}

		if(!empty($this->_whereData) && $cmdKey != 'C'){
			$this->_query .= $this->_whereData;
		}
		if(!empty($this->_like) && $cmdKey != 'C' && $cmdKey != 'U'){
			$this->_query = str_replace('= ?', ' ', $this->_query);
			$this->_query .= $this->_likeData;
		}
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		 echo $this->_query . "<br />";
//		 print_r($data);
		// echo "Count: " . count($data) . "<br />";
		// call method to prepare the query
		$stmt = $this->prepQuery();
		if($hasData && $cmdKey != 'R'){
			$stmt = $this->bindParam($stmt, $data);
		}elseif ($cmdKey == 'R') {
			$data = $this->_like;
			$stmt = $this->bindParam($stmt, $data);
		}

		return $stmt;
	}

	protected function bindParam($stmt, $data){
		$i = 1;
		foreach ($data as $key => $value) {
			$stmt->bindValue($i, $value, $this->determineType($value));
			$i++;
		}
		return $stmt;
	}

	protected function queryFetch($stmt){
		$results = array();
		while($rows = $stmt->fetch(PDO::FETCH_OBJ)){
			$results[] = $rows;
		}

		return $results;
	}

	protected function determineType($item){
		$itemType = "";
		switch (gettype($item)) {
			case 'string':
				$itemType = PDO::PARAM_STR;
				break;
			case 'integer':
				$itemType = PDO::PARAM_INT;
				break;
			case 'boolean':
				$itemType = PDO::PARAM_BOOL;
				break;
			case 'NULL':
				$itemType = PDO::PARAM_NULL;
				break;
			default:
				$itemType = false;
				break;
		}
		return $itemType;
	}

	protected function lastId(){
		$this->_lastId = $this->conn->lastInsertId();
	}

	private function queryKey(){
		$queryCmd = array('C'=>'INSERT', 'R'=>'SELECT', 'U'=>'UPDATE');
		$toSearch = substr($this->_query, 0, 6);
		$cmdKey = array_search($toSearch, $queryCmd);
		return $cmdKey;
	}

	private function prepQuery(){
		if(!$stmt = $this->conn->prepare($this->_query)){
			trigger_error("Error in preparing the query!", E_USER_ERROR);
		}
		return $stmt;
	}

	public function __destruct(){
		$this->conn = null;
	}
}
?>
