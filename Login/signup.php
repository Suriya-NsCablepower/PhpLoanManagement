<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = [];

    // Ensure upload directory exists
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
     $role = htmlspecialchars(trim($_POST['role'] ?? 'user'));

    // Validate fields
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $response['error'] = "All fields are required.";
        echo json_encode($response);
        exit;
    }

    if ($password !== $confirm_password) {
        $response['error'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile picture upload
    $profile_picture_path = "uploads/default-profile.png"; // Default profile picture

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES['profile_picture'];
        $file_name = time() . "_" . basename($profile_picture['name']); // Unique filename
        $target_path = $upload_dir . $file_name;

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB limit

        if (!in_array($profile_picture['type'], $allowed_types)) {
            $response['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            echo json_encode($response);
            exit;
        }

        if ($profile_picture['size'] > $max_size) {
            $response['error'] = "File size exceeds 2MB.";
            echo json_encode($response);
            exit;
        }

        if (!move_uploaded_file($profile_picture['tmp_name'], $target_path)) {
            $response['error'] = "Error uploading the profile picture.";
            echo json_encode($response);
            exit;
        }

        $profile_picture_path = $target_path; // Set uploaded picture path
    }

    // Load users from JSON
    $json_file = 'users.json';
    $existing_data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : ['users' => []];

    if (!isset($existing_data['users']) || !is_array($existing_data['users'])) {
        $existing_data = ['users' => []];
    }

    // Check for duplicate username
    foreach ($existing_data['users'] as $user) {
        if ($user['username'] === $username) {
            $response['error'] = "Username already exists.";
            echo json_encode($response);
            exit;
        }
    }

    // Add new user
    $new_user = [
        'username' => $username,
        'password' => $hashed_password,
        'profile_picture' => $profile_picture_path,
        "role"=>$role,
        'last_active' => time()
    ];

    $existing_data['users'][] = $new_user;

    if (file_put_contents($json_file, json_encode($existing_data, JSON_PRETTY_PRINT)) === false) {
        $response['error'] = "Error saving user data.";
        echo json_encode($response);
        exit;
    }

    // Success response
    $response['success'] = "Signup successful! You can now <a href='../index.php'>login</a>.";
    echo json_encode($response);
    exit;
}
?>