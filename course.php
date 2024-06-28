<?php
require_once 'config.php';

// Default course ID for English
$default_course_id = 2; // Assuming 1 is the course ID for English

// Query to fetch programs for the default course (English)
$sql = "SELECT * FROM program WHERE course_id = $default_course_id";
$result = $conn->query($sql);
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
    <!-- Add Slick Carousel CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick-theme.css" />
    <!-- AOS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body >
    <!-- NAVBAR -->
    <?php require 'template/navbar.php'; ?>
    <!-- END NAVBAR -->

    <!-- HEADER-->
    <section class="header" >
        <div class="container">
            <div class="row" data-aos="zoom-out">
                <div class="p-5 text-center">
                    <h2 class="fw-bold mb-4">Course & Program</h2>
                    <div class="d-flex flex-wrap justify-content-center">
                        <?php
                        // Query to fetch all courses
                        $sql = "SELECT * FROM course";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Output buttons for each course
                                $isActive = ($row['id'] == $default_course_id) ? 'active' : '';
                        ?>
                                <button class="btn btn-primary my-2 mx-1 course-btn <?php echo $isActive; ?>" data-course-id="<?php echo $row['id']; ?>"><?php echo $row["nama"]; ?></button>
                        <?php
                            }
                        } else {
                            echo "<p>No courses found.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END HEADER-->

    <section id="course" class="course">
        <div class="container" data-aos="zoom-out-right">
            <section id="program-list" class="mt-4">
                <div class="row" id="program-list-container">
                    <!-- Programs will be dynamically loaded here -->
                </div>
            </section>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
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
            // Initial load of programs for default course
            loadPrograms(<?php echo $default_course_id; ?>);

            // Click event for course buttons
            $('.course-btn').click(function() {
                var courseId = $(this).data('course-id');
                loadPrograms(courseId);
            });

            // Function to load programs via AJAX
            function loadPrograms(courseId) {
                $.ajax({
                    url: 'get_all_programs.php',
                    method: 'POST',
                    data: {
                        course_id: courseId
                    },
                    success: function(response) {
                        $('#program-list-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

        });
    </script>
</body>

</html>

<?php $conn->close(); ?>