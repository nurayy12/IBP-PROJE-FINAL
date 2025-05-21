<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $msg = "Login failed. Please check your credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.onload = function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                let errorMessages = [];
                
             
                const email = document.querySelector('input[name="email"]');
                const password = document.querySelector('input[name="password"]');

              
                if (!email.value.trim()) {
                    errorMessages.push("Please fill out the Email.");
                }

                if (!password.value.trim()) {
                    errorMessages.push("Please fill out the Password.");
                }

               
                if (errorMessages.length > 0) {
                    e.preventDefault(); 
                    alert(errorMessages.join("\n")); 
                }
            });
        }
    </script>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <button type="submit">Login</button>
    </form>
    <p style="color:red;">
        <?= $msg ?>
    </p>
    <p style="text-align: center;">Don't have an account? <a href="register.php">Register!</a></p>
</body>
</html>
