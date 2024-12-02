<?php

require __DIR__ . "/vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

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

    // require_once __DIR__ . "/../html/pages/maintenance/maintenance.php";

    require __DIR__ . '/loader.php';
    // if (Maintenance::userHasAccess($userToken, $contentId, $contentType, $userData)) {

    // } else {
    //     die("403");
    // }

}


function proxyRequest()
{


    $client = new Client();

    try {
        // آدرس مقصد برای ارسال درخواست‌ها
        // $targetUrl = 'https://destination.example.com';

        $baseurl = "http://172.16.107.29/api/loadbalance";
        //$baseurl = "http://127.0.0.1:8000/api/loadbalance";

        $targetUrl = $baseurl . $_GET["url"];

        // اطلاعات درخواست ورودی
        $method = $_SERVER['REQUEST_METHOD']; // متد (GET, POST, PUT, DELETE, ...)
        $headers = getallheaders();          // هدرهای درخواست
        $body = file_get_contents('php://input'); // بدنه درخواست

        // ارسال درخواست به آدرس مقصد
        $request = new Request($method, $targetUrl, $headers, $body);
        $response = $client->send($request, ['http_errors' => false]);

        // بازگرداندن پاسخ به درخواست‌دهنده اصلی
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            header($name . ': ' . implode(', ', $values));
        }

        echo $response->getBody();

    } catch (Exception $e) {
        // مدیریت خطاها
        http_response_code(500);
        echo "An error occurred: " . $e->getMessage();
    }

}
