<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'user') {
    header('Location: ../user/index.php');
    exit();
}

$alertMessage = '';

// Add Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $sql = "INSERT INTO course (nama, deskripsi) VALUES ('$nama', '$deskripsi')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Course added successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_course.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the course: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_course.php';
                        });";
    }
}

// Edit Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_course'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $sql = "UPDATE course SET nama = '$nama', deskripsi = '$deskripsi' WHERE id = $course_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Course updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_course.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the course: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_course.php';
                        });";
    }
}

// Delete Course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    $sql = "DELETE FROM course WHERE id = $course_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Course deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_course.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the course: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_course.php';
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
    <title>Course Dashboard</title>
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
                        <li class="breadcrumb-item active">Course</li>
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
                                        <h5 class="card-title mt-3 fw-bold">Course</h5>
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Deskripsi</th>
                                                <th scope="col" colspan="2">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Fetch and display course data here -->
                                            <?php
                                            $sql = "SELECT * FROM course";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $no = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<th scope='row'>" . $no++ . "</th>";
                                                    echo "<td>" . $row['nama'] . "</td>";
                                                    echo "<td>" . $row['deskripsi'] . "</td>";
                                                    echo "<td><button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editModal' data-id='" . $row['id'] . "' data-nama='" . $row['nama'] . "' data-deskripsi='" . $row['deskripsi'] . "'><i class='lni lni-pencil-alt'></i></button></td>";
                                                    echo "<td>
                                                            <form action='' method='POST' class='delete-form' style='display:inline-block;'>
                                                                <input type='hidden' name='course_id' value='" . $row['id'] . "'>
                                                                <input type='hidden' name='delete_course' value='1'>
                                                                <button type='button' class='btn btn-link text-danger p-0 m-0 delete-btn'><i class='lni lni-trash-can text-danger'></i></button>
                                                            </form>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No courses found</td></tr>";
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-white" id="exampleModalLabel">Tambah Course</h1>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label text-white">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label text-white">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <input type="hidden" name="add_course" value="1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-white" id="editModalLabel">Edit Course</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="editNama" class="form-label text-white">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDeskripsi" class="form-label text-white">Deskripsi</label>
                            <textarea class="form-control" id="editDeskripsi" name="deskripsi" required></textarea>
                        </div>
                        <input type="hidden" id="editCourseId" name="course_id">
                        <input type="hidden" name="edit_course" value="1">
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
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    const deskripsi = button.getAttribute('data-deskripsi');

                    document.getElementById('editCourseId').value = id;
                    document.getElementById('editNama').value = nama;
                    document.getElementById('editDeskripsi').value = deskripsi;
                });
            });

            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = button.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to delete this course?',
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