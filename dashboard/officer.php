<?php
session_start();
require '../includes/functions.php';

// Only allow officers
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'officer') {
    die("Access denied. Officers only.");
}

$loans = read_json('../data/loans.json');
$loanTypes = read_json('../data/loan_types.json');

// Handle Approval/Rejection
if (isset($_POST['action'])) {
    foreach ($loans as &$loan) {
        if ($loan['id'] === $_POST['loan_id']) {
            $loan['status'] = $_POST['action'];
            $loan['reviewed_by'] = $_SESSION['user']['id'];  // Assuming you have the user ID stored separately in session
            $loan['reviewed_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
    write_json('../data/loans.json', $loans);
    echo "<p>Loan {$_POST['action']}.</p>";
}

$pendingLoans = array_filter($loans, fn($l) => $l['status'] === 'pending');
?>

<h2>Officer Dashboard</h2>

<h3>Pending Loan Applications</h3>

<table border="1">
    <tr>
        <th>User</th><th>Type</th><th>Amount</th><th>Submitted</th><th>Actions</th>
    </tr>

    <?php foreach ($pendingLoans as $loan): 
        $userMatches = array_values(array_filter($users, fn($u) => $u['id'] === $loan['user_id']));
        $username = $userMatches[0]['username'] ?? 'Unknown';

        $typeMatches = array_values(array_filter($loanTypes, fn($t) => $t['id'] === $loan['loan_type_id']));
        $loan_type_id = $typeMatches[0]['name'] ?? 'Unknown';
    ?>
    <tr>
        <td><?= htmlspecialchars($loan[username]) ?></td>
        <td><?= htmlspecialchars($loan[loan_type_id]) ?></td>
        <td><?= htmlspecialchars($loan['amount']) ?></td>
        <td><?= htmlspecialchars($loan['submitted_at']) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="loan_id" value="<?= $loan['id'] ?>">
                <button name="action" value="approved">Approve</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="loan_id" value="<?= $loan['id'] ?>">
                <button name="action" value="rejected">Reject</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
