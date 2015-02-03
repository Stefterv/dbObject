# dbObject

### Usage
#### Example class:
	<?
	class Object extends databaseObject{
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