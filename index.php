<?php





if(isset($_GET['loadbalance']) and $_GET['loadbalance'] == 'true') {

    $contentId = isset($_POST['id']) ? $_POST['id'] : "";
    $contentType = isset($_POST['type']) ? $_POST['type'] : "";
    $userToken = isset($_POST['token']) ? $_POST['token'] : "";
    $userData = isset($_POST['data']) ? $_POST['data'] : "{}";

    require_once __DIR__ . "/../html/pages/maintenance/maintenance.php";
    if(Maintenance::userHasAccess($userToken, $contentId, $contentType, $userData)) {
        require __DIR__ . '/loader.php';
    }
    else{
      die("403");
    }

}
