<?php 


define('DOC_ROOT', __DIR__);


require __DIR__ . "/classes/BaseClass.php";


$action = ucfirst($_REQUEST['action']);


require __DIR__ . "/classes/".$action."/index.php";

$class = new $action($_REQUEST);

$class->run();


exit;
