<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo json_encode([
        'loggedin' => true,
        'email' => $_SESSION['email']
    ]);
} else {
    echo json_encode([
        'loggedin' => false
    ]);
}
?> 