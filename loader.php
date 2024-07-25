<?php 


define('DOC_ROOT', __DIR__);


//var_dump($_REQUEST);
require __DIR__ . "/classes/BaseClass.php";

//die(__DIR__ . "/classes/{$action}/index2.php");

$action = ucfirst($_REQUEST['action']);
//var_dump($action);


//die(__DIR__ . "/classes/".$action."/index.php");
require __DIR__ . "/classes/".$action."/index.php";

$class = new $action($_REQUEST);

$class->run();


exit;
