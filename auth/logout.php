<?php
// ============================================
// LOGOUT
// Menghancurkan session dan mengarahkan ke login
// ============================================

require_once '../config/database.php';

// Hancurkan semua session
session_unset();
session_destroy();

// Redirect ke halaman login
header('Location: login.php');
exit();
?>