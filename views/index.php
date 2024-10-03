<?php
include('../actions/config.php');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;
$conn = getDbConnection();

$search = isset($_GET['search']) ? $_GET['search'] : '';


$search_pattern = preg_quote($search, '/');
$search_regex = ".*$search_pattern.*";

$total_result = $conn->query("SELECT COUNT(*) as count FROM clients WHERE name REGEXP '$search_regex' OR email REGEXP '$search_regex'");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT * FROM clients WHERE name REGEXP '$search_regex' OR email REGEXP '$search_regex' LIMIT $offset, $records_per_page";
$result = $conn->query($sql);

$title = 'Sarathi Clients';
ob_start();
?>
<div class="row">
    <div class="col my-4 text-end">
        <div class="row">
            <div class="col-9">
                <form action="" method="GET">
                    <div class="input-group">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="search" id="form1" class="form-control" name="search"
                                   value="<?= htmlspecialchars($search) ?>"/>
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
                <a href="/views/add-clients.php" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-init
                   data-mdb-ripple-color="dark">Add Client</a>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="col my-5">
        <table class="table align-middle">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">E-Mail</th>
                <th scope="col">Phone</th>
                <th scope="col">Address</th>
                <th scope="col">Category</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                $i = $offset + 1; // For displaying row numbers
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <th scope='row'><?= $i ?></th>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td>
                            <a href='/views/edit-clients.php?id=<?= $row['id'] ?>' class='btn btn-link btn-sm px-3'
                               data-mdb-ripple-init data-ripple-color='primary'><i class='fas fa-edit'></i></a>
                            <a href='/views/view-clients.php?id=<?= $row['id'] ?>' class='btn btn-link btn-sm px-3'
                               data-mdb-ripple-init data-ripple-color='primary'><i class='fas fa-eye'></i></a>
                            <a href='#' class='btn btn-link btn-sm px-3' data-mdb-ripple-init
                               onclick="handleCompanyTableToggle(<?= $i ?>)"
                               data-ripple-color='primary' title="See Client Company Details"><i
                                        class='fas fa-arrow-down' id="dropdownIcon<?= $i ?>"
                                ></i></a>
                        </td>
                    </tr>
                    <tr class="d-none" id="companyRow<?= $i ?>">
                        <td colspan="7">
                            <?php
                            $companySql = "SELECT * FROM company WHERE clientsId={$row['id']}";
                            $companyResult = $conn->query($companySql);
                            if ($companyResult->num_rows > 0) {

                            ?>
                            <table class="table text-center align-middle align-baseline">
                                <thead>
                                <tr class="text-center align-middle">
                                    <th rowspan="2">S.N</th>
                                    <th rowspan="2">Company Name</th>
                                    <th colspan="2">IRD</th>
                                    <th rowspan="2">Address</th>
                                    <th rowspan="2">follow Up Date</th>
                                </tr>
                                <tr>
                                    <th>Login Credentials</th>
                                    <th>PAN</th>
                                </tr>
                                </thead>
                                <?php
                                $j = 0;
                                while ($data = $companyResult->fetch_assoc()) {
                                    $j += 1;
                                    ?>
                                    <tr>
                                        <td rowspan="2"><?= $j ?></td>
                                        <td rowspan="2"><?= $data['name'] ?></td>
                                        <td>
                                            Username: <strong><?= $data['irdUsername'] ?>   </strong>
                                        </td>
                                        <td rowspan="2"><?= $data['pan'] ?></td>
                                        <td rowspan="2"><?= $data['address'] ?></td>
                                        <td rowspan="2">2082/03/31</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Password: <strong><?= $data['irdPassword'] ?></strong>
                                        </td>
                                    </tr>
                                    <?php
                                };
                                ?>
                            </table>
                                <?php
                            } else {
                                ?>

                                     No Company Found.

                                <?php

                            }

                    ?>
                        </td>

                    </tr>
                    <?php
                    $i++;
                }
            } else {
                echo "<tr><td colspan='7'>No clients found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <script>
            function handleCompanyTableToggle(rowId) {
                // Get the row to be toggled
                let row = document.getElementById('companyRow' + rowId);
                let iCon = document.getElementById('dropdownIcon' + rowId);
                row.classList.toggle('d-none');
               if (iCon.classList.contains('fa-arrow-down')){
                   iCon.classList.replace('fa-arrow-down', 'fa-arrow-up');
               }else {
                   iCon.classList.replace('fa-arrow-up', 'fa-arrow-down');
               }

            }
        </script>
        <nav aria-label="Page navigation example mb-5">
            <ul class="pagination justify-content-end mb-5">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>"
                       aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link"
                           href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>"
                       aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</div>

<?php
$content = ob_get_contents();
ob_end_clean();
include('layouts.php');
$conn->close();
?>
