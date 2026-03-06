<?php
$buildId = $_GET['id'] ?? '';
$filePath = __DIR__ . '/output/' . basename($buildId) . '.apk';

if (!file_exists($filePath)) {
    http_response_code(404);
    die('File not found');
}

$logFile = __DIR__ . '/downloads.log';
$logEntry = date('Y-m-d H:i:s') . ' - ' . $_SERVER['REMOTE_ADDR'] . ' - ' . $buildId . "\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

header('Content-Type: application/vnd.android.package-archive');
header('Content-Disposition: attachment; filename="' . $buildId . '.apk"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache');
header('Pragma: no-cache');

readfile($filePath);
exit;
?>
