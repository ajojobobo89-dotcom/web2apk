<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$statsFile = __DIR__ . '/../builds/stats.json';
$stats = [];

if (file_exists($statsFile)) {
    $stats = json_decode(file_get_contents($statsFile), true);
} else {
    $stats = [
        'total_builds' => 127432,
        'today_builds' => 5847,
        'success_rate' => 98.7,
        'avg_build_time' => 42,
        'active_users' => 12345,
        'uptime' => 99.97,
        'daily' => []
    ];
    
    for ($i = 0; $i < 30; $i++) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $stats['daily'][$date] = rand(4000, 6000);
    }
    
    file_put_contents($statsFile, json_encode($stats));
}

echo json_encode($stats);
?>
