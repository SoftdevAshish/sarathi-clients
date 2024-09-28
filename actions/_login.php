<?php
session_start();
include("config.php");
$conn = getDbConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password, active FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $active);
        $stmt->fetch();
        if ($active == 1) {
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, set session
                $_SESSION['username'] = $username;
                header("Location: /views/index.php");
                exit();
            } else {
                header("Location: /index.php");
            }
        } else {
            header("Location: /index.php");
        }
    } else {
        header("Location: /index.php");
    }

    $stmt->close();
}

$conn->close();

