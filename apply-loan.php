<?php

session_start();

require 'includes/functions.php';



if (
    !isset($_SESSION['role']) || 
    !in_array($_SESSION['role'], ['admin', 'user', 'officer'])
) {
    die("Access denied.");
}



$loanTypes = read_json('data/loan_types.json');
$loans = read_json('data/loans.json');

// Retrieve the user's username for later reference
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected loan type details
    $loanTypeId = $_POST['loan_type_id'];
    $selectedLoanType = null;
    foreach ($loanTypes as $type) {
        if ($type['id'] === $loanTypeId) {
            $selectedLoanType = $type;
            break;
        }
    }

    // Make sure the loan type was found
    if ($selectedLoanType) {
        // Add the loan with the username and loan name
        $loans[] = [
            'id' => uniqid(),  // Loan ID (unique ID)
            'username' => $username,  // Replace user ID with username
            'loan_type_id' => $selectedLoanType['name'],  // Loan type name instead of ID
            'amount' => $_POST['amount'],
            'status' => 'pending',
            'submitted_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => null,
            'reviewed_at' => null
        ];

        // Write the new loan data back to the loans.json file
        write_json('data/loans.json', $loans);
        echo "Loan application submitted!";
    } else {
        echo "Invalid loan type selected.";
    }
}

?>

<!-- Form -->

<form method="POST">
    <select name="loan_type_id">
        <?php foreach ($loanTypes as $type): ?>
            <option value="<?= $type['id'] ?>"><?= $type['name'] ?> (<?= $type['interest'] ?>%)</option>
        <?php endforeach; ?>
    </select><br>

    <input type="number" name="amount" required placeholder="Amount"><br>

    <button type="submit">Apply</button>
</form>