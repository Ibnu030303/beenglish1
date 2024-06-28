<?php
require_once 'config.php';

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
    $nama = $_POST['nama'];
    $tanggal_daftar = date('Y-m-d'); // Mengisi tanggal daftar otomatis dengan tanggal hari ini
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
        $alertMessage = "Student added successfully.";
        $alertType = "success";
    } else {
        $alertMessage = "There was an error adding the student: " . $stmt->error;
        $alertType = "error";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bright Bee Excellent - English Course</title>
    <link rel="shortcut icon" href="assets/img/logo-removebg-preview.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-body">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-transparant navbar-dark fixed-top" style="background-color: #9553e2;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img src="assets/img/logo-removebg-preview.png" alt="Logo" width="40" height="35" class="d-inline-block align-text-top me-3">
                BEE Makes You Talk
            </a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="http://localhost/Beexcellent/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="course.php">Course</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/Beexcellent/#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/Beexcellent/#articel">Articel</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/Beexcellent/#footer">Kontak</a>
                    </li>
                </ul>
                <div class="">
                    <a href="register.php" class=" btn btn-light me-2">Daftar</a>
                    <a href="login.php" class="btn btn-outline-light">Login</a>
                </div>

            </div>
        </div>
    </nav>
    <!-- END NAVBAR -->

    <section class="mt-5" style="margin-top: 30%;">
        <div class="container">
            <div class="row">
                <div class="card shadow mt-5" style="margin-top: 10%;">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                            <label for="program_id" class="form-label">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                <!-- Option populated dynamically from database -->
                                <?php
                                $sql = "SELECT id, nama FROM program";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No programs available</option>";
                                }
                                ?>
                            </select>
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
                                } else {
                                    echo "<option value=''>No courses available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add_student">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php if ($alertMessage) : ?>
        <div class="alert alert-<?php echo $alertType; ?> mt-3" role="alert">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to handle navbar background change on scroll
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();

                if (scroll > 50) { // Adjust the scroll value as needed
                    $(".navbar").addClass("navbar-scrolled");
                } else {
                    $(".navbar").removeClass("navbar-scrolled");
                }
            });

        });
    </script>
</body>

</html>