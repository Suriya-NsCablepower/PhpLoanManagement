<?php
session_start();

if (
    !isset($_SESSION['role']) || 
    !in_array($_SESSION['role'], ['admin', 'user', 'officer'])
) {
    die("Access denied.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Loan Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #333;
        }
        .card {
            display: inline-block;
            width: 300px;
            background: #fff;
            margin: 20px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .card a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Loan Management System</h1>
    <div class="card">
        <h2>Apply for a Loan</h2>
        <a href="../apply-loan.php">Go</a>
    </div>
    <div class="card">
        <h2>My Loan Status</h2>
        <a href="../loan-status.php">View</a>
    </div>
    <div class="card">
        <h2>Exit</h2>
        <a href="../Logout.php">Logout</a>
    </div>
</body>
</html>
