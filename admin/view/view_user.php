<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../admin/index.php');
    exit();
}

$alertMessage = '';

// Handling user deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $sql = "DELETE FROM users WHERE id = $user_id";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'User deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_user.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the user: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_user.php';
                        });";
    }
}

// Handling user addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Example of hashing the password with bcrypt (recommended for secure storage)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$hashed_password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'User added successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_user.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the user: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_user.php';
                        });";
    }
}

// Handling user editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id=$user_id";

    // Check if password field is not empty
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nama='$nama', email='$email', password='$hashed_password', role='$role' WHERE id=$user_id";
    }

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'User updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_user.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the user: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_user.php';
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
    <title>User Dashboard</title>

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
                        <li class="breadcrumb-item active">User</li>
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
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#addModal">Tambah</button>
                                    </div>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Password</th>
                                                <th scope="col">Role</th>
                                                <th scope="col" colspan="2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch users
                                            $sql = "SELECT * FROM users";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                // Output data of each row
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                                    echo "<td>**********</td>"; // Displaying password securely; replace with appropriate logic if showing plaintext is necessary
                                                    echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                                                    echo "<td>
                                                        <button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editModal' 
                                                        data-id='" . $row["id"] . "' 
                                                        data-nama='" . htmlspecialchars($row["nama"]) . "' 
                                                        data-email='" . htmlspecialchars($row["email"]) . "' 
                                                        data-role='" . htmlspecialchars($row["role"]) . "' 
                                                        >Edit</button>
                                                    </td>";
                                                    echo "<td>
                                                        <form action='' method='POST' class='delete-form' style='display:inline-block;'>
                                                            <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                                                            <input type='hidden' name='delete_user' value='1'>
                                                            <button type='submit' class='btn btn-link text-danger p-0 m-0 delete-btn'><i class='lni lni-trash-can text-danger'></i></button>
                                                        </form>
                                                    </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No users found</td></tr>";
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

    <!-- Add Users Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg text-white">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_user">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg text-white">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">New Password (Optional)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_user">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- Script -->
    <script src="../assets/js/script.js"></script>
    <!-- Custom Script -->
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
                    const email = button.getAttribute('data-email');
                    const role = button.getAttribute('data-role');

                    document.getElementById('edit_user_id').value = id;
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role').value = role;
                });
            });
        });
    </script>

    <?php
    if (!empty($alertMessage)) {
        echo "<script>
                $alertMessage
              </script>";
    }
    ?>
</body>

</html>