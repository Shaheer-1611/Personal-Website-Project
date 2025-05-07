<?php
 session_start();

// Database connection parameters
$host = 'localhost';
$dbname = 'website_db';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Geçersiz email formatı.";
            header("Location: login.html");
            exit();
        }
        
        // Prepare SQL statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            
            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = time() + (30 * 24 * 60 * 60); // 30 days
                
                // Store token in database
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, token_expires = ? WHERE id = ?");
                $stmt->execute([$token, date('Y-m-d H:i:s', $expires), $user['id']]);
                
                // Set cookie
                setcookie('remember_token', $token, $expires, '/', '', true, true);
            }
            
            // Redirect to dashboard or home page
            header("Location: index.html");
            exit();
        } else {
            $_SESSION['error'] = "Geçersiz email veya şifre.";
            header("Location: login.html");
            exit();
        }
    }
} catch(PDOException $e) {
    // Log error (in production, don't show actual error message to user)
    error_log("Database Error: " . $e->getMessage());
    $_SESSION['error'] = "Bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
    header("Location: login.html");
    exit();
}
?>