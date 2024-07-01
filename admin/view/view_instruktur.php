<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../admin/index.php');
    exit();
}

$alertMessage = '';

// Add Instructor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_instructor'])) {
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jk = mysqli_real_escape_string($conn, $_POST['jk']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    $sql = "INSERT INTO instruktur (nip, nama, tempat_lahir, tanggal_lahir, jk, alamat, telepon, email, course_id)
            VALUES ('$nip', '$nama', '$tempat_lahir', '$tanggal_lahir', '$jk', '$alamat', '$telepon', '$email', '$course_id')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Instructor added successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_instruktur.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the instructor: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_instruktur.php';
                        });";
    }
}

// Edit Instructor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_instructor'])) {
    $instructor_id = mysqli_real_escape_string($conn, $_POST['instructor_id']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jk = mysqli_real_escape_string($conn, $_POST['jk']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    $sql = "UPDATE instruktur SET 
                nip = '$nip', 
                nama = '$nama', 
                tempat_lahir = '$tempat_lahir', 
                tanggal_lahir = '$tanggal_lahir', 
                jk = '$jk', 
                alamat = '$alamat', 
                telepon = '$telepon', 
                email = '$email', 
                course_id = '$course_id'
            WHERE id = $instructor_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Instructor updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_instruktur.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the instructor: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_instruktur.php';
                        });";
    }
}

// Delete Instructor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_instructor'])) {
    $instructor_id = mysqli_real_escape_string($conn, $_POST['instructor_id']);

    $sql = "DELETE FROM instruktur WHERE id = $instructor_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Instructor deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_instruktur.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the instructor: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_instruktur.php';
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
    <title>Instruktur Dashboard</title>

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
                        <li class="breadcrumb-item active">Instuktur</li>
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
                                        <h5 class="card-title mt-3 fw-bold">Instruktur</h5>
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">NIP</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Tempat Lahir</th>
                                                <th scope="col">Tanggal Lahir</th>
                                                <th scope="col">Jenis Kelamin</th>
                                                <th scope="col">Alamat</th>
                                                <th scope="col">Telepon</th>
                                                <th scope="col">email</th>
                                                <th scope="col">Course</th>
                                                <th scope="col" colspan="2">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT instruktur.id, instruktur.nip, instruktur.nama, instruktur.tempat_lahir, instruktur.tanggal_lahir, instruktur.jk, instruktur.alamat, instruktur.telepon, instruktur.email, course.nama AS course_nama FROM instruktur LEFT JOIN course ON instruktur.course_id = course.id";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $no = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['tempat_lahir']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['tanggal_lahir']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['jk']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['telepon']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['course_nama']) . "</td>";
                                                    echo "<td>
                                                            <button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editModal'
                                                                    data-instructor-id='" . $row['id'] . "'
                                                                    data-nip='" . htmlspecialchars($row['nip']) . "'
                                                                    data-nama='" . htmlspecialchars($row['nama']) . "'
                                                                    data-tempat-lahir='" . htmlspecialchars($row['tempat_lahir']) . "'
                                                                    data-tanggal-lahir='" . htmlspecialchars($row['tanggal_lahir']) . "'
                                                                    data-jk='" . htmlspecialchars($row['jk']) . "'
                                                                    data-alamat='" . htmlspecialchars($row['alamat']) . "'
                                                                    data-telepon='" . htmlspecialchars($row['telepon']) . "'
                                                                    data-email='" . htmlspecialchars($row['email']) . "'
                                                                    data-course-nama='" . htmlspecialchars($row['course_nama']) . "'>
                                                                <i class='lni lni-pencil-alt'></i>
                                                            </button>
                                                        </td>";
                                                    echo "<td>
                                                            <form action='' method='POST' class='delete-form' style='display: inline;'>
                                                                <input type='hidden' name='instructor_id' value='" . $row['id'] . "'>
                                                                <input type='hidden' name='delete_instructor' value='1'>
                                                                <button type='button' class='btn btn-link text-danger p-0 m-0 delete-btn'>
                                                                    <i class='lni lni-trash-can text-danger'></i>
                                                                </button>
                                                            </form>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='11'>No Instruktur found</td></tr>";
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
    <!-- Modal for Adding Instructor -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg text-white">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Instruktur</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="jk" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jk" name="jk" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="course_id" class="form-label">Course</label>
                            <select class="form-select" id="course_id" name="course_id" required>
                                <!-- Option populated dynamically from database -->
                                <?php
                                $sql = "SELECT id, nama FROM course";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="add_instructor" value="1">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <!-- Modal for Editing Instructor -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg text-white">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Instruktur</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST">
                        <div class="mb-3">
                            <label for="editNIP" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="editNIP" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTempatLahir" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="editTempatLahir" name="tempat_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTanggalLahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="editTanggalLahir" name="tanggal_lahir" required>
                        </div>
                        <div class="mb-3">
                            <label for="editJK" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="editJK" name="jk" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editAlamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="editAlamat" name="alamat" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editTelepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="editTelepon" name="telepon" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCourseId" class="form-label">Course</label>
                            <select class="form-select" id="editCourseId" name="course_id" required>
                                <!-- Option populated dynamically from database -->
                                <?php
                                $sql = "SELECT id, nama FROM course";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="editInstructorId" name="instructor_id">
                        <input type="hidden" name="edit_instructor" value="1">
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
                    const instructorId = button.getAttribute('data-instructor-id');
                    const nip = button.getAttribute('data-nip');
                    const nama = button.getAttribute('data-nama');
                    const tempatLahir = button.getAttribute('data-tempat-lahir');
                    const tanggalLahir = button.getAttribute('data-tanggal-lahir');
                    const jk = button.getAttribute('data-jk');
                    const alamat = button.getAttribute('data-alamat');
                    const telepon = button.getAttribute('data-telepon');
                    const email = button.getAttribute('data-email');
                    const courseId = button.getAttribute('data-course-id');

                    // Set values in the edit form
                    document.getElementById('editInstructorId').value = instructorId;
                    document.getElementById('editNIP').value = nip;
                    document.getElementById('editNama').value = nama;
                    document.getElementById('editTempatLahir').value = tempatLahir;
                    document.getElementById('editTanggalLahir').value = tanggalLahir;
                    document.getElementById('editJK').value = jk;
                    document.getElementById('editAlamat').value = alamat;
                    document.getElementById('editTelepon').value = telepon;
                    document.getElementById('editEmail').value = email;
                    document.getElementById('editCourseId').value = courseId;
                });
            });

            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = button.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to delete this instructor?',
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