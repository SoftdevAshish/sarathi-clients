<?php
ob_start(); // Start output buffering

// Include your database connection
include 'config.php';

if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = '../uploads/' . $fileName;
    $companyId = $_GET['cid'];
    // Check if the file exists
    if (file_exists($filePath)) {
        // Set headers to initiate a download
        header('Content-Description: File Transfer');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Determine the file's MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        // Set the content type based on the MIME type
        header('Content-Type: ' . $mimeType);

        // Set the content disposition header to force download
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // Clear the output buffer
        ob_clean(); // Clear any previous output
        flush();    // Flush the system output buffer

        // Read and output the file content
        readfile($filePath);
        header("Location: ../views/view-client.php?id=" . $companyId."&tab=2");
        exit;
    } else {
        // File not found
        header("Location: ../views/index.php");
        exit;
    }
} else {
    header("Location: ../views/index.php");
    exit;
}

ob_end_flush(); // Flush the output buffer at the end
?>
