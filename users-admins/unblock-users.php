<?php
require "../layouts/header.php";
require "../../config/config.php";

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='".ADMINURL."/admins/login-admins.php'</script>";
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Update wrong_attempts to 0
    $updateWrongAttemptsQuery = "UPDATE users SET wrong_attempts = 0 WHERE id = :userId";
    $updateWrongAttemptsStmt = $conn->prepare($updateWrongAttemptsQuery);
    $updateWrongAttemptsStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $updateWrongAttemptsStmt->execute();

    // Redirect back to show-users.php
    echo "<script>window.location.href='show-users.php'</script>";
} else {
    // Handle the case where no user ID is provided
    echo "<script>alert('Invalid user ID'); window.location.href='show-users.php'</script>";
}

?>