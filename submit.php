<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "online2";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Display all errors during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $dob = isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';
    $qualification = isset($_POST['qualification']) ? htmlspecialchars($_POST['qualification']) : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($dob) || empty($gender) || empty($qualification)) {
        echo "All fields are required.";
    } else {
        // Calculate age based on the Date of Birth (dob)
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($dobDate)->y;

        // Prepare the SQL query to insert the data into the users table
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, dob, gender, qualification, age) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $email, $phone, $dob, $gender, $qualification, $age);

        // Execute the query
        if ($stmt->execute()) {
            echo "<h2>Registration Successful!</h2>";
            echo "<p><strong>Name:</strong> $name</p>";
            echo "<p><strong>Email:</strong> $email</p>";
            echo "<p><strong>Contact Number:</strong> $phone</p>";
            echo "<p><strong>Date of Birth:</strong> $dob</p>";
            echo "<p><strong>Gender:</strong> $gender</p>";
            echo "<p><strong>Age:</strong> $age</p>";  // Display the calculated age
            echo "<p><strong>Qualification:</strong> $qualification</p>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
