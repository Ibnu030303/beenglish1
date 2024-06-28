<?php
require_once 'config.php';

if (isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);

    $sql = "SELECT * FROM program WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col-4 flex-wrap mb-4">
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
        echo '<p>No programs found for this course.</p>';
    }

    $stmt->close();
}
$conn->close();
?>
