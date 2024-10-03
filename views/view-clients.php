<?php
$title = 'View Client';
include('../actions/config.php');
$activeTab = $_GET['tab'] ?? 1;
ob_start();

// Get client ID from query string
$clientId = $_GET['id'] ?? null;

if ($clientId) {
    $conn = getDbConnection();

    // Prepare and execute the query
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

    <div class="row">
        <div class="col">
            <!-- Tabs navs -->
            <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a
                            data-mdb-tab-init
                            class="nav-link <?= $activeTab == 1 ? 'active' : '' ?>"
                            id="ex1-tab-1"
                            href="#ex1-tabs-1"
                            role="tab"
                            aria-controls="ex1-tabs-1"
                            aria-selected="<?= $activeTab == 1 ? 'true' : 'false' ?>"
                    >Client Details</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a
                            data-mdb-tab-init
                            class="nav-link <?= $activeTab == 2 ? 'active' : '' ?>"
                            id="ex1-tab-2"
                            href="#ex1-tabs-2"
                            role="tab"
                            aria-controls="ex1-tabs-2"
                            aria-selected="<?= $activeTab == 2 ? 'true' : 'false' ?>">Company Details</a>
                </li>
                <!--                <li class="nav-item" role="presentation">-->
                <!--                    <a-->
                <!--                            data-mdb-tab-init-->
                <!--                            class="nav-link"-->
                <!--                            id="ex1-tab-3"-->
                <!--                            href="#ex1-tabs-3"-->
                <!--                            role="tab"-->
                <!--                            aria-controls="ex1-tabs-3"-->
                <!--                            aria-selected="false"-->
                <!--                    >Tab 3</a-->
                <!--                    >-->
                <!--                </li>-->
            </ul>
            <!-- Tabs navs -->

            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">
                <div
                        class="tab-pane fade <?= $activeTab == 1 ? 'show active' : '' ?>"
                        id="ex1-tabs-1"
                        role="tabpanel"
                        aria-labelledby="ex1-tab-1"
                >
                    <div class="row">
                        <!--                        <div class="col-3"></div>-->
                        <div class="col-6 m-5">
                            <div class="row my-3">
                                <div class="col">
                                    <!--                                    <h4 class="text-start">Client Details</h4>-->
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($client['name']) ?></h5>
                                            <p class="card-text">
                                                <strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
                                            <p class="card-text">
                                                <strong>Phone:</strong> <?= htmlspecialchars($client['phone']) ?></p>
                                            <p class="card-text">
                                                <strong>Address:</strong> <?= nl2br(htmlspecialchars($client['address'])) ?>
                                            </p>
                                            <p class="card-text">
                                                <strong>Category:</strong> <?= htmlspecialchars($client['category']) ?>
                                            </p>
                                            <div class="row">
                                                <div class="col-7 text-start d-inline">
                                                    <a href="edit-clients.php?id=<?= htmlspecialchars($clientId) ?>"
                                                       class="btn btn-warning">Edit Client</a>

                                                </div>
                                                <div class="col-5 text-end  d-inline">
                                                    <a href="../actions/_delete-client.php?id=<?= $clientId ?>"
                                                       class="btn btn-danger text-end">Delete
                                                        Client</a>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!--                        <div class="col-3"></div>-->
                    </div>
                </div>
                <div class="tab-pane fade <?= $activeTab == 2 ? 'show active' : '' ?>" id="ex1-tabs-2" role="tabpanel"
                     aria-labelledby="ex1-tab-2">
                    <div class="row">
                        <div class="col">
                            <div class="col  text-end">
                                <a href="#" class="btn btn-outline-primary btn-rounded "
                                   data-mdb-ripple-color="dark" data-mdb-ripple-init
                                   data-mdb-modal-init data-mdb-target="#exampleModal">Add Company</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--                        <div class="col-3"></div>-->
                        <div class="col-">
                            <div class="row my-3">
                                <div class="col">
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                $query = $conn->prepare("SELECT * FROM company WHERE clientsId = ?");
                                $query->bind_param("i", $clientId);
                                $query->execute();
                                $result = $query->get_result();
                                if ($result->num_rows > 0) {
                                    while ($company = $result->fetch_assoc()):
                                        $modelId = 'fileUpload' . $company['id']
                                        ?>
                                        <div class="col-6 ">
                                            <div class="card my-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col d-inline">
                                                            <h5 class="card-title d-inline"><?= htmlspecialchars($company['name']) ?></h5>
                                                        </div>
                                                        <div class="col d-inline text-end">
                                                            <a class="btn btn-primary btn-floating" data-mdb-ripple-init
                                                               title="Company File Upload." data-mdb-modal-init
                                                               data-mdb-target="#<?= $modelId ?>">
                                                                <i class="fas fa-upload"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <p class="card-text">
                                                        <strong>PAN:</strong> <?= htmlspecialchars($company['pan']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>PAN:</strong> <?= htmlspecialchars($company['pan']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>Phone:</strong> <?= htmlspecialchars($company['phone']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>email:</strong> <?= htmlspecialchars($company['email']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>Address:</strong> <?= nl2br(htmlspecialchars($company['address'])) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>types:</strong> <?= htmlspecialchars($company['types']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>Category:</strong> <?= htmlspecialchars($company['category']) ?>
                                                    </p>
                                                    <h4>IRD Details</h4>
                                                    <hr>
                                                    <p class="card-text">
                                                        <strong>Username:</strong> <?= htmlspecialchars($company['irdUsername']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>Password:</strong> <?= htmlspecialchars($company['irdPassword']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>phone:</strong> <?= htmlspecialchars($company['irdPhone']) ?>
                                                    </p>
                                                    <p class="card-text">
                                                        <strong>phone:</strong> <?= htmlspecialchars($company['irdEmail']) ?>
                                                    </p>
                                                    <h4>File Details </h4>
                                                    <hr>
                                                    <div class="row">
                                                        <?php
                                                        $fileQuery = $conn->prepare("SELECT * FROM companyFile WHERE companyId = ?");
                                                        $fileQuery->bind_param("i", $company['id']);
                                                        $fileQuery->execute();
                                                        $fileResult = $fileQuery->get_result();
                                                        if ($fileResult->num_rows > 0) {
                                                            while ($companyFile = $fileResult->fetch_assoc()):
                                                                ?>
                                                                <div class="col-6">
                                                                    <div class="card m-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col d-inline">
                                                                                    <h5 class="card-title d-inline"><?= htmlspecialchars($companyFile['FileType']) ?></h5>
                                                                                </div>
                                                                                <div class="col d-inline text-end">
                                                                                    <a href="../actions/_download-file.php?file=<?= $companyFile['FileName'] ?>&cid=<?= $company['id'] ?>"
                                                                                       class="btn btn-light btn-floating"
                                                                                       data-mdb-ripple-init
                                                                                       title="Company File Upload.">
                                                                                        <i class="fas fa-download"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <p class="card-text">
                                                                                <i class="fas fa-file fa-5x"></i>
                                                                            </p>

                                                                            <div class="row">
                                                                                <div class="col d-inline">
<!--                                                                                    <a href='/views/edit-clients.php'-->
<!--                                                                                       class="btn btn-light btn-floating"-->
<!--                                                                                       data-mdb-ripple-init-->
<!--                                                                                       title="Edit File of Company">-->
<!--                                                                                        <i class="fas fa-edit"></i>-->
<!--                                                                                    </a>-->
                                                                                    <a href='../uploads/<?= $companyFile['FileName'] ?>'
                                                                                       class="btn btn-light btn-floating"
                                                                                       data-mdb-ripple-init
                                                                                       title="view File of Company">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col d-inline text-end">
                                                                                    <a  href="../actions/_delete-file.php?id=<?= $companyFile['id'] ?>" class="btn btn-danger btn-floating"
                                                                                       data-mdb-ripple-init
                                                                                       title="Company File Delete.">
                                                                                        <i class="fas fa-remove"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            endwhile;
                                                        } else {
                                                            echo "<p>No Company File Found.</p>";
                                                        }
                                                        ?>
                                                    </div>
                                                    <br>
                                                    <br>


                                                    <div class="row">
                                                        <div class="col text-start d-inline">
                                                            <a href="edit-company.php?id=<?= htmlspecialchars($company['id']) ?>"
                                                               class="btn btn-warning">Edit Company</a>
                                                        </div>

                                                        <div class="col text-end d-inline">
                                                            <a href="../actions/_delete-company.php?id=<?= $company['id'] ?>&cid=<?= $clientId ?>"
                                                               class="btn btn-danger text-end">Delete
                                                                Company</a>
                                                        </div>

                                                    </div>


                                                </div>
                                            </div>

                                        </div>
                                        <!-- Modal For File Upload -->
                                        <div class="modal fade" id="<?= $modelId ?>" tabindex="-1"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Company Document
                                                            Upload</h5>
                                                        <button type="button" class="btn-close" data-mdb-ripple-init
                                                                data-mdb-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="../actions/_uploads.php" method="POST"
                                                              enctype="multipart/form-data">
                                                            <div class="mb-3">
                                                                <div class="form-outline" data-mdb-input-init>
                                                                    <input type="text" id="form12" class="form-control"
                                                                           name="documentType"/>
                                                                    <label class="form-label" for="form12">Document
                                                                        Type.</label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label" for="customFile">Choose File
                                                                    which you want.</label>
                                                                <input type="file" name="file" class="form-control"
                                                                       id="customFile"/>
                                                            </div>

                                                            <div class="mb-3 text-center">
                                                                <input type="hidden" name="companyId"
                                                                       value="<?php echo $company['id']; ?>">
                                                                <!-- Pass the client ID -->
                                                                <button type="submit" class="btn btn-primary">Save
                                                                </button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-mdb-ripple-init data-mdb-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    endwhile;
                                } else {
                                    echo "<div>No Company found..</div>";
                                }
                                ?>
                            </div>

                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>

            </div>
            <!-- Tabs content -->
        </div>
    </div>
    <div class="row my-5">
        <div class="col my-5 text-end">
            <a href="users.php" class="btn btn-secondary">Back to
                Clients</a>
        </div>
    </div>
</div>
<div class="modal modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Company Details</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/actions/_company.php" method="POST">
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="companyPan" class="form-label">PAN</label>
                        <input type="text" class="form-control" id="companyPan" name="pan" required>
                    </div>
                    <div class="mb-3">
                        <label for="companyAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="companyAddress" name="address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="companyPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="companyPhone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="companyEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="companyEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="companyTypes" class="form-label">Types</label>
                        <input type="text" class="form-control" id="companyTypes" name="types">
                    </div>
                    <div class="mb-3">
                        <label for="companyCategory" class="form-label">Category</label>
                        <input type="text" class="form-control" id="companyCategory" name="category">
                    </div>
                    <br>
                    <div class="mb-3">
                        <label class="h4">Ird Details</label>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <label for="irdUsername" class="form-label">IRD Username</label>
                        <input type="text" class="form-control" id="irdUsername" name="irdUsername">
                    </div>
                    <div class="mb-3">
                        <label for="irdPassword" class="form-label">IRD Password</label>
                        <input type="text" class="form-control" id="irdPassword" name="irdPassword">
                    </div>
                    <div class="mb-3">
                        <label for="irdPhone" class="form-label">IRD Phone</label>
                        <input type="tel" class="form-control" id="irdPhone" name="irdPhone">
                    </div>
                    <div class="mb-3">
                        <label for="irdEmail" class="form-label">IRD Email</label>
                        <input type="email" class="form-control" id="irdEmail" name="irdEmail">
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks"></textarea>
                    </div>
                    <div class="mb-3 text-center">
                        <input type="hidden" name="clientsId" value="<?php echo $clientId; ?>">
                        <!-- Pass the client ID -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Back
                </button>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
include('layouts.php'); // Include your layout
?>
