<?php
include('config.php');

if (isset($_GET['id']) && isset($_GET['status'])) {
    $userId = (int)$_GET['id'];
    $status = (int)$_GET['status'];

    $conn=getDbConnection();
    $sql = "UPDATE users SET active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $status, $userId);

    if ($stmt->execute()) {
        // Redirect back to the user management page after update
        header('Location:../views/index.php');
        exit();
    } else {
        echo "Failed to update user status.";
    }

    $stmt->close();
}
$conn->close();
?>
