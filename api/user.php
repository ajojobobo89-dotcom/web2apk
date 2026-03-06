<?php
header('Content-Type: application/json');
session_start();

require_once 'config.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_profile':
        getProfile();
        break;
    case 'update_profile':
        updateProfile();
        break;
    case 'get_api_keys':
        getApiKeys();
        break;
    case 'generate_api_key':
        generateApiKey();
        break;
    case 'revoke_api_key':
        revokeApiKey();
        break;
    case 'get_builds':
        getUserBuilds();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getProfile() {
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    foreach ($users as $user) {
        if ($user['id'] === $_SESSION['user']['id']) {
            unset($user['password']);
            echo json_encode(['success' => true, 'profile' => $user]);
            return;
        }
    }
    
    echo json_encode(['error' => 'User not found']);
}

function updateProfile() {
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $company = $_POST['company'] ?? '';
    $website = $_POST['website'] ?? '';
    
    foreach ($users as &$user) {
        if ($user['id'] === $_SESSION['user']['id']) {
            $user['full_name'] = $fullName;
            $user['email'] = $email;
            $user['company'] = $company;
            $user['website'] = $website;
            
            $_SESSION['user']['email'] = $email;
            
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true]);
            return;
        }
    }
    
    echo json_encode(['error' => 'User not found']);
}

function getApiKeys() {
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    foreach ($users as $user) {
        if ($user['id'] === $_SESSION['user']['id']) {
            echo json_encode(['success' => true, 'api_keys' => $user['api_keys'] ?? []]);
            return;
        }
    }
    
    echo json_encode(['api_keys' => []]);
}

function generateApiKey() {
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    $keyName = $_POST['name'] ?? 'Default Key';
    $newKey = 'lifx_' . bin2hex(random_bytes(16));
    
    foreach ($users as &$user) {
        if ($user['id'] === $_SESSION['user']['id']) {
            if (!isset($user['api_keys'])) {
                $user['api_keys'] = [];
            }
            
            $user['api_keys'][] = [
                'key' => $newKey,
                'name' => $keyName,
                'created_at' => date('Y-m-d H:i:s'),
                'last_used' => null,
                'usage_count' => 0
            ];
            
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true, 'key' => $newKey]);
            return;
        }
    }
    
    echo json_encode(['error' => 'Failed to generate key']);
}

function revokeApiKey() {
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    $keyToRevoke = $_POST['key'] ?? '';
    
    foreach ($users as &$user) {
        if ($user['id'] === $_SESSION['user']['id']) {
            $user['api_keys'] = array_filter($user['api_keys'], function($k) use ($keyToRevoke) {
                return $k['key'] !== $keyToRevoke;
            });
            
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true]);
            return;
        }
    }
    
    echo json_encode(['error' => 'Failed to revoke key']);
}

function getUserBuilds() {
    $buildsFile = __DIR__ . '/../builds/user_builds.json';
    $builds = json_decode(file_get_contents($buildsFile), true);
    
    $userBuilds = array_filter($builds, function($b) {
        return $b['user_id'] === $_SESSION['user']['id'];
    });
    
    echo json_encode(['success' => true, 'builds' => array_values($userBuilds)]);
}
?>
