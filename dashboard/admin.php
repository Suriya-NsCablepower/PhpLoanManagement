<?php
session_start();
require '../includes/functions.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Officers only.");
}

$users = read_json('../data/users.json');
$loanTypes = read_json('../data/loan_types.json');

// Handle adding a loan type
if (isset($_POST['add_loan'])) {
    $loanTypes[] = [
        'id' => uniqid(),
        'name' => htmlspecialchars(trim($_POST['loan_name'])),
        'interest' => floatval($_POST['interest']),
        'term_months' => intval($_POST['term'])
    ];
    write_json('../data/loan_types.json', $loanTypes);
    $_SESSION['message'] = "Loan type added!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle adding an officer
if (isset($_POST['add_officer'])) {
    $newOfficer = [
        'username' => htmlspecialchars(trim($_POST['officer_username'])),
        'password' => password_hash($_POST['officer_password'], PASSWORD_DEFAULT),
        'role' => 'officer',
        'profile_picture' => 'uploads/default-profile.png',
        'last_active' => time()
    ];

    foreach ($users as $u) {
        if ($u['username'] === $newOfficer['username']) {
            die("Username already exists.");
        }
    }

    $storedUsers[] = $newOfficer;
    write_json('../data/users.json', $storedUsers);

    $_SESSION['message'] = "Officer added!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Dashboard</h2>

<?php
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>{$_SESSION['message']}</p>";
    unset($_SESSION['message']);
}
?>

<!-- Admin-only section -->
<?php if ($currentUser['role'] === 'admin'): ?>
    <h3>Admin Only Section</h3>
    <p>Welcome, <strong><?= htmlspecialchars($currentUser['username']) ?></strong>. You have administrative privileges.</p>
<?php endif; ?>

<h3>Add Loan Type</h3>
<form method="POST">
    <input name="loan_name" required placeholder="Loan Name"><br>
    <input name="interest" type="number" step="0.01" required placeholder="Interest Rate (%)"><br>
    <input name="term" type="number" required placeholder="Term (months)"><br>
    <button name="add_loan">Add Loan Type</button>
</form>

<h3>Add Officer</h3>
<form method="POST">
    <input name="officer_username" required placeholder="Username"><br>
    <input name="officer_password" type="password" required placeholder="Password"><br>
    <button name="add_officer">Add Officer</button>
</form>

<h3>Existing Loan Types</h3>
<ul>
    <?php foreach ($loanTypes as $loan): ?>
        <li><?= htmlspecialchars($loan['name']) ?> - <?= $loan['interest'] ?>% for <?= $loan['term_months'] ?> months</li>
    <?php endforeach; ?>
</ul>

<h3>Existing Officers</h3>
<ul>
    <?php foreach ($users as $u): ?>
        <?php if ($u['role'] === 'officer'): ?>
            <li><?= htmlspecialchars($u['username']) ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

</body>
</html>