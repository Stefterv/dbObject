<? include "../dbObject.php" ;	
loadClasses("classes");
$tests = Test::findAll();
 ?>
 <? foreach($tests as $test): ?>
	<? $test->form("form_update"); ?>

 <? endforeach; ?>