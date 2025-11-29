<?php
// ===============================
// Admin password reset helper
// ===============================

// 1) Set the new password you want for the admin user:
$newPassword = 'admin123';   // <- change this if you like

// 2) DB connection settings (XAMPP defaults)
$servername = "localhost";
$username   = "root";
$password   = "";            // default XAMPP MySQL password is empty
$dbname     = "car_rental";

// -------------------------------
// Connect to database
// -------------------------------
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// -------------------------------
// Check that the users table & admin user exist
// -------------------------------
$result = $mysqli->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows === 0) {
    die("Table 'users' does not exist in database '$dbname'.");
}

// Check if admin user exists
$stmt = $mysqli->prepare("SELECT id, username FROM users WHERE username = 'admin' LIMIT 1");
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    die("No user with username 'admin' found in 'users' table.");
}
$admin = $res->fetch_assoc();

// -------------------------------
// Reset the password
// -------------------------------
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$update = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->bind_param("si", $hashedPassword, $admin['id']);

if ($update->execute()) {
    echo "<h2 style='color: green'>✅ Admin password reset successfully.</h2>";
    echo "<p>Username: <b>admin</b><br>";
    echo "New password: <b>" . htmlspecialchars($newPassword, ENT_QUOTES, 'UTF-8') . "</b></p>";
} else {
    echo "<h2 style='color: red'>❌ Failed to reset password.</h2>";
    echo "<p>Error: " . htmlspecialchars($mysqli->error, ENT_QUOTES, 'UTF-8') . "</p>";
}

$update->close();
$stmt->close();
$mysqli->close();

echo "<hr><p style='color:#a00'>⚠ IMPORTANT: Delete this file (reset_admin.php) after you have logged in.</p>";
