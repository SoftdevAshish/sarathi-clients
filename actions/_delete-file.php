<?php
// Include your database connection
include 'config.php';

if (isset($_GET['id'])) {
    $fileId = intval($_GET['id']);
    $conn = getDbConnection();
    // Fetch the file details from the database
    $query = $conn->prepare("SELECT * FROM companyFile WHERE id = ?");
    $query->bind_param("i", $fileId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $file = $result->fetch_assoc();
        $fileName = $file['FileName'];
        $filePath = '../uploads/' . $fileName;

        // Attempt to delete the file from the filesystem
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                // File successfully deleted from filesystem

                // Now delete the file record from the database
                $deleteQuery = $conn->prepare("DELETE FROM companyFile WHERE id = ?");
                $deleteQuery->bind_param("i", $fileId);
                if ($deleteQuery->execute()) {
                    $sql = $conn->prepare("SELECT * FROM company WHERE id = ?");
                    if (!$sql) {
                        die("Prepare failed: " . $conn->error);
                    }
                    $sql->bind_param("i", $file['companyId']);

                    // Execute the query to fetch company details
                    if ($sql->execute()) {
                        $result = $sql->get_result();
                        if ($result->num_rows > 0) {
                            $companyDetails = $result->fetch_assoc();
                            header("Location: ../views/view-clients.php?id=" . $companyDetails['clientsId'] . "&tab=2");
                            exit;
                        }
//                    echo "File deleted successfully.";
//                    header("Location: ../views/view-clients.php");
//                    exit;
                    } else {
                        echo "Error deleting record from database.";
                    }
                } else {
                    echo "Error deleting file from the server.";
                }
            } else {
                echo "File does not exist.";
            }
        } else {
            echo "No file found with that ID.";
        }
    } else {
        echo "No file ID specified.";
    }
}
?>
