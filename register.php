<?php
// Connect to the database
$host = "localhost";
$user = "root";
$password = ""; // If you have set a password for root, write it here
$dbname = "sabujcha_db";

// Create the connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// If the form is submitted
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    
    // Receive data from the form
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt the password

    // Prepare and create the query
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo " Account created successfully!";
        // You can do a redirect here, for example
        // header("Location: login-register.php");
    } else {
        echo " An error occurred: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
