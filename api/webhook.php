<?php
header('Content-Type: application/json');
require_once 'config.php';

$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

$event = $data['event'] ?? 'unknown';
$buildId = $data['build_id'] ?? '';
$status = $data['status'] ?? '';

$logFile = __DIR__ . '/../logs/webhook.log';
$logEntry = date('Y-m-d H:i:s') . " - Event: $event - Build: $buildId - Status: $status\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

if ($event === 'build_completed') {
    $webhookUrl = getWebhookUrl($data['user_id'] ?? '');
    if ($webhookUrl) {
        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'type' => 'build_success',
            'build_id' => $buildId,
            'download_url' => getDownloadUrl($buildId),
            'timestamp' => date('Y-m-d H:i:s')
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}

echo json_encode(['success' => true]);

function getWebhookUrl($userId) {
    $webhooks = [
        'user123' => 'https://discord.com/api/webhooks/...',
        'user456' => 'https://hooks.slack.com/services/...'
    ];
    return $webhooks[$userId] ?? null;
}

function getDownloadUrl($buildId) {
    return "https://apk.lifx.com/download/$buildId";
}
?>
