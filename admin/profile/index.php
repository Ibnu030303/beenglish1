<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../admin/index.php');
    exit();
}

$alertMessage = '';

// Handling profile deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_profile'])) {
    $profile_id = mysqli_real_escape_string($conn, $_POST['profile_id']);
    $sql = "DELETE FROM profiles WHERE id = $profile_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Profile deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'index.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the profile: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'index.php';
                        });";
    }
}

// Handling profile addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_profile'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $logo = $_FILES['logo']['name'];
    $target = "../assets/uploads/" . basename($logo);

    $sql = "INSERT INTO profiles (nama, title, deskripsi, alamat, telepon, email, logo) VALUES ('$nama', '$title', '$deskripsi', '$alamat', '$telepon', '$email', '$logo')";

    if ($conn->query($sql) === TRUE) {
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target)) {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Profile added successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'index.php';
                            });";
        } else {
            $alertMessage = "Swal.fire({
                                title: 'Error',
                                text: 'Failed to upload logo.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'index.php';
                            });";
        }
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the profile: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'index.php';
                        });";
    }
}

// Handling profile editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_profile'])) {
    $profile_id = mysqli_real_escape_string($conn, $_POST['profile_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $logo = $_FILES['logo']['name'];
    $target = "../assets/uploads/" . basename($logo);

    if ($logo) {
        $sql = "UPDATE profiles SET nama='$nama', title='$title', deskripsi='$deskripsi', alamat='$alamat', telepon='$telepon', email='$email', logo='$logo' WHERE id=$profile_id";
    } else {
        $sql = "UPDATE profiles SET nama='$nama', title='$title', deskripsi='$deskripsi', alamat='$alamat', telepon='$telepon', email='$email' WHERE id=$profile_id";
    }

    if ($conn->query($sql) === TRUE) {
        if ($logo && move_uploaded_file($_FILES['logo']['tmp_name'], $target)) {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Profile updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'index.php';
                            });";
        } else {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Profile updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'index.php';
                            });";
        }
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the profile: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'index.php';
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
    <title>Profile Dashboard</title>

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
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </nav>
            </div>
            <!-- End Page Title -->

            <!-- Your Content Here -->
            <section class="section dashboard">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title mt-3 fw-bold">Profile</h5>
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#addModal">Add</button>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Deskripsi</th>
                                                <th scope="col">Alamat</th>
                                                <th scope="col">Telepon</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Logo</th>
                                                <th scope="col" colspan="2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch profiles
                                            $sql = "SELECT * FROM profiles";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                // Output data of each row
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["deskripsi"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["alamat"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["telepon"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                                    echo "<td><img src='../assets/uploads/" . htmlspecialchars($row["logo"]) . "' alt='" . htmlspecialchars($row["nama"]) . "' style='max-width:150px;'></td>";
                                                    echo "<td>
                                                            <button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editModal' 
                                                            data-id='" . $row["id"] . "' 
                                                            data-nama='" . htmlspecialchars($row["nama"]) . "' 
                                                            data-title='" . htmlspecialchars($row["title"]) . "' 
                                                            data-deskripsi='" . htmlspecialchars($row["deskripsi"]) . "' 
                                                            data-alamat='" . htmlspecialchars($row["alamat"]) . "' 
                                                            data-telepon='" . htmlspecialchars($row["telepon"]) . "' 
                                                            data-email='" . htmlspecialchars($row["email"]) . "' 
                                                            data-logo='" . htmlspecialchars($row["logo"]) . "'><i class='lni lni-pencil-alt'></i></button>
                                                    </td>";
                                                    echo "<td>
                                                            <form action='' method='POST' class='delete-form' style='display:inline-block;'>
                                                                <input type='hidden' name='profile_id' value='" . $row['id'] . "'>
                                                                <input type='hidden' name='delete_profile' value='1'>
                                                                <button type='button' class='btn btn-link text-danger p-0 m-0 delete-btn'><i class='lni lni-trash-can text-danger'></i></button>
                                                            </form>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No profiles found</td></tr>";
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

    <!-- Add Profile Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
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
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_profile">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_profile_id" name="profile_id">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="edit_alamat" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="edit_telepon" name="telepon" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="edit_logo" name="logo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_profile">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Custom Script -->
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
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = button.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to delete this profile?',
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

            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    const title = button.getAttribute('data-title');
                    const deskripsi = button.getAttribute('data-deskripsi');
                    const alamat = button.getAttribute('data-alamat');
                    const telepon = button.getAttribute('data-telepon');
                    const email = button.getAttribute('data-email');
                    const logo = button.getAttribute('data-logo');

                    document.getElementById('edit_profile_id').value = id;
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_deskripsi').value = deskripsi;
                    document.getElementById('edit_alamat').value = alamat;
                    document.getElementById('edit_telepon').value = telepon;
                    document.getElementById('edit_email').value = email;
                    // Handle logo population if needed
                });
            });
        });
    </script>
</body>

</html>