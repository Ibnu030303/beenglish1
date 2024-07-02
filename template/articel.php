<div class="container">
    <div class="row" data-aos="zoom-out-right">
        <div class="slick-carousel">
            <?php
            // SQL query to fetch data
            $sql = "SELECT * FROM articel";
            $result = $conn->query($sql);

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
            ?>
        </div>
    </div>
</div>
