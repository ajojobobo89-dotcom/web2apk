<?php
define('CACHE_ENABLED', true);
define('CACHE_DIR', __DIR__ . '/../cache/');
define('CACHE_TTL', 3600); // 1 hour

function cache_get($key) {
    if (!CACHE_ENABLED) return null;
    
    $file = CACHE_DIR . md5($key) . '.cache';
    if (!file_exists($file)) return null;
    
    $data = file_get_contents($file);
    $cache = unserialize($data);
    
    if (time() > $cache['expires']) {
        unlink($file);
        return null;
    }
    
    return $cache['data'];
}

function cache_set($key, $data, $ttl = CACHE_TTL) {
    if (!CACHE_ENABLED) return false;
    
    if (!is_dir(CACHE_DIR)) {
        mkdir(CACHE_DIR, 0777, true);
    }
    
    $cache = [
        'expires' => time() + $ttl,
        'data' => $data
    ];
    
    $file = CACHE_DIR . md5($key) . '.cache';
    return file_put_contents($file, serialize($cache));
}

function cache_clear($key = null) {
    if ($key) {
        $file = CACHE_DIR . md5($key) . '.cache';
        if (file_exists($file)) unlink($file);
    } else {
        array_map('unlink', glob(CACHE_DIR . '*.cache'));
    }
}
?>
