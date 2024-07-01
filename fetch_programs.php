<?php
// Include your database connection
require_once 'config.php';

// Check if course_id is provided via GET request
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Prepare SQL statement to fetch programs based on course_id
    $sql = "SELECT id, nama FROM program WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);

    // Execute SQL query
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($program_id, $program_name);

    // Fetch results into an array
    $programs = array();
    while ($stmt->fetch()) {
        $program = array(
            'id' => $program_id,
            'nama' => $program_name
        );
        $programs[] = $program;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($programs);
    exit;
} else {
    // Invalid request, return empty response or handle error
    echo json_encode(array()); // Return an empty array if no course_id provided
    exit;
}
?>
