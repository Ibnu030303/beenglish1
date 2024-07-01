<?php
require_once 'config.php';

// Check if course_id is provided via GET request
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Prepare SQL statement to fetch programs based on course_id
    $sql = "SELECT id, nama FROM program WHERE course_id = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("i", $course_id);

    // Execute SQL query
    if ($stmt->execute()) {
        // Bind result variables
        $stmt->bind_result($program_id, $program_name);

        // Fetch results into an array
        $programs = [];
        while ($stmt->fetch()) {
            $program = [
                'id' => $program_id,
                'nama' => $program_name
            ];
            $programs[] = $program;
        }

        // Close statement
        $stmt->close();

        // Close database connection (optional if not reused elsewhere)
        $conn->close();

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($programs);
        exit;
    } else {
        // Error in execution
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Error executing SQL query']);
        exit;
    }
} else {
    // No course_id provided
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'No course_id provided']);
    exit;
}
