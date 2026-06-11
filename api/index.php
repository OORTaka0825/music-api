<?php
// 强制 HTTPS，避免生成 http:// 的 lrc/url/pic
$_SERVER['HTTPS'] = 'on';
$_SERVER['REQUEST_SCHEME'] = 'https';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (empty($_GET['server'])) {
    $_GET['server'] = 'netease';
}

if (empty($_GET['type'])) {
    $_GET['type'] = 'playlist';
}

if (empty($_GET['id'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'missing id'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

ob_start();

require __DIR__ . '/../index.php';

$output = ob_get_clean();

// 兜底：把当前域名下生成的 http 链接全部替换成 https
$host = $_SERVER['HTTP_HOST'] ?? '';
if ($host !== '') {
    $output = str_replace('http://' . $host, 'https://' . $host, $output);
}

header('Content-Type: application/json; charset=utf-8');
echo $output;