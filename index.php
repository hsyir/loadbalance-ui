<?php


require_once __DIR__ . "/../html/pages/maintenance/maintenance.php";



if(isset($_GET['loadbalance']) and $_GET['loadbalance'] == 'true') {
    $username = Maintenance::getTokenUser($userToken);
    echo ($username);
    require __DIR__ . '/loader.php';
}
