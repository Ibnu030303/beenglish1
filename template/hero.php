<div class="hero-top d-flex justify-content-center align-items-center" data-aos="zoom-in">
    <div class="container">
        <div class="row text-center">
            <div class="col text-center text-white">
                <h1>BRIGHT EXCELLENT ENGLISH</h1>
                <h2>Transform Your English Language Skills with BEE Balaraja!</h2>
                <p>
                    Solusi Untuk Belajar Melatih Bahasa Inggrismu Lebih Lancar dan
                    Percaya Diri
                </p>
            </div>
        </div>
    </div>
</div>
<div class="hero-bottom" data-aos="zoom-in-up">
    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="cards d-flex flex-wrap justify-content-center">
                    <?php
                    $sql = "SELECT * FROM service";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <div class="col d-flex align-items-center justify-content-center">
                                <div class="card text-center d-flex flex-column align-items-center">
                                    <img src="<?php echo $row["icon"]; ?>" alt="Image of <?php echo $row["title"]; ?>" class="mx-auto">
                                    <h5 class="card-title mb-4 mt-4"><?php echo $row["title"]; ?></h5>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        echo "No programs available for English course.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>