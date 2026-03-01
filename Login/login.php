<?php
session_start();

function isJsonRequest() {
    return (
        isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
    ) || 
    (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    $file_path = 'users.json';

    if (!file_exists($file_path)) {
        $error = ['error' => 'Users database not found.'];
        if (isJsonRequest()) {
            http_response_code(500);
            echo json_encode($error);
        } else {
            $_SESSION['login_error'] = $error['error'];
            header("Location: ../index.php");
        }
        exit;
    }

    $json_data = file_get_contents($file_path);
    $data = json_decode($json_data, true);

    if (!isset($data['users']) || !is_array($data['users'])) {
        $error = ['error' => 'Invalid user data structure.'];
        if (isJsonRequest()) {
            http_response_code(500);
            echo json_encode($error);
        } else {
            $_SESSION['login_error'] = $error['error'];
            header("Location: ../index.php");
        }
        exit;
    }

    $authenticated = false;
    foreach ($data['users'] as &$user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $authenticated = true;

            // Store user info in session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'] ?? 'user';
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? 'default-profile.png';

            // Update last active
            $user['last_active'] = time();
            file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
            break;
        }
    }

    if ($authenticated) {
        $role = $_SESSION['role'];
        $redirectUrl = '';

        switch ($role) {
            case 'admin':
                $redirectUrl = '../dashboard/admin.php';
                break;
            case 'officer':
                $redirectUrl = '../dashboard/officer.php';
                break;
            case 'user':
            default:
                $redirectUrl = '../dashboard/index.php';
                break;
        }

        $success = [
            'success' => 'Login successful! Redirecting...',
            'redirect' => $redirectUrl
        ];

        if (isJsonRequest()) {
            echo json_encode($success);
        } else {
            header("Location: $redirectUrl");
        }
    } else {
        $error = ['error' => 'Invalid username or password.'];
        if (isJsonRequest()) {
            http_response_code(401);
            echo json_encode($error);
        } else {
            $_SESSION['login_error'] = $error['error'];
            header("Location: ../index.php");
        }
    }

    exit;
} else {
    header("Location: login.php");
    exit;
}
