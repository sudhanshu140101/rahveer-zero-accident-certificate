<?php
session_start();
require_once '../config.php';

// Log logout action
if (isset($_SESSION['admin_logged_in'])) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO admin_logs (action, description, ip_address) VALUES (?, ?, ?)");
        $stmt->execute(['LOGOUT', 'Admin logged out', $_SERVER['REMOTE_ADDR']]);
    } catch (Exception $e) {
        error_log("Logout log error: " . $e->getMessage());
    }
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header('Location: login.php');
exit;
?>
