<?php
require_once '../../config.php';
require_once '../../protected_page.php';

if ($_SESSION['role'] != 'user') {
    header('Location: ../user/index.php');
    exit();
}

$alertMessage = '';

// Handling article deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_article'])) {
    $article_id = mysqli_real_escape_string($conn, $_POST['article_id']);
    $sql = "DELETE FROM articel WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $article_id);
    
    if ($stmt->execute()) {
        $alertMessage = "Swal.fire({
                            title: 'Success',
                            text: 'Article deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_articel.php';
                        });";
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error deleting the article: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_articel.php';
                        });";
    }
    $stmt->close();
}

// Handling article addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_article'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = $_FILES['image']['name'];
    $target = "../assets/uploads/" . basename($image);

    $sql = "INSERT INTO articel (title, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $image);

    if ($stmt->execute()) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Article added successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_articel.php';
                            });";
        } else {
            $alertMessage = "Swal.fire({
                                title: 'Error',
                                text: 'Failed to upload image.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_articel.php';
                            });";
        }
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error adding the article: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_articel.php';
                        });";
    }
    $stmt->close();
}

// Handling article editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_article'])) {
    $article_id = mysqli_real_escape_string($conn, $_POST['article_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = $_FILES['image']['name'];
    $target = "../assets/uploads/" . basename($image);

    // If no new image is uploaded, retain the old image
    if (empty($image)) {
        $sql = "UPDATE articel SET title=?, content=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $article_id);
    } else {
        $sql = "UPDATE articel SET title=?, content=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $image, $article_id);
    }

    if ($stmt->execute()) {
        if (empty($image) || move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $alertMessage = "Swal.fire({
                                title: 'Success',
                                text: 'Article updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_articel.php';
                            });";
        } else {
            $alertMessage = "Swal.fire({
                                title: 'Error',
                                text: 'Failed to upload image.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = 'view_articel.php';
                            });";
        }
    } else {
        $alertMessage = "Swal.fire({
                            title: 'Error',
                            text: 'There was an error updating the article: " . $conn->error . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location = 'view_articel.php';
                        });";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Article Dashboard</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" />
    <!-- Lni Icons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
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
                        <li class="breadcrumb-item active">Article</li>
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
                                        <h5 class="card-title mt-3 fw-bold">Post Article</h5>
                                        <button class="btn btn-primary mt-4 mb-4" style="height: 43px;" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah</button>
                                    </div>
                                    <table id="articleTable" class="table table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">Title</th>
                                                <th scope="col">Content</th>
                                                <th scope="col">Image</th>
                                                <th scope="col" colspan="2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SQL query to fetch articles
                                            $sql = "SELECT * FROM articel";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                // Output data of each row
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row["content"]) . "</td>";
                                                    echo "<td><img src='../assets/uploads/" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["title"]) . "' style='max-width:150px;'></td>";
                                                    echo "<td>
                                                            <button class='btn btn-link edit-btn' data-bs-toggle='modal' data-bs-target='#editModal' 
                                                            data-id='" . $row["id"] . "' 
                                                            data-title='" . htmlspecialchars($row["title"]) . "' 
                                                            data-content='" . htmlspecialchars($row["content"]) . "' 
                                                            data-image='" . htmlspecialchars($row["image"]) . "'><i class='lni lni-pencil-alt'></i></button>
                                                    </td>";
                                                    echo "<td>
                                                            <form action='' method='POST' class='delete-form' style='display:inline-block;'>
                                                                <input type='hidden' name='article_id' value='" . $row['id'] . "'>
                                                                <input type='hidden' name='delete_article' value='1'>
                                                                <button type='button' class='btn btn-link text-danger p-0 m-0 delete-btn'><i class='lni lni-trash-can text-danger'></i></button>
                                                            </form>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No articles found</td></tr>";
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

    <!-- Add Article Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg modal-dialog-centered text-white">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Article</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_article">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Article Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered text-white">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Article</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_article_id" name="article_id">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Content</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit_image" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_article">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
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
                        text: 'Do you really want to delete this article?',
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
                    const title = button.getAttribute('data-title');
                    const content = button.getAttribute('data-content');
                    const image = button.getAttribute('data-image');

                    document.getElementById('edit_article_id').value = id;
                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_content').value = content;
                    // Handle image population if needed
                });
            });

            // Initialize DataTables
            $('#articleTable').DataTable();
        });
    </script>
</body>

</html>
