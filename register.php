<?php
require 'configs/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash it!

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$user, $pass]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Username already taken.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Register MangaWorm</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <form method="POST" class="bg-gray-800 p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl font-bold mb-6 text-yellow-500">Create Account</h2>
        <?php if($error): ?> <p class="text-red-500 mb-4"><?= $error ?></p> <?php endif; ?>
        <input type="text" name="username" placeholder="Username" class="w-full p-2 mb-4 bg-gray-700 rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-6 bg-gray-700 rounded" required>
        <button type="submit" class="w-full bg-yellow-500 text-black font-bold py-2 rounded">Register</button>
        <p class="mt-4 text-sm text-gray-400">Already have an account? <a href="login.php" class="text-yellow-500">Login</a></p>
    </form>
</body>
</html>
