<?php
$title = 'Edit Client';
include('../actions/config.php'); // Include the database connection

ob_start();

// Get client ID from query string
$clientId = $_GET['id'] ?? null;

if ($clientId) {
    // Fetch existing client data
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();

    if (!$client) {
        echo "<div class='alert alert-danger'>Client not found.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Invalid client ID.</div>";
    exit();
}
?>

<div class="container my-4">
    <h4 class="text-end">Edit Client</h4>
    <form action="edit-clients.php?id=<?= htmlspecialchars($clientId) ?>" method="POST">
        <div class="mb-3">
            <label for="clientName" class="form-label">Client Name</label>
            <input type="text" class="form-control" id="clientName" name="clientName" value="<?= htmlspecialchars($client['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="clientEmail" class="form-label">Client Email</label>
            <input type="email" class="form-control" id="clientEmail" name="clientEmail" value="<?= htmlspecialchars($client['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="clientPhone" class="form-label">Client Phone</label>
            <input type="tel" class="form-control" id="clientPhone" name="clientPhone" value="<?= htmlspecialchars($client['phone']) ?>">
        </div>
        <div class="mb-3">
            <label for="clientAddress" class="form-label">Client Address</label>
            <textarea class="form-control" id="clientAddress" name="clientAddress"><?= htmlspecialchars($client['address']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="clientCategory" class="form-label">Client Category</label>
            <input type="text" class="form-control" id="clientCategory" name="clientCategory" value="<?= htmlspecialchars($client['category']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Client</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDbConnection();

    // Fetch input values
    $clientName = $_POST['clientName'];
    $clientEmail = $_POST['clientEmail'];
    $clientPhone = $_POST['clientPhone'] ?? null; // Optional
    $clientAddress = $_POST['clientAddress'] ?? null; // Optional
    $clientCategory = $_POST['clientCategory'] ?? null; // Optional

    if (empty($clientName) || empty($clientEmail)) {
        echo "<div class='alert alert-danger'>Please fill in all required fields.</div>";
    } else {
        $sql = "UPDATE clients SET name = ?, email = ?, phone = ?, address = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $clientName, $clientEmail, $clientPhone, $clientAddress, $clientCategory, $clientId);

        if ($stmt->execute()) {
            header('Location: /views/index.php');
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error updating client: " . $stmt->error . "</div>";
        }
    }
}

$content = ob_get_contents();
ob_end_clean();
include('layouts.php');
?>
