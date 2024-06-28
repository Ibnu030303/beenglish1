<?php
require_once 'config.php';

// Default course ID for English
$default_course_id = 2; // Assuming 1 is the course ID for English

// Query to fetch programs for the default course (English)
$sql = "SELECT * FROM program WHERE course_id = $default_course_id LIMIT 3";
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
    <style>
        /* CSS for the navbar background color change */
        .navbar-scrolled {
            background-color: #9553e2;
            /* Dark background color when scrolled */
            transition: background-color 0.3s ease-in-out;
            /* Smooth transition */
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <?php require 'template/navbar.php'; ?>
    <!-- END NAVBAR -->

    <!-- HERO -->
    <section class="hero">
        <?php require 'template/hero.php'; ?>
    </section>
    <!-- END HERO -->

    <section id="course" class="course"  >
        <div class="container">
            <div class="row" data-aos="fade-up"
            data-aos-duration="1000">
                <div class="col-12">
                    <h2 class="text-start mb-3">Course</h2>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap">
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

            <section id="program-list" class="mt-4" data-aos="fade-up"
            data-aos-anchor-placement="top-bottom" data-aos-duration="1400">
                <div class="row" id="program-list-container">
                    <!-- Programs will be dynamically loaded here -->
                </div>
            </section>
        </div>
    </section>

    <!-- ABOUT -->
    <section class="about" id="about">
        <?php require 'template/about.php'; ?>
    </section>
    <!-- END ABOUT -->

    <!-- PRICE -->
    <section class="price" id="price">
        <?php require 'template/price.php'; ?>
    </section>
    <!-- END PRICE -->

    <!-- INFO -->
    <section class="info">
        <?php require 'template/info.php'; ?>
    </section>
    <!-- END INFO -->

    <section class="articel" id="articel">
        <div class="col-12">
            <h2 class=" mb-4">Articel</h2>
        </div>
        <?php require 'template/articel.php'; ?>
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
            // Function to handle navbar background change on scroll
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();

                if (scroll > 50) { // Adjust the scroll value as needed
                    $(".navbar").addClass("navbar-scrolled");
                } else {
                    $(".navbar").removeClass("navbar-scrolled");
                }
            });

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
                    url: 'get_programs.php',
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
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>