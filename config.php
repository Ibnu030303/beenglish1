<?php 
// Establish database connection (replace with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beenglish";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Perform other operations with the database if needed

} catch (Exception $e) {
    // Handle any errors that occur during connection or operations
    echo "Error: " . $e->getMessage();
}

?>
