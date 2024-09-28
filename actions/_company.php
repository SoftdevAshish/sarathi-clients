<?php
include('config.php');

$conn = getDbConnection();

// Example for adding a company
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the data
    $data = [
        'name' => $_POST['name'],
        'pan' => $_POST['pan'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'types' => $_POST['types'],
        'category' => $_POST['category'],
        'irdUsername' => $_POST['irdUsername'],
        'irdPassword' => $_POST['irdPassword'],
        'irdPhone' => $_POST['irdPhone'],
        'irdEmail' => $_POST['irdEmail'],
        'remarks' => $_POST['remarks'],
        'clientsId' => $_POST['clientsId'] // This should match the ID of the client you're associating with
    ];

    // Prepare the SQL statement
    $sql = "INSERT INTO company (name, pan, address, phone, email, types, category, irdUsername, irdPassword, irdPhone, irdEmail, remarks, clientsId) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssssssssss",
        $data['name'],
        $data['pan'],
        $data['address'],
        $data['phone'],
        $data['email'],
        $data['types'],
        $data['category'],
        $data['irdUsername'],
        $data['irdPassword'],
        $data['irdPhone'],
        $data['irdEmail'],
        $data['remarks'],
        $data['clientsId']
    );

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect on success
        header('Location: /views/view-clients.php?id='.$data['clientsId'].'&tab=2');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error adding company: " . $stmt->error . "</div>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
