<?php





if(isset($_GET['loadbalance']) and $_GET['loadbalance'] == 'true') {

    $contentId = isset($_GET['id']) ? $_GET['id'] : "";
    $contentType = isset($_GET['type']) ? $_GET['type'] : "";
    $userToken = isset($_GET['token']) ? $_GET['token'] : "";
    $userData = isset($_GET['data']) ? $_GET['data'] : "{}";

    require_once __DIR__ . "/../html/pages/maintenance/maintenance.php";
    if(Maintenance::userHasAccess($userToken, $contentId, $contentType, $userData)) {
        require __DIR__ . '/loader.php';
    }
    else{
      die("403");
    }

}
