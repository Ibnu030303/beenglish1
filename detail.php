<?php
require_once 'config.php';

// Get the article ID from the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // SQL query to fetch the article details
    $sql = "SELECT * FROM articel WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the article data
            $row = $result->fetch_assoc();
        } else {
            echo "Article not found.";
            $stmt->close();
            $conn->close();
            exit();
        }

        $stmt->close();
    } else {
        echo "Database error.";
        $conn->close();
        exit();
    }
} else {
    echo "Invalid request.";
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row["title"]); ?></title>
    <link rel="shortcut icon" href="assets/img/logo-removebg-preview.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Add Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick-theme.css" />
    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* CSS for the navbar background color change */
        .navbar {
            background-color: #9553e2;
            /* Dark background color when scrolled */
            transition: background-color 0.3s ease-in-out;
            /* Smooth transition */
        }

        .article-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .article-image {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <?php require 'template/navbar.php'; ?>
    <!-- END NAVBAR -->

    <div class="container article-container mt-5">
        <!-- Article image -->
        <img src="user/assets/uploads/<?php echo htmlspecialchars($row["image"]); ?>" class="card-img-top article-image" alt="<?php echo htmlspecialchars($row["title"]); ?>">

        <!-- Article title -->
        <h2 class="card-title text-center mt-3"><?php echo htmlspecialchars($row["title"]); ?></h2>

        <div class="mt-5" style="text-align: justify; line-height: 2.5rem; font-size: 1.3rem;">
            <p class="card-text"><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
        </div>
        <!-- Article content -->

        <!-- Back to articles button -->
        <a href="index.php" class="btn btn-primary mt-5 mb-4">Back to Articles</a>
    </div>

    <section class="articel" id="articel">
        <div class="col-12">
            <h2 class=" mb-4">Articel</h2>
        </div>
        <div class="container">
            <div class="row" data-aos="zoom-out-right">
                <div class="slick-carousel">
                    <?php
                    // SQL query to fetch data excluding the current article being viewed
                    $sql = "SELECT * FROM articel WHERE id != ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("i", $id); // $id is the ID of the current article being viewed
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                    ?>
                                <div class="col">
                                    <div class="card d-flex m-3">
                                        <img src='user/assets/uploads/<?php echo $row["image"]; ?>' class="card-img-top" alt='<?php echo $row["title"]; ?>' style='max-height: 200px; object-fit: cover;'>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row["title"]; ?></h5>
                                            <p class="card-text"><?php echo substr($row["content"], 0, 100) . '...'; ?></p>
                                            <a href="detail.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                    <?php
                            }
                        } else {
                            echo "<p>No results found.</p>";
                        }

                        $stmt->close();
                    } else {
                        echo "Database error.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>


    <!-- FOOTER -->
    <footer id="footer">
        <?php require 'template/footer.php'; ?>
    </footer>
    <!-- END FOOTER -->

    <!-- CONTACK -->
    <div class="contack fixed-bottom text-end me-4 mb-4">
        <?php require 'template/contack.php'; ?>
    </div>
    <!-- END CONTACK -->

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Add Slick Carousel JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <script>
        AOS.init();
        $(document).ready(function() {

            // Initialize Slick Carousel (if needed)
            $('.slick-carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 3,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        infinite: true,
                        dots: true
                    }
                }, {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>