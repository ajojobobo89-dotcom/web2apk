<?php
header('Content-Type: application/json');
session_start();

require_once 'config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'register':
        handleRegister();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'check':
        handleCheck();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function handleLogin() {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
            return;
        }
    }
    
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}

function handleRegister() {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email']);
        return;
    }
    
    $usersFile = __DIR__ . '/../config/users.json';
    $users = json_decode(file_get_contents($usersFile), true);
    
    foreach ($users as $user) {
        if ($user['username'] === $username || $user['email'] === $email) {
            echo json_encode(['error' => 'User already exists']);
            return;
        }
    }
    
    $newUser = [
        'id' => uniqid(),
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'user',
        'created_at' => date('Y-m-d H:i:s'),
        'api_keys' => []
    ];
    
    $users[] = $newUser;
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    
    $_SESSION['user'] = [
        'id' => $newUser['id'],
        'username' => $newUser['username'],
        'email' => $newUser['email'],
        'role' => $newUser['role']
    ];
    
    echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
}

function handleLogout() {
    session_destroy();
    echo json_encode(['success' => true]);
}

function handleCheck() {
    if (isset($_SESSION['user'])) {
        echo json_encode(['logged_in' => true, 'user' => $_SESSION['user']]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
}
?>
