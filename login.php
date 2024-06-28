<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '';

    $email = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $role = $row['role'];

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            if (!empty($return_url)) {
                header("Location: " . urldecode($return_url));
                exit();
            } else {
                if ($role == 'admin') {
                    header("Location: admin/index.php");
                    exit();
                } else {
                    header("Location: user/index.php");
                    exit();
                }
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bright Bee Excellent - English Course</title>
    <link rel="shortcut icon" href="assets/img/logo-removebg-preview.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            max-width: 900px;
            width: 100%;
        }

        .login-container img {
            max-width: 200px;
            margin-right: 2rem;
        }

        .login-form {
            flex-grow: 1;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .form-control {
            margin-bottom: 1rem;
        }

        .login-form button {
            width: 100%;
        }

        .text-danger {
            margin-top: 1rem;
            color: #d9534f;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="assets/img/logo-removebg-preview.png" alt="logo">
        <div class="login-form">
            <h4 class="mb-4 text-center">Bright Excellent English</h4>
            <?php if (isset($error)) : ?>
                <div class="text-danger mt-2"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <input type="hidden" name="return_url" value="<?php echo isset($_GET['return_url']) ? htmlspecialchars($_GET['return_url']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Login</button>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>