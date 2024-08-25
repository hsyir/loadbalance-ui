<?php





if(isset($_GET['loadbalance']) and $_GET['loadbalance'] == 'true') {
    require_once __DIR__ . "/../html/pages/maintenance/maintenance.php";
    $username = Maintenance::getTokenUser($userToken);
    require __DIR__ . '/loader.php';
}
