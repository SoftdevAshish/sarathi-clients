<?php
include('config.php');
$conn=getDbConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the passwords match
    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);


    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $hashedPassword, $userId);

    if ($stmt->execute()) {
        // Redirect to a success page or show a success message
        header('Location: /views/users.php');
        exit();
    } else {
        header('Location: /views/users.php');
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
