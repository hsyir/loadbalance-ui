<?php

if($_GET['loadbalance'] != 'true') {
    return false;
}

require "./classes/BaseClass.php";

$action = $_REQUEST['action'];

require "./classes/$action/index.php";
$class = new $action($_REQUEST);
$class->run();

