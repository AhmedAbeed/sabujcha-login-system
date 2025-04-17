<?php
session_start();

// Connection setup
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sabujcha_db";

$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the user submitted email and password
if (isset($_POST['email']) && isset($_POST['password'])) {
    
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Search for the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If there is a result
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            echo "✅ Logged in successfully, welcome " . $username;
            // header("Location: index.php"); ← You can redirect later
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ No user found with this email.";
    }

    $stmt->close();
}

$conn->close();
?>
