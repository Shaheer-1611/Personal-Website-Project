<?php
// login.php

// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    $errors = [];
    
    // Check if fields are empty
    if (empty($username)) {
        $errors[] = 'Kullanıcı adı boş olamaz';
    }
    
    if (empty($password)) {
        $errors[] = 'Şifre boş olamaz';
    }
    
    // Validate email format
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçersiz email formatı';
    } elseif (!preg_match('/^b\d{9}@sakarya\.edu\.tr$/i', $username)) {
        $errors[] = 'Geçerli bir öğrenci emaili giriniz';
    }
    
    // Validate password format
    if (!preg_match('/^b\d{9}$/i', $password)) {
        $errors[] = 'Geçerli bir öğrenci numarası giriniz';
    }
    
    // Check if username and password match
    $expectedUsername = strtolower($password) . '@sakarya.edu.tr';
    if (strtolower($username) !== $expectedUsername) {
        $errors[] = 'Kullanıcı adı ve şifre uyuşmuyor';
    }
    
    // If no errors, show welcome message
    if (empty($errors)) {
        // Extract student number from username
        $studentNumber = str_replace('@sakarya.edu.tr', '', strtolower($username));
        
        // Show welcome message
        echo <<<HTML
        <!DOCTYPE html>
        <html lang='tr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Hoşgeldiniz</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
            <style>
                .success-page {
                    background: linear-gradient(135deg, #2ecc71, #27ae60);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    color: white;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }
                .success-card {
                    background-color: rgba(255, 255, 255, 0.9);
                    padding: 3rem;
                    border-radius: 15px;
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
                    color: #2c3e50;
                    max-width: 500px;
                    width: 100%;
                    margin: 0 auto;
                }
            </style>
        </head>
        <body class='success-page'>
            <div class='success-card animate__animated animate__bounceIn'>
                <h1><i class='fas fa-check-circle text-success me-2'></i> Giriş Başarılı!</h1>
                <p class='lead mt-3'>Hoşgeldiniz $studentNumber</p>
                <a href='index.html' class='btn btn-success mt-3'>
                    <i class='fas fa-home me-1'></i> Ana Sayfaya Dön
                </a>
            </div>
        </body>
        </html>
HTML;
        exit;
    } else {
        // Store errors in session and redirect back
        $_SESSION['login_errors'] = $errors;
        header('Location: login.html');
        exit;
    }
} else {
    // Redirect if not POST request
    header('Location: login.html');
    exit;
}
?>