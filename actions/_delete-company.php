<?php
// Include your database connection
include '../actions/config.php';

// Check if the client ID is provided in the URL
if (isset($_GET['id'])) {
    $companyId = $_GET['id'];
    $clientId = $_GET['cid'];
    $conn = getDbConnection();

    // Prepare and execute the delete query
    $deleteQuery = $conn->prepare("DELETE FROM company WHERE id = ?");
    $deleteQuery->bind_param("i", $companyId);

    if ($deleteQuery->execute()) {
        // Success: Redirect to the clients list page
        header("Location: /views/view-clients.php?id=" . $clientId . '&tab=2');
        exit;
    } else {
        header("Location: /views/view-clients.php?id=" . $clientId . '&tab=2');
        exit;
    }
} else {
    header("Location: /views/index.php");
    exit;
}
