<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../admin/index.php');
    exit();
}

function generateStudentNumber($conn)
{
    $currentYear = date('Y');
    $prefix = $currentYear . '1';

    // Get the maximum student number for the current year
    $sql = "SELECT MAX(CAST(SUBSTRING(no_siswa, 6, 4) AS UNSIGNED)) as max_number 
            FROM siswa 
            WHERE no_siswa LIKE '$prefix%'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $maxNumber = isset($row['max_number']) ? $row['max_number'] : 0;

    // Increment the maximum number by 1
    $newNumber = $maxNumber + 1;

    // Generate the new student number
    return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}


$alertMessage = '';

// Add Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $no_siswa = generateStudentNumber($conn);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal_daftar = date('Y-m-d'); // Mengisi tanggal daftar otomatis dengan tanggal hari ini
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jk = mysqli_real_escape_string($conn, $_POST['jk']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $program_id = mysqli_real_escape_string($conn, $_POST['program_id']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO siswa (no_siswa, nama, tanggal_daftar, tempat_lahir, tanggal_lahir, jk, alamat, telepon, email, program_id, course_id, status)
            VALUES ('$no_siswa', '$nama', '$tanggal_daftar', '$tempat_lahir', '$tanggal_lahir', '$jk', '$alamat', '$telepon', '$email', '$program_id', '$course_id', '$status')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Student added successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_siswa_baru.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the student: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_siswa_baru.php';
                        });";
    }
}

// Edit Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_student'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $no_siswa = mysqli_real_escape_string($conn, $_POST['no_siswa']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal_daftar = mysqli_real_escape_string($conn, $_POST['tanggal_daftar']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jk = mysqli_real_escape_string($conn, $_POST['jk']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $program_id = mysqli_real_escape_string($conn, $_POST['program_id']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE siswa SET 
                no_siswa = '$no_siswa', 
                nama = '$nama', 
                tanggal_daftar = '$tanggal_daftar', 
                tempat_lahir = '$tempat_lahir', 
                tanggal_lahir = '$tanggal_lahir', 
                jk = '$jk', 
                alamat = '$alamat', 
                telepon = '$telepon', 
                email = '$email', 
                program_id = '$program_id', 
                course_id = '$course_id', 
                status = '$status'
            WHERE id = $student_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Student updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_siswa_baru.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the student: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_siswa_baru.php';
                        });";
    }
}

