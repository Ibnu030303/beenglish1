<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'user') {
    header('Location: ../user/index.php');
    exit();
}

$alertMessage = '';

// Add Program
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_program'])) {
    $nama_program = mysqli_real_escape_string($conn, $_POST['nama_program']);
    $deskripsi_program = mysqli_real_escape_string($conn, $_POST['deskripsi_program']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $image = $_FILES['image']['name'];
    $target = "../assets/uploads/" . basename($image);

    $sql = "INSERT INTO program (nama, deskripsi, course_id, image) VALUES ('$nama_program', '$deskripsi_program', '$course_id', '$image')";

    if ($conn->query($sql) === TRUE) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Program added successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_program.php';
                            });";
        } else {
            $alertMessage = "Swal.fire({
                                title: 'Error',
                                text: 'Failed to upload image.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_program.php';
                            });";
        }
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the program: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_program.php';
                        });";
    }
}

// Edit Program
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_program'])) {
    $program_id = mysqli_real_escape_string($conn, $_POST['program_id']);
    $nama_program = mysqli_real_escape_string($conn, $_POST['nama_program']);
    $deskripsi_program = mysqli_real_escape_string($conn, $_POST['deskripsi_program']);
    $image = $_FILES['image']['name'];
    $target = "../assets/uploads/" . basename($image);

    if (!empty($image)) {
        $sql = "UPDATE program SET nama = '$nama_program', deskripsi = '$deskripsi_program', image = '$image' WHERE id = $program_id";
    } else {
        $sql = "UPDATE program SET nama = '$nama_program', deskripsi = '$deskripsi_program' WHERE id = $program_id";
    }

    if ($conn->query($sql) === TRUE) {
        if (!empty($image) && !move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $alertMessage = "Swal.fire({
                                title: 'Error',
                                text: 'Failed to upload image.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_program.php';
                            });";
        } else {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Program updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_program.php';
                            });";
        }
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the program: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_program.php';
                        });";
    }
}

// Delete Program
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_program'])) {
    $program_id = mysqli_real_escape_string($conn, $_POST['program_id']);

    $sql = "DELETE FROM program WHERE id = $program_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Program deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_program.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the program: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_program.php';
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
    <title>Program Dashboard</title>

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
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active">Program</li>
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
                                        <h5 class="card-title mt-3 fw-bold">Program</h5>
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama Program</th>
                                                <th scope="col">Deskripsi Program</th>
                                                <th scope="col">Nama Kursus</th>
                                                <th scope="col">Image</th>
                                                <th scope="col" colspan="2">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Fetch and display program data with corresponding course data here -->
                                            <?php
                                            $sql = "SELECT program.id as program_id, program.nama as program_nama, program.deskripsi as program_deskripsi, course.nama as course_nama, course.deskripsi as course_deskripsi, program.image as program_image FROM program INNER JOIN course ON program.course_id = course.id";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $no = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<th scope='row'>" . $no++ . "</th>";
                                                    echo "<td>" . htmlspecialchars($row['program_nama']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['program_deskripsi']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['course_nama']) . "</td>";
                                                    echo "<td><img src='../assets/uploads/" . htmlspecialchars($row['program_image']) . "' alt='" . htmlspecialchars($row['program_nama']) . "' style='max-width:150px;'></td>";
                                                    echo "<td>
                                                                <button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editModal' data-program-id='" . $row['program_id'] . "' data-program-nama='" . htmlspecialchars($row['program_nama']) . "' data-program-deskripsi='" . htmlspecialchars($row['program_deskripsi']) . "' data-course-nama='" . htmlspecialchars($row['course_nama']) . "' data-image='" . htmlspecialchars($row["program_image"]) . "'> 
                                                                    <i class='lni lni-pencil-alt'></i>
                                                                </button>
                                                            </td>";
                                                    echo "<td>
                                                                <form method='post' action=''>
                                                                    <input type='hidden' name='program_id' value='" . $row['program_id'] . "'>
                                                                    <button type='submit' name='delete_program' class='btn btn-link text-danger'><i class='lni lni-trash-can'></i></button>
                                                                </form>
                                                            </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>No programs found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Add Program -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg text-white">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Program</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama_program" class="form-label">Nama Program</label>
                                        <input type="text" name="nama_program" class="form-control" id="nama_program" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi_program" class="form-label">Deskripsi Program</label>
                                        <textarea name="deskripsi_program" class="form-control" id="deskripsi_program" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="course_id" class="form-label">Nama Kursus</label>
                                        <select name="course_id" id="course_id" class="form-select" required>
                                            <option value="">Pilih Kursus</option>
                                            <?php
                                            $course_query = "SELECT id, nama FROM course";
                                            $course_result = $conn->query($course_query);
                                            while ($course_row = $course_result->fetch_assoc()) {
                                                echo "<option value='" . $course_row['id'] . "'>" . htmlspecialchars($course_row['nama']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Program Image</label>
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="add_program" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Add Program -->

                <!-- Modal Edit Program -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg text-white">
                        <div class="modal-content">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Program</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="program_id" id="edit_program_id">
                                    <div class="mb-3">
                                        <label for="edit_nama_program" class="form-label">Nama Program</label>
                                        <input type="text" name="nama_program" class="form-control" id="edit_nama_program" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_deskripsi_program" class="form-label">Deskripsi Program</label>
                                        <textarea name="deskripsi_program" class="form-control" id="edit_deskripsi_program" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Program Image</label>
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_course_id" class="form-label">Nama Kursus</label>
                                        <select name="course_id" id="edit_course_id" class="form-select" required>
                                            <?php
                                            $course_query = "SELECT id, nama FROM course";
                                            $course_result = $conn->query($course_query);
                                            while ($course_row = $course_result->fetch_assoc()) {
                                                echo "<option value='" . $course_row['id'] . "'>" . htmlspecialchars($course_row['nama']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="edit_program" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Edit Program -->

            </section>
            <!-- End Your Content Here -->

        </main>
        <!-- End Main Content -->
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($alertMessage)) {
                echo $alertMessage;
            } ?>
        });

        var editModal = document.getElementById('editModal')
        editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget
            var programId = button.getAttribute('data-program-id')
            var programNama = button.getAttribute('data-program-nama')
            var programDeskripsi = button.getAttribute('data-program-deskripsi')
            var courseNama = button.getAttribute('data-course-nama')

            var modalTitle = editModal.querySelector('.modal-title')
            var modalBodyProgramNama = editModal.querySelector('#edit_nama_program')
            var modalBodyProgramDeskripsi = editModal.querySelector('#edit_deskripsi_program')
            var modalBodyCourseId = editModal.querySelector('#edit_course_id')

            modalTitle.textContent = 'Edit Program - ' + programNama
            editModal.querySelector('#edit_program_id').value = programId
            modalBodyProgramNama.value = programNama
            modalBodyProgramDeskripsi.value = programDeskripsi

            // Set the selected course ID
            for (var i = 0; i < modalBodyCourseId.options.length; i++) {
                if (modalBodyCourseId.options[i].text === courseNama) {
                    modalBodyCourseId.selectedIndex = i;
                    break;
                }
            }
        })
    </script>
</body>

</html>
