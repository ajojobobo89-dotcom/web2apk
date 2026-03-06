<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action'];

switch ($action) {
    case 'build_html':
        $response = buildFromHTML($_POST);
        break;
    case 'build_url':
        $response = buildFromURL($_POST);
        break;
    case 'build_zip':
        $response = buildFromZIP($_FILES['zip_file'] ?? null, $_POST);
        break;
    case 'check_status':
        $response = ['success' => true, 'status' => checkBuildStatus($_POST['build_id'] ?? '')];
        break;
}

echo json_encode($response);

function buildFromHTML($data) {
    $appName = preg_replace('/[^a-zA-Z0-9]/', '', $data['app_name'] ?? 'App');
    $buildId = uniqid('build_');
    $buildDir = __DIR__ . '/../builds/' . $buildId;
    
    if (!mkdir($buildDir, 0777, true)) {
        return ['success' => false, 'message' => 'Failed to create build directory'];
    }
    
    file_put_contents($buildDir . '/index.html', $data['html'] ?? '');
    
    $manifest = generateManifest($data);
    file_put_contents($buildDir . '/AndroidManifest.xml', $manifest);
    
    $activity = generateActivity($data['app_name'] ?? 'App', $data['package_name'] ?? 'com.lifx.app');
    $activityPath = $buildDir . '/src/main/java/' . str_replace('.', '/', $data['package_name'] ?? 'com.lifx.app');
    mkdir($activityPath, 0777, true);
    file_put_contents($activityPath . '/MainActivity.java', $activity);
    
    $apkPath = compileAPK($buildDir, $buildId, $data['package_name'] ?? 'com.lifx.app');
    
    if ($apkPath && file_exists($apkPath)) {
        updateStats();
        return [
            'success' => true,
            'build_id' => $buildId,
            'download_url' => '/download.php?id=' . $buildId,
            'file_size' => filesize($apkPath),
            'filename' => $appName . '.apk'
        ];
    }
    
    return ['success' => false, 'message' => 'Build failed'];
}

function buildFromURL($data) {
    $url = filter_var($data['url'] ?? '', FILTER_VALIDATE_URL);
    if (!$url) {
        return ['success' => false, 'message' => 'Invalid URL'];
    }
    
    $html = @file_get_contents($url);
    if (!$html) {
        return ['success' => false, 'message' => 'Failed to fetch URL'];
    }
    
    $html = fixRelativePaths($html, $url);
    
    $buildData = $data;
    $buildData['html'] = $html;
    return buildFromHTML($buildData);
}

function buildFromZIP($file, $data) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload failed'];
    }
    
    $buildId = uniqid('build_');
    $buildDir = __DIR__ . '/../builds/' . $buildId;
    
    if (!mkdir($buildDir, 0777, true)) {
        return ['success' => false, 'message' => 'Failed to create build directory'];
    }
    
    $zip = new ZipArchive();
    if ($zip->open($file['tmp_name']) !== true) {
        return ['success' => false, 'message' => 'Invalid ZIP file'];
    }
    
    $zip->extractTo($buildDir);
    $zip->close();
    
    if (!file_exists($buildDir . '/index.html')) {
        return ['success' => false, 'message' => 'No index.html found in ZIP'];
    }
    
    $manifest = generateManifest($data);
    file_put_contents($buildDir . '/AndroidManifest.xml', $manifest);
    
    $activity = generateActivity($data['app_name'] ?? 'App', $data['package_name'] ?? 'com.lifx.app');
    $activityPath = $buildDir . '/src/main/java/' . str_replace('.', '/', $data['package_name'] ?? 'com.lifx.app');
    mkdir($activityPath, 0777, true);
    file_put_contents($activityPath . '/MainActivity.java', $activity);
    
    $apkPath = compileAPK($buildDir, $buildId, $data['package_name'] ?? 'com.lifx.app');
    
    if ($apkPath && file_exists($apkPath)) {
        updateStats();
        return [
            'success' => true,
            'build_id' => $buildId,
            'download_url' => '/download.php?id=' . $buildId,
            'file_size' => filesize($apkPath),
            'filename' => $data['app_name'] . '.apk'
        ];
    }
    
    return ['success' => false, 'message' => 'Build failed'];
}

