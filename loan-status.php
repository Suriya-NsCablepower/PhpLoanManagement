<?php
session_start();

require 'includes/functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$loans = read_json('data/loans.json');
$loanTypes = read_json('data/loan_types.json');

// Get only loans belonging to the current user and that are approved or rejected
$Loans = array_filter($loans, function ($loan) {
    return $loan['username'] === $_SESSION['username'] && in_array($loan['status'], ['approved', 'rejected']);
});
?>

<h2>Your Processed Loan Applications</h2>

<?php if (empty($Loans)): ?>
    <p>You have no approved or rejected applications yet.</p>
<?php else: ?>
    <table border="1">
        <tr>
            <th>Type</th><th>Amount</th><th>Status</th><th>Submitted</th><th>Reviewed</th>
        </tr>
        <?php foreach ($Loans as $loan): 
            
            
        ?>
        <tr>
            <td><?= htmlspecialchars($loan['loan_type_id']) ?></td>
            <td><?= htmlspecialchars($loan['amount']) ?></td>
            <td><?= htmlspecialchars($loan['status']) ?></td>
            <td><?= htmlspecialchars($loan['submitted_at']) ?></td>
            <td><?= htmlspecialchars($loan['reviewed_at'] ?? 'Not reviewed') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>