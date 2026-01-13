<?php
session_start();
require_once 'logger.php';

// Log the logout before destroying the session if you want to know who left
if (isset($_SESSION['logged_in'])) {
    Logger::info("User logged out", [
        'ip' => $_SERVER['REMOTE_ADDR']
    ]);
}

session_destroy();
echo json_encode(['success' => true]);
?>