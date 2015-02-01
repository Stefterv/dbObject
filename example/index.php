<? include "dbObject.php" ;	
$tests = Example::findAll();
 ?>
 <? foreach($tests as $test): ?>
	<? $test->form("form_update"); ?>

 <? endforeach; ?>