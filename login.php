<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check credentials
    if ($email === "b987654321@sakarya.edu.tr" && $password === "b987654321") {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        
        // Redirect to index page
        header("Location: index.html");
        exit();
    } else {
        // Invalid credentials
        header("Location: login.html?error=1");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: login.html");
    exit();
}
?> 