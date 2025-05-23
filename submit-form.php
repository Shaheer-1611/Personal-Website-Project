<?php
/**
 * submit-form.php
 * 
 * Purpose: Handles login form submission and authentication
 * 
 * Features:
 * - Session management
 * - Form data processing
 * - Credential validation
 * - JSON response generation
 * 
 * Security Notes:
 * - Uses session for user tracking
 * - Validates request method
 * - Returns JSON responses for AJAX
 */

// Start a new session or resume existing session for user tracking
session_start();

// Set the content type to JSON for AJAX response
header('Content-Type: application/json');

// Check if the request method is POST (form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize email and password from POST data
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate credentials against hardcoded values
    // In a production environment, this should be replaced with database authentication
    if ($email === "b987654321@sakarya.edu.tr" && $password === "b987654321") {
        // Set session variables for logged-in user
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        
        // Return success response with redirect URL
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => 'welcome.html'
        ]);
    } else {
        // Return error response for invalid credentials
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email or password'
        ]);
    }
} else {
    // Return error for invalid request method
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?> 