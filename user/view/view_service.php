<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'user') {
    header('Location: ../user/index.php');
    exit();
}

$alertMessage = '';

// Add Service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desktipsi = mysqli_real_escape_string($conn, $_POST['desktipsi']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO service (title, desktipsi, icon, status) VALUES ('$title', '$desktipsi', '$icon', '$status')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Service added successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_service.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the service: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_service.php';
                        });";
    }
}

// Edit Service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_service'])) {
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desktipsi = mysqli_real_escape_string($conn, $_POST['desktipsi']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE service SET title = '$title', desktipsi = '$desktipsi', icon = '$icon', status = '$status' WHERE id = $service_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Service updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_service.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the service: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_service.php';
                        });";
    }
}

// Delete Service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_service'])) {
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);

    $sql = "DELETE FROM service WHERE id = $service_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Service deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_service.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the service: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_service.php';
                        });";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Service Dashboard</title>
    <link rel="shortcut icon" href="../../assets/img/logo-removebg-preview.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" />
    <!-- Lni Icons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Style -->
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include '../template/sidebar.php'; ?>
        <!-- End Sidebar -->

        <main id="main" class="main">
            <!-- Navbar -->
            <?php include '../template/navbar.php'; ?>
            <!-- End Navbar -->

            <!-- Main Content -->
            <div class="pagetitle">
                <h1>Dashboard</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Service</li>
                    </ol>
                </nav>
            </div>
            <!-- End Page Title -->

            <!-- Your Content Here -->
            <section class="section dashboard">
                <!-- Add your content here -->
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mt-3 fw-bold">Service Post</h5>
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#addServiceModal">Tambah</button>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Deskripsi</th>
                                                <th scope="col">Icon</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" colspan="2">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Fetch and display service data here -->
                                            <?php
                                            $sql = "SELECT * FROM service";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $no = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<th scope='row'>" . $no++ . "</th>";
                                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['desktipsi']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['icon']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                                    echo "<td>
                                                        <button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editServiceModal' data-service-id='" . $row['id'] . "' data-title='" . htmlspecialchars($row['title']) . "' data-desktipsi='" . htmlspecialchars($row['desktipsi']) . "' data-icon='" . htmlspecialchars($row['icon']) . "' data-status='" . htmlspecialchars($row['status']) . "'>
                                                        <i class='lni lni-pencil-alt'></i></button>
                                                    </td>";
                                                    echo "<td>
                                                                <form action='' method='POST' class='delete-form' style='display;'>
                                                                    <input type='hidden' name='service_id' value='" . $row['id'] . "'>
                                                                    <input type='hidden' name='delete_service' value='1'>
                                                                    <button type='button' class='btn btn-link text-danger p-0 m-0 delete-btn'><i class='lni lni-trash-can text-danger'></i></button>
                                                                </form>
                                                            </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No services found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Content -->
        </main>
        <!-- End #main -->
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg text-white">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addServiceModalLabel">Tambah Service</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="desktipsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="desktipsi" name="desktipsi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="icon" name="icon" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                        <input type="hidden" name="add_service" value="1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg text-white">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editServiceModalLabel">Edit Service</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDesktipsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDesktipsi" name="desktipsi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editIcon" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="editIcon" name="icon" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                        <input type="hidden" id="editServiceId" name="service_id">
                        <input type="hidden" name="edit_service" value="1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Script -->
    <script src="../assets/js/script.js"></script>

    <?php
    if (!empty($alertMessage)) {
        echo "<script>
            $alertMessage
          </script>";
    }
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = button.getAttribute('data-service-id');
                    const title = button.getAttribute('data-title');
                    const desktipsi = button.getAttribute('data-desktipsi');
                    const icon = button.getAttribute('data-icon');
                    const status = button.getAttribute('data-status');

                    document.getElementById('editServiceId').value = serviceId;
                    document.getElementById('editTitle').value = title;
                    document.getElementById('editDesktipsi').value = desktipsi;
                    document.getElementById('editIcon').value = icon;
                    document.getElementById('editStatus').value = status;
                });
            });

            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = button.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to delete this service?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