function generateManifest($data) {
    $package = $data['package_name'] ?? 'com.lifx.app';
    $version = $data['version'] ?? '1.0.0';
    $versionCode = $data['version_code'] ?? '1';
    
    $permissions = '';
    $permList = ['INTERNET'];
    
    if (!empty($data['perm_storage'])) $permList[] = 'WRITE_EXTERNAL_STORAGE';
    if (!empty($data['perm_camera'])) $permList[] = 'CAMERA';
    if (!empty($data['perm_location'])) $permList[] = 'ACCESS_FINE_LOCATION';
    if (!empty($data['perm_microphone'])) $permList[] = 'RECORD_AUDIO';
    
    foreach ($permList as $perm) {
        $permissions .= "    <uses-permission android:name=\"android.permission.$perm\" />\n";
    }
    
    return "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<manifest xmlns:android=\"http://schemas.android.com/apk/res/android\"
    package=\"$package\"
    android:versionCode=\"$versionCode\"
    android:versionName=\"$version\">

$permissions
    <application
        android:allowBackup=\"true\"
        android:icon=\"@mipmap/ic_launcher\"
        android:label=\"@string/app_name\"
        android:theme=\"@style/AppTheme\">
        <activity
            android:name=\".MainActivity\"
            android:configChanges=\"orientation|screenSize\"
            android:label=\"@string/app_name\">
            <intent-filter>
                <action android:name=\"android.intent.action.MAIN\" />
                <category android:name=\"android.intent.category.LAUNCHER\" />
            </intent-filter>
        </activity>
    </application>
</manifest>";
}

function generateActivity($appName, $package) {
    return "package $package;

import android.os.Bundle;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import androidx.appcompat.app.AppCompatActivity;

public class MainActivity extends AppCompatActivity {
    private WebView webView;
    
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        webView = new WebView(this);
        setContentView(webView);
        
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setUseWideViewPort(true);
        
        webView.setWebViewClient(new WebViewClient());
        webView.loadUrl(\"file:///android_asset/index.html\");
    }
    
    @Override
    public void onBackPressed() {
        if (webView.canGoBack()) {
            webView.goBack();
        } else {
            super.onBackPressed();
        }
    }
}";
}

function compileAPK($buildDir, $buildId, $package) {
    $outputFile = __DIR__ . '/../output/' . $buildId . '.apk';
    
    if (!is_dir(__DIR__ . '/../output')) {
        mkdir(__DIR__ . '/../output', 0777, true);
    }
    
    $androidJar = ANDROID_SDK_PATH . '/platforms/android-33/android.jar';
    $buildTools = ANDROID_SDK_PATH . '/build-tools/30.0.3';
    
    $cmd = "javac -d $buildDir/classes -classpath $androidJar $buildDir/src/main/java/**/*.java 2>&1";
    exec($cmd, $output, $returnCode);
    
    if ($returnCode !== 0) {
        return null;
    }
    
    $cmd = "$buildTools/dx --dex --output=$buildDir/classes.dex $buildDir/classes 2>&1";
    exec($cmd, $output, $returnCode);
    
    if ($returnCode !== 0) {
        return null;
    }
    
    $cmd = "cd $buildDir && $buildTools/aapt package -f -M AndroidManifest.xml -I $androidJar -F $buildDir/unaligned.apk 2>&1";
    exec($cmd, $output, $returnCode);
    
    if ($returnCode !== 0) {
        return null;
    }
    
    $cmd = "$buildTools/zipalign -f 4 $buildDir/unaligned.apk $outputFile 2>&1";
    exec($cmd, $output, $returnCode);
    
    if ($returnCode !== 0) {
        return null;
    }
    
    $keystore = KEYSTORE_PATH;
    $storePass = KEYSTORE_PASS;
    $keyAlias = KEYSTORE_ALIAS;
    
    $cmd = "jarsigner -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore $keystore -storepass $storePass $outputFile $keyAlias 2>&1";
    exec($cmd, $output, $returnCode);
    
    return $returnCode === 0 ? $outputFile : null;
}

function fixRelativePaths($html, $baseUrl) {
    $html = preg_replace_callback('/(src|href)=["\']([^"\']+)["\']/i', function($matches) use ($baseUrl) {
        $attr = $matches[1];
        $url = $matches[2];
        
        if (strpos($url, 'http') === 0 || strpos($url, '//') === 0) {
            return "$attr=\"$url\"";
        }
        
        if (strpos($url, '/') === 0) {
            $parsed = parse_url($baseUrl);
            $base = $parsed['scheme'] . '://' . $parsed['host'];
            return "$attr=\"$base$url\"";
        }
        
        return "$attr=\"" . rtrim($baseUrl, '/') . '/' . ltrim($url, '/') . '"';
    }, $html);
    
    return $html;
}

function updateStats() {
    $statsFile = __DIR__ . '/../builds/stats.json';
    
    if (file_exists($statsFile)) {
        $stats = json_decode(file_get_contents($statsFile), true);
        $stats['total_builds'] = ($stats['total_builds'] ?? 0) + 1;
        $stats['today_builds'] = ($stats['today_builds'] ?? 0) + 1;
        
        $today = date('Y-m-d');
        if (!isset($stats['daily'][$today])) {
            $stats['daily'][$today] = 0;
        }
        $stats['daily'][$today]++;
        
        file_put_contents($statsFile, json_encode($stats));
    }
}

function checkBuildStatus($buildId) {
    $apkFile = __DIR__ . '/../output/' . $buildId . '.apk';
    
    if (file_exists($apkFile)) {
        return 'completed';
    }
    
    $buildDir = __DIR__ . '/../builds/' . $buildId;
    if (is_dir($buildDir)) {
        return 'processing';
    }
    
    return 'failed';
}
?>
