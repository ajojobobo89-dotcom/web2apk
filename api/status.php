<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$apis = [
    'web2apk' => checkWeb2APK(),
    'appyet' => checkAppYet(),
    'mit' => checkMIT()
];

function checkWeb2APK() {
    $ch = curl_init('https://api.web2apk.com/v1/status');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200 ? 'online' : 'offline';
}

function checkAppYet() {
    $ch = curl_init('https://www.appyet.com/api/status');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200 ? 'online' : 'offline';
}

function checkMIT() {
    $ch = curl_init('https://ai2.appinventor.mit.edu/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200 ? 'online' : 'offline';
}

echo json_encode([
    'web2apk' => $apis['web2apk'],
    'appyet' => $apis['appyet'],
    'mit' => $apis['mit'],
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
