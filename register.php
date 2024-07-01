<?php
require_once 'config.php';
session_start();

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

// Generate a unique token and store it in the session
if (empty($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

$alertMessage = '';
$registrationSuccess = false;

// Add Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    // Verify the token
    if (!isset($_POST['form_token']) || $_POST['form_token'] !== $_SESSION['form_token']) {
        $alertMessage = "Invalid form submission.";
        $alertType = "error";
    } else {
        unset($_SESSION['form_token']); // Invalidate the token

        $no_siswa = generateStudentNumber($conn);
        $nama = $_POST['nama'];
        $tanggal_daftar = date('Y-m-d'); // Set the registration date to today's date
        $tempat_lahir = $_POST['tempat_lahir'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jk = $_POST['jk'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        $email = $_POST['email'];
        $program_id = $_POST['program_id'];
        $course_id = $_POST['course_id'];

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO siswa (no_siswa, nama, tanggal_daftar, tempat_lahir, tanggal_lahir, jk, alamat, telepon, email, program_id, course_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $no_siswa, $nama, $tanggal_daftar, $tempat_lahir, $tanggal_lahir, $jk, $alamat, $telepon, $email, $program_id, $course_id);

        if ($stmt->execute() === TRUE) {
            $alertMessage = "Pendaftaran berhasil. Silahkan hubungi admin atau datang langsung ke lembaga.";
            $alertType = "success";
            $registrationSuccess = true; // Flag to indicate registration success
        } else {
            $alertMessage = "Terjadi kesalahan saat mendaftar: " . $stmt->error;
            $alertType = "error";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bright Bee Excellent - Registrasi Form</title>
    <link rel="shortcut icon" href="assets/img/logo-removebg-preview.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<style>
    .navbar {
        background-color: #9553e2;
    }
</style>

<body class="bg-body">
    <!-- NAVBAR -->
    <?php require 'template/navbar.php'; ?>
    <!-- END NAVBAR -->

    <section class="border mt-5" style="margin-top: 30%;">
        <div class="container">
            <div class="row d-flex justify-content-center mb-5">
                <?php if ($alertMessage) : ?>
                    <div class="alert alert-<?php echo $alertType; ?> mt-3" role="alert">
                        <?php echo $alertMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="card shadow mt-5 px-4 py-4 w-50" style="margin-top: 10%;">
                    <?php if ($registrationSuccess) : ?>
                        <div class="card-body">
                            <h5 class="card-title">Pendaftaran Berhasil</h5>
                            <p class="card-text">Silahkan hubungi admin atau datang langsung ke lembaga untuk informasi lebih lanjut.</p>
                        </div>
                    <?php else : ?>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="no_siswa" name="no_siswa" value="<?php echo generateStudentNumber($conn); ?>" readonly>
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
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="tel" class="form-control" id="telepon" name="telepon" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select class="form-select" id="course_id" name="course_id" required onchange="fetchPrograms(this.value)">
                                    <!-- Option populated dynamically from database -->
                                    <?php
                                    $sql = "SELECT id, nama FROM course";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No courses available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="program_id" class="form-label">Program</label>
                                <select class="form-select" id="program_id" name="program_id" required>
                                    <!-- Program options will be populated dynamically based on course selection -->
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3 w-100" name="add_student">Daftar</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Function to fetch programs based on selected course
        function fetchPrograms(courseId) {
            fetch('fetch_programs.php?course_id=' + courseId)
                .then(response => response.json())
                .then(data => {
                    const programSelect = document.getElementById('program_id');
                    programSelect.innerHTML = ''; // Clear existing options

                    data.forEach(program => {
                        const option = document.createElement('option');
                        option.value = program.id;
                        option.textContent = program.nama;
                        programSelect.appendChild(option); // Append new options
                    });
                })
                .catch(error => console.error('Error fetching programs:', error));
        }
    </script>
</body>

</html>
