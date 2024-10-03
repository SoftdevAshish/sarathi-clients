<?php
// Include your database connection
include '../actions/config.php';
$title="Edit Company";
ob_start();
// Check if client id is passed in the URL
if (isset($_GET['id'])) {
    $companyId = $_GET['id'];
    $conn = getDbConnection();
    // Prepare a statement to retrieve client company data
    $query = $conn->prepare("SELECT * FROM company WHERE id = ?");
    $query->bind_param("i", $companyId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $company = $result->fetch_assoc();
    } else {
        echo "No company found!";
        exit;
    }
} else {
    echo "No client selected!";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $pan = $_POST['pan'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $types = $_POST['types'];
    $category = $_POST['category'];
    $irdUsername = $_POST['irdUsername'];
    $irdPassword = $_POST['irdPassword'];
    $irdPhone = $_POST['irdPhone'];
    $irdEmail = $_POST['irdEmail'];
    $id = $companyId;

    // Update the database
    $updateQuery = $conn->prepare("UPDATE company SET name=?, pan=?, phone=?, email=?, address=?, types=?, category=?, irdUsername=?, irdPassword=?, irdPhone=?, irdEmail=? WHERE id=?");
    $updateQuery->bind_param("sssssssssssi", $name, $pan, $phone, $email, $address, $types, $category, $irdUsername, $irdPassword, $irdPhone, $irdEmail, $companyId);

    if ($updateQuery->execute()) {
        echo "Company updated successfully!".$id;
//        header("Location: users.php");
        header('Location: /views/view-clients.php?id='.$company['clientsId'].'&tab=2');

        exit;
    } else {
        echo "Error updating company: " . $conn->error;
    }
}

?>
    <div class="container">
        <div class="row m-5">
            <div class="col-3"></div>
            <div class="col-6 mb-5">
                    <h2>Edit Client Company</h2>
                    <form action="edit-company.php?id=<?= htmlspecialchars($company['id']) ?>" method="POST">
                        <div class="form-group">
                            <label for="name">Company Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= htmlspecialchars($company['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="pan">PAN</label>
                            <input type="text" class="form-control" id="pan" name="pan" value="<?= htmlspecialchars($company['pan']) ?>"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="<?= htmlspecialchars($company['phone']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($company['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="<?= htmlspecialchars($company['address']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="types">Types</label>
                            <input type="text" class="form-control" id="types" name="types"
                                   value="<?= htmlspecialchars($company['types']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" id="category" name="category"
                                   value="<?= htmlspecialchars($company['category']) ?>" required>
                        </div>

                        <h4>IRD Details</h4>
                        <div class="form-group">
                            <label for="irdUsername">IRD Username</label>
                            <input type="text" class="form-control" id="irdUsername" name="irdUsername"
                                   value="<?= htmlspecialchars($company['irdUsername']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="irdPassword">IRD Password</label>
                            <input type="text" class="form-control" id="irdPassword" name="irdPassword"
                                   value="<?= htmlspecialchars($company['irdPassword']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="irdPhone">IRD Phone</label>
                            <input type="text" class="form-control" id="irdPhone" name="irdPhone"
                                   value="<?= htmlspecialchars($company['irdPhone']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="irdEmail">IRD Email</label>
                            <input type="email" class="form-control" id="irdEmail" name="irdEmail"
                                   value="<?= htmlspecialchars($company['irdEmail']) ?>" required>
                        </div>
                        <div class="form-group my-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="/views/view-clients.php?id=<?= $company['clientsId'] ?>.&tab=2" class="btn btn-secondary">Back to Company</a>
                        </div>
<!--                        <button type="submit" class="btn btn-primary">Update Company</button>-->
<!--                        <a href="/views/view-users.php?id=--><?php //= $company['clientsId'] ?><!--.&tab=2" class="btn btn-secondary">Back to Company</a>-->
                    </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>


<?php
$content = ob_get_contents();
ob_end_clean();
include('layouts.php');
