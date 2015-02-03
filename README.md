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

#### Class requirements:
	<?
	class Object extends databaseObject{
		// add the table name of the database
		protected static $dbName = "Objects";
		// add the columns of the MySQL Database here
		public $variables;	
	}
	?>
The id variable is automatically added.
#### Example:
	<?
	class Example extends databaseObject{
		protected static $dbName = "Example";
		public $input;	
		public $test;
	}
	?>

## Getting stuff out of the database

#### Get stuff out of the database by query
	Object::find($query);
#### Get a single object out of the database
	Object::findSingle($query);
#### Get all rows out of the database
	Object::findAll($query);

## Putting stuff in the database
You can put an array into a object with:
	Object->update(array);

#### Example
	$array = array("input"=>"test","test"=>"1");
	$object = Object::findByID(1);
	$object->update($array);

Now the object's are updated but, it is not yet submitted to the database.
To send the current state of the object to the database use 
	$object->submit();
#### Example
	$array = array("input"=>"test","test"=>"1");
	$object = Object::findByID(1);
	$object->update($array);
	$object->submit();

## Removing stuff out of the database
remove stuff from the database is as simple as 
	$object->delete();


###### TO DO:
- auto redirect after form submission
- Documentation
- PDO implementation