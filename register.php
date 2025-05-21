<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $msg = "This email address is already registered.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

       
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashed_password])) {
            $msg = "Successfully registered. You can now log in.";
        } else {
            $msg = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script>
       
        window.onload = function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                
                let errorMessages = [];
                
              
                const name = document.querySelector('input[name="name"]');
                const email = document.querySelector('input[name="email"]');
                const password = document.querySelector('input[name="password"]');

      
                if (!name.value.trim()) {
                    errorMessages.push("Please fill out the Full Name.");
                }

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
    <h2>Register</h2>
    <form method="POST" id="registrationForm">
        <input type="text" name="name" placeholder="Full Name"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <button type="submit">Register</button>
    </form>
    <p style="color:red;">
        <?= $msg ?>  
    </p>
    <p style="text-align: center;">Already have an account? <a href="index.php">Log in!</a></p>
</body>
</html>
