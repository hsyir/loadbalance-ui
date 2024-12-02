<?php


if (isset($_GET['loadbalance']) and $_GET['loadbalance'] == 'true') {

    $contentId = isset($_GET['id']) ? $_GET['id'] : "";
    $contentType = isset($_GET['type']) ? $_GET['type'] : "";
    $userToken = isset($_GET['token']) ? $_GET['token'] : "";
    $userData = isset($_GET['data']) ? $_GET['data'] : "{}";

    if ($_GET["action"] == "asset") {
        require __DIR__ . '/loader.php';
        return;
    }

    if ($_GET["action"] == "proxy") {
        echo proxyRequest();
        return;
    }

    require_once __DIR__ . "/../html/pages/maintenance/maintenance.php";

    if (Maintenance::userHasAccess($userToken, $contentId, $contentType, $userData)) {
        require __DIR__ . '/loader.php';
    } else {
        die("403");
    }

}


function proxyRequest()
{

    // آدرس IP یا URL مقصد
    $baseurl = "http://127.0.0.1:8000/api/loadbalance";

    $targetUrl = $baseurl . $_GET["url"];
    // دریافت متد درخواست
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // دریافت هدرها
    $headers = getallheaders();

    // دریافت بدنه درخواست
    $requestBody = file_get_contents('php://input');

    // مقداردهی اولیه cURL
    $ch = curl_init($targetUrl);

    // تنظیم متد درخواست
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);

    // ارسال هدرها
    $curlHeaders = [];
    foreach ($headers as $key => $value) {
        $curlHeaders[] = "$key: $value";
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);

    // ارسال بدنه درخواست
    if (!empty($requestBody)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
    }

    // دریافت پاسخ از مقصد
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // اجرای درخواست
    $response = curl_exec($ch);

    // بررسی خطای احتمالی
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }

    // بستن cURL
    curl_close($ch);

    // نمایش پاسخ دریافت شده از مقصد
    return $response;

}
