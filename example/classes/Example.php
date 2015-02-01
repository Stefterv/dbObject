<?
class Example extends databaseObject{
	protected static $dbName = "example";
	public $input;	
	function form_update(){
		?>
		<input type="text" <?$this->input("input");?>>
		<button type="submit" name="submit" value="create">Create class</button>
		<button type="submit" name="submit" value="update">update class</button>
		<button type="submit" name="submit" value="delete">delete class</button>
		<?
	}
}
?>