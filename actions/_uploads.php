<?php
// Include your database connection
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $companyId = $_POST['companyId'];
    $documentType = $_POST['documentType'];

    // Check if file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Specify allowed file types
        $allowedFileExtensions = array('pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx');

        // Check if the file extension is allowed
        if (in_array($fileExtension, $allowedFileExtensions)) {
            // Define the upload path
            $uploadFileDir = '../uploads/';

            // Generate a unique file name using company ID, document type, and timestamp
            $newFileName = md5($companyId . $documentType . time()) . '.' . $fileExtension;

            // Full path where the file will be saved
            $dest_path = $uploadFileDir . $newFileName;

            // Attempt to move the uploaded file
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Insert file data into the database
                $conn = getDbConnection();
                $query = $conn->prepare("INSERT INTO companyFile (companyId, FileName, FileType) VALUES (?, ?, ?)");

                if (!$query) {
                    die("Prepare failed: " . $conn->error);
                }

                // Bind parameters
                $query->bind_param("iss", $companyId, $newFileName, $documentType);

                // Execute the query
                if ($query->execute()) {
                    // Get company details
                    $sql = $conn->prepare("SELECT * FROM company WHERE id = ?");
                    if (!$sql) {
                        die("Prepare failed: " . $conn->error);
                    }
                    $sql->bind_param("i", $companyId);

                    // Execute the query to fetch company details
                    if ($sql->execute()) {
                        $result = $sql->get_result();
                        if ($result->num_rows > 0) {
                            $companyDetails = $result->fetch_assoc();
                            header("Location: ../views/view-clients.php?id=" . $companyDetails['clientsId'] . "&tab=2");
                            exit;
                        } else {
                            header("Location: ../views/index.php");
                            exit;
                        }
                    } else {
                        header("Location: ../views/index.php");
                        exit;
                    }
                } else {
                    header("Location: ../views/index.php");
                    exit;
                }
            } else {
                header("Location: ../views/index.php");
                exit;
            }
        } else {
            header("Location: ../views/index.php");
            exit;
        }
    } else {
        header("Location: ../views/index.php");
        exit;
    }
} else {
    header("Location: ../views/index.php");
    exit;
}
?>
