# dbObject

### Usage
#### Getting started
- Place the "dbObject.php" in your php website
- Add a config.php with the following code
	<?
	define("DB_LOC", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "root");
	define("DB_NAME", "dbObject_example");
	?>
- Change the login and the database name and you should be ready to go.

#### Example class:
	<?
	class Object extends databaseObject{
		// add the table name of the database
		protected static $dbName = "Objects";
		// add the columns of the MySQL Database here
		public $variables;	
	}
	?>
#### Get stuff out of the database by query
	Object::find($query);
#### Get a single object out of the database
	Object::findSingle($query);


###### TO DO:
- auto redirect after form submission
- Documentation
- PDO implementation