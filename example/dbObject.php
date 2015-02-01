<?php
	
	/*
	Example dbObject:
<?
class Object extends databaseObject{
	protected static $dbName = "Objects";
	public $variables;	
}
?>
	*/
	require("config.php");

	// !CONNECT
	global $db;
	$db = new mysqli(DB_LOC, DB_USER, DB_PASS, DB_NAME);
	if ($db->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}

	class databaseObject{
		public $id = 0;
		public static function find($query=""){
			global $db;
			$result = $db->query($query);
			$objectArray = array();
			if($result){
				while($row = $result->fetch_assoc()){
					$objectArray[] = static::instance($row);
				}
				return $objectArray;
			}else{
				return false;
			}
		}
		public static function findByID($id){
			return static::findSingle("SELECT * FROM ".static::$dbName." WHERE id={$id} LIMIT 1");
		}
		public static function findAll(){
			return static::find("SELECT * FROM ".static::$dbName);
		}
		public static function findSingle($query=""){
			$result = static::find($query); 
			return !empty($result) ? array_shift($result) : false;
		}
		public static function findByAttr($array){
			global $db;
			$query = "SELECT * FROM ".static::$dbName." WHERE ";
			$first = true;
			foreach ($array as $key => $value) {
				if($first)$first=false;
				else $query .= " AND ";
				$query .= $key."'".$db->real_escape_string($value)."'";				
			}
			return static::find($query);
		}
		public static function findSingleByAttr($array){
			$result = static::findByAttr($array); 
			return !empty($result) ? array_shift($result) : false;
		}
		private static function instance($record){
			$className = get_called_class();
			$object = new $className;
			$object->parser($record);
			return $object;
		}
		private function hasAttr($attribute){
			$objectVars = get_object_vars($this);
			return array_key_exists($attribute, $objectVars);
		}
		public function submit($echo = false){
			//Check if ID exists
			global $db;
			$query = "SHOW COLUMNS FROM ".static::$dbName;
			$columns = $db->query($query);
			$set = "SET ";
			$added = false;
			while($column = $columns->fetch_assoc()){
				$variableName = $column['Field'];
				
				if(isset($this->$variableName)){
					if($added){
						$set .=", ";
					}
					$set .= $variableName."='".$db->real_escape_string($this->$variableName)."'";
					$added = true;
				}
				
			}
			$query = "SELECT * FROM ".static::$dbName." WHERE id={$this->id}";
			$result = $db->query($query);
			if($result && $result->num_rows >0){
				//Update the item in the database
				$query = "UPDATE ".static::$dbName." ".$set. " WHERE id={$this->id}";
				echo $echo?$query:"";
				$result = $db->query($query);
				
			}else{
				//Create the item in the database
				$query = "INSERT INTO ".static::$dbName." ".$set;
				echo $echo?$query:"";
				$result = $db->query($query);
				$this->id = $db->insert_id;
			}
		}
		public function remove(){
			global $db;
			$query = "DELETE FROM ".static::$dbName."
					WHERE id={$this->id}";
			$db->query($query);
		}
		public function parser($record,$escape=true){
			global $db;
			foreach($record as $attribute=>$value){
				if($this->hasAttr($attribute)){
					$this->$attribute = $value;
				}
			}
		}
		public function escape($var){
			global $db;
			return $db->real_escape_string($var);
		}
		public function selected($valuename,$equals,$expr="selected"){
			if($this->$valuename == $equals){
				echo $expr;
			}
		}
		public function input($valuename){
			?>name="<?=$valuename?>" value="<?=$this->$valuename?>"<? 
		}
		function form($functionName){
			?>
			<form action="dbObject.php" enctype="multipart/form-data" method="post">
				<input type="hidden" name="id" value="<?=$this->id?>">
				<input type="hidden" name="class" value="db_<?=get_class($this)?>">
				<? $this->$functionName() ?>
			</form>
			<?
		}
		static function create($values){
			$inst = new static();
			$inst->parser($values);
			$inst->submit();
			return $inst;
		}
		static function update($id,$values){
			$inst = static::findByID($id);
			if($inst){
				$inst->parser($_POST);
				$inst->submit();
			}
			return $inst;
		}
		static function delete($id){
			$inst = static::findByID($id);
			if($inst){
				$inst->remove();
			}
		}
	}
	function __autoload($name) {
		include_once("classes/".$name.".php");
	}
?>

<?
// Get form submission
if(isset($_POST['submit'])):
	// get the class
	if(!isset($_POST['class'])){
		echo "ERROR: No Class Specified";
		die("No Class specified");
	}
	$class = substr($_POST['class'],3);
	// check if class exists
	if(!class_exists($class)){
		echo "ERROR: Class \'{$class}\' does not exist";
		die("Class \'{$class}\' does not exist");
	}
	$inst;
	if($_POST['submit'] == "create"){
		$class::create($_POST);
	}
	if($_POST['submit'] == "delete"){
		$class::delete($_POST['id']);
	}
	if($_POST['submit'] == "update"){
		$class::update($_POST['id'],$_POST);
	}
endif;
?>