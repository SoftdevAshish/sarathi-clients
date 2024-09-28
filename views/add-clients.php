<?php
$title = 'Add Clients';
include('../actions/config.php'); // Include the database connection

ob_start();
?>

<div class="container my-4">
    <h4 class="text-end">Add Client Page</h4>
    <form action="#add-client.php" method="POST">
        <div class="mb-3">
            <label for="clientName" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="clientName" name="clientName" required>
        </div>
        <div class="mb-3">
            <label for="clientEmail" class="form-label">Client Email</label>
            <input type="email" class="form-control" id="clientEmail" name="clientEmail" required>
        </div>
        <div class="mb-3">
            <label for="clientPhone" class="form-label">Client Phone</label>
            <input type="tel" class="form-control" id="clientPhone" name="clientPhone">
        </div>
        <div class="mb-3">
            <label for="clientAddress" class="form-label">Client Address</label>
            <textarea class="form-control" id="clientAddress" name="clientAddress"></textarea>
        </div>
        <div class="mb-3">
            <label for="clientCategory" class="form-label">Client Category</label>
            <input type="text" class="form-control" id="clientCategory" name="clientCategory">
        </div>
        <button type="submit" class="btn btn-primary">Add Client</button>
    </form>
</div>

<?php


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn=getDbConnection();
    // Fetch input values
    $clientName = $_POST['clientName'];
    $clientEmail = $_POST['clientEmail'];
    $clientPhone = $_POST['clientPhone'] ?? null; // Optional
    $clientAddress = $_POST['clientAddress'] ?? null; // Optional
    $clientCategory = $_POST['clientCategory'] ?? null; // Optional

    // Validate input
    if (empty($clientName) || empty($clientEmail)) {
        echo "<div class='alert alert-danger'>Please fill in all required fields.</div>";
    } else {
        // Prepare and execute the insertion
        $sql = "INSERT INTO clients (name, email, phone, address, category) VALUES ('$clientName', '$clientEmail', '$clientPhone', '$clientAddress', '$clientCategory')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header('Location: clients.php');
            exit();
        } else{
            header('Location: clients.php');
            exit();
        }
    }
}
$content = ob_get_contents();
ob_end_clean();
include('layouts.php');
?>
