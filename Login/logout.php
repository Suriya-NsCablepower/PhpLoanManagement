<?php
session_start();

$file_path = 'users.json';

if (isset($_SESSION['username']) && file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $data = json_decode($json_data, true);

    if (isset($data['users']) && is_array($data['users'])) {
        foreach ($data['users'] as &$user) {
            if ($user['username'] === $_SESSION['username']) {
                $user['last_active'] = time() - 3600; // Sets last active to 1 hour ago
                break;
            }
        }
        file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));
    }
}

// Destroy the session and redirect to login
session_unset();
session_destroy();

header("Location: ../index.php");
exit;
?>