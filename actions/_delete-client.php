<?php
// Include your database connection
include '../actions/config.php';

// Check if the client ID is provided in the URL
if (isset($_GET['id'])) {
    $clientId = $_GET['id'];
    $conn = getDbConnection();

    // Prepare and execute the delete query
    $deleteQuery = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $deleteQuery->bind_param("i", $clientId);

    if ($deleteQuery->execute()) {
        // Success: Redirect to the clients list page
        header("Location: /views/clients.php");
        exit;
    } else {
        echo "Error deleting client: " . $conn->error;
    }
} else {
    echo "No client ID provided!";
}