// Delete Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);

    $sql = "DELETE FROM siswa WHERE id = $student_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Student deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_siswa_baru.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the student: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_siswa_baru.php';
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
    <title>Siswa Baru Dashboard</title>

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
                        <li class="breadcrumb-item active">Siswa Baru</li>
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
                                <div class="card-body" style="overflow-y: auto;">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mt-3 fw-bold">Siswa Baru</h5>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">No Siswa</th>
                                                <th scope="col">Tanggal Daftar</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Tempat Lahir</th>
                                                <th scope="col">Tanggal Lahir</th>
                                                <th scope="col">Jenis Kelamin</th>
                                                <th scope="col">Alamat</th>
                                                <th scope="col">Telepon</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Program</th>
                                                <th scope="col">Course</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" colspan="2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT siswa.id, siswa.no_siswa, siswa.tanggal_daftar, siswa.nama, siswa.tempat_lahir, siswa.tanggal_lahir, 
                                              siswa.jk, siswa.alamat, siswa.telepon, siswa.email, 
                                              program.nama AS program_name, course.nama AS course_name, 
                                              siswa.program_id, siswa.course_id, siswa.status FROM siswa LEFT JOIN program ON siswa.program_id = program.id LEFT JOIN course ON siswa.course_id = course.id WHERE siswa.status = 'Wait'";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $count = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $count++ . "</td>";
                                                    echo "<td>" . $row['no_siswa'] . "</td>";
                                                    echo "<td>" . $row['tanggal_daftar'] . "</td>";
                                                    echo "<td>" . $row['nama'] . "</td>";
                                                    echo "<td>" . $row['tempat_lahir'] . "</td>";
                                                    echo "<td>" . $row['tanggal_lahir'] . "</td>";
                                                    echo "<td>" . $row['jk'] . "</td>";
                                                    echo "<td>" . $row['alamat'] . "</td>";
                                                    echo "<td>" . $row['telepon'] . "</td>";
                                                    echo "<td>" . $row['email'] . "</td>";
                                                    echo "<td>" . $row['program_name'] . "</td>";
                                                    echo "<td>" . $row['course_name'] . "</td>";
                                                    echo "<td>" . $row['status'] . "</td>";
                                                    echo "<td>
                                                            <button class='btn btn-sm btn-link editBtn' data-bs-toggle='modal' data-bs-target='#editModal' 
                                                                data-student-id='" . $row['id'] . "' 
                                                                data-no-siswa='" . $row['no_siswa'] . "' 
                                                                data-nama='" . $row['nama'] . "' 
                                                                data-tanggal-daftar='" . $row['tanggal_daftar'] . "' 
                                                                data-tempat-lahir='" . $row['tempat_lahir'] . "' 
                                                                data-tanggal-lahir='" . $row['tanggal_lahir'] . "' 
                                                                data-jk='" . $row['jk'] . "' 
                                                                data-alamat='" . $row['alamat'] . "' 
                                                                data-telepon='" . $row['telepon'] . "' 
                                                                data-email='" . $row['email'] . "' 
                                                                data-program-id='" . $row['program_id'] . "' 
                                                                data-course-id='" . $row['course_id'] . "' 
                                                                data-status='" . $row['status'] . "'> <i class='lni lni-pencil-alt'></i></button>
                                                          
                                                        </td>";
                                                    echo "<td>
                                                            <button class='btn btn-sm btn-link deleteBtn' data-bs-toggle='modal' data-bs-target='#deleteModal' data-student-id='" . $row['id'] . "'> <i class='lni lni-trash-can text-danger'></i></button>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='14' class='text-center'>No students found.</td></tr>";
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
            <!-- End Section -->

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered text-white">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Siswa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form content here -->
                                <input type="hidden" id="edit_student_id" name="student_id">
                                <div class="mb-3">
                                    <label for="edit_no_siswa" class="form-label">No Siswa</label>
                                    <input type="text" class="form-control" id="edit_no_siswa" name="no_siswa" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_tanggal_daftar" class="form-label">Tanggal Daftar</label>
                                    <input type="date" class="form-control" id="edit_tanggal_daftar" name="tanggal_daftar" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_tempat_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="edit_tempat_lahir" name="tempat_lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_jk" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="edit_jk" name="jk" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_telepon" class="form-label">Telepon</label>
                                    <input type="tel" class="form-control" id="edit_telepon" name="telepon" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_program_id" class="form-label">Program</label>
                                    <select class="form-select" id="edit_program_id" name="program_id" required>
                                        <!-- Option populated dynamically from database -->
                                        <?php
                                        $sql = "SELECT id, nama FROM program";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_course_id" class="form-label">Course</label>
                                    <select class="form-select" id="edit_course_id" name="course_id" required>
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
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Wait">Wait</option>
                                        <option value="Active">Active</option>
                                        <option value="Off">Off</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="edit_student">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Edit Modal -->

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Student</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form content here -->
                                <input type="hidden" id="delete_student_id" name="student_id">
                                <p>Are you sure you want to delete this student?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger" name="delete_student">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Delete Modal -->

            <!-- SweetAlert2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Your custom scripts (optional) -->
            <script>
                // Edit Student Function
                var editBtns = document.querySelectorAll('.editBtn');
                editBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        let student_id = btn.getAttribute('data-student-id');
                        let no_siswa = btn.getAttribute('data-no-siswa');
                        let nama = btn.getAttribute('data-nama');
                        let tanggal_daftar = btn.getAttribute('data-tanggal-daftar');
                        let tempat_lahir = btn.getAttribute('data-tempat-lahir');
                        let tanggal_lahir = btn.getAttribute('data-tanggal-lahir');
                        let jk = btn.getAttribute('data-jk');
                        let alamat = btn.getAttribute('data-alamat');
                        let telepon = btn.getAttribute('data-telepon');
                        let email = btn.getAttribute('data-email');
                        let program_id = btn.getAttribute('data-program-id');
                        let course_id = btn.getAttribute('data-course-id');
                        let status = btn.getAttribute('data-status');

                        document.getElementById('edit_student_id').value = student_id;
                        document.getElementById('edit_no_siswa').value = no_siswa;
                        document.getElementById('edit_nama').value = nama;
                        document.getElementById('edit_tanggal_daftar').value = tanggal_daftar;
                        document.getElementById('edit_tempat_lahir').value = tempat_lahir;
                        document.getElementById('edit_tanggal_lahir').value = tanggal_lahir;
                        document.getElementById('edit_jk').value = jk;
                        document.getElementById('edit_alamat').value = alamat;
                        document.getElementById('edit_telepon').value = telepon;
                        document.getElementById('edit_email').value = email;
                        document.getElementById('edit_program_id').value = program_id;
                        document.getElementById('edit_course_id').value = course_id;
                        document.getElementById('edit_status').value = status;
                    });
                });

                // Delete Student Function
                var deleteBtns = document.querySelectorAll('.deleteBtn');
                deleteBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        let student_id = btn.getAttribute('data-student-id');
                        document.getElementById('delete_student_id').value = student_id;
                    });
                });

                // SweetAlert Message Handling
                <?php echo $alertMessage; ?>
            </script>
        </main>
        <!-- End Main Content -->
    </div>
</body>

</html>