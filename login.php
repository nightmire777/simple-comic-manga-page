<?php
require 'configs/db.php';
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch();

    if ($userData && password_verify($pass, $userData['password'])) {
        // Success! Save to session
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['role'] = $userData['role'];
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Login - MangaWorm</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <form method="POST" class="bg-gray-800 p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl font-bold mb-6 text-yellow-500">Welcome Back</h2>
        <?php if($error): ?> <p class="text-red-500 mb-4"><?= $error ?></p> <?php endif; ?>
        <input type="text" name="username" placeholder="Username" class="w-full p-2 mb-4 bg-gray-700 rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-6 bg-gray-700 rounded" required>
        <button type="submit" class="w-full bg-yellow-500 text-black font-bold py-2 rounded">Login</button>
        <p class="mt-4 text-sm text-gray-400">New here? <a href="register.php" class="text-yellow-500">Register</a></p>
    </form>
</body>
</html>
