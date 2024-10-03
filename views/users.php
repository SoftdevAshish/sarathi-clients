<?php
include('../actions/config.php');

// Pagination and search setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;
$conn = getDbConnection();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_pattern = preg_quote($search, '/');
$search_regex = ".*$search_pattern.*";

$total_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE name REGEXP '$search_regex' OR email REGEXP '$search_regex' OR username REGEXP '$search_regex'");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch users with pagination and search
$sql = "SELECT * FROM users WHERE name REGEXP '$search_regex' OR email REGEXP '$search_regex' OR username REGEXP '$search_regex' LIMIT $offset, $records_per_page";
$result = $conn->query($sql);

$title = 'Users management';
ob_start();
?>
<div class="row">
    <div class="col my-4 text-end">
        <div class="row">
            <div class="col-9">
                <form action="" method="GET">
                    <div class="input-group">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="search" id="form1" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" />
                            <label class="form-label" for="form1">Search</label>
                        </div>
                        <button type="submit" class="btn btn-primary" data-mdb-ripple-init>
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="?page=<?= $page ?>" class="btn btn-secondary" data-mdb-ripple-init>Clear</a>
                    </div>
                </form>
            </div>
            <div class="col-3">
                <a href="/views/register.php" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-init data-mdb-ripple-color="dark">Add User</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <table class="table align-middle">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Address</th>
                <th scope="col">Active</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                $i = $offset + 1;
                while ($row = $result->fetch_assoc()) {
                    $toggleButton = $row['active'] ?
                        "<a href='/actions/_toggle-active.php?id={$row['id']}&status=0' class='btn btn-danger btn-sm px-3' data-mdb-ripple-init data-ripple-color='primary'>Deactivate</a>" :
                        "<a href='/actions/_toggle-active.php?id={$row['id']}&status=1' class='btn btn-success btn-sm px-3' data-mdb-ripple-init data-ripple-color='primary'>Activate</a>";

                    echo "<tr>
                                <th scope='row'>{$i}</th>
                                <td>{$row['name']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['address']}</td>
                                <td>" . ($row['active'] ? 'Active' : 'Blocked') . "</td>
                                <td>
                                    <a  class='btn btn-link btn-sm px-3' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='#exampleModal'><i class='fas fa-key'></i></a>
                                    $toggleButton
                                </td>
                            </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='8'>No users found.</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Modal -->
    <!-- Reset Password Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../actions/_password-update.php" method="POST" id="resetPasswordForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Reset User Password</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden input to pass the user ID -->
                        <input type="hidden" name="user_id" id="userId" value="">

                        <!-- Input for the new password -->
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="text" class="form-control" id="newPassword" name="new_password" required>
                        </div>

                        <!-- Input for confirming the new password -->
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="text" class="form-control" id="confirmPassword" name="confirm_password" required>
                        </div>
                        <div class="form-group p-3 text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    $content = ob_get_contents();
    ob_end_clean();
    include('layouts.php');

    // Close database connection
    $conn->close();
    ?>
