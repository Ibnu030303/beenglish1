<?php
require_once 'config.php';

// Default course ID for English
$default_course_id = 2; // Replace with the actual course ID for English

// Determine which course ID to use
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : $default_course_id;

// SQL query to fetch up to 3 programs based on course_id
$sql = "SELECT * FROM program WHERE course_id = $course_id LIMIT 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col mb-4">
            <div class="card">
                <img src="user/assets/uploads/<?php echo $row["image"]; ?>" class="card-img-top" alt="<?php echo $row["nama"]; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["nama"]; ?></h5>
                    <p class="card-text"><?php echo $row["deskripsi"]; ?></p>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>No programs found for this course.</p>";
}

// Close connection
$conn->close();
?>
