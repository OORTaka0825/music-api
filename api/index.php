<?php
// 关键：禁止 PHP warning/notice 直接输出成 HTML，避免 JSON.parse 报错
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

// 允许博客跨域调用
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 防止主题偶尔空参数请求导致 PHP warning
if (!isset($_GET['server']) || $_GET['server'] === '') {
    $_GET['server'] = 'netease';
}

if (!isset($_GET['type']) || $_GET['type'] === '') {
    $_GET['type'] = 'playlist';
}

if (!isset($_GET['id']) || $_GET['id'] === '') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'missing id'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require __DIR__ . '/../index.php';