<?php
session_start();
require 'configs/db.php';

// Fetch all manga from the database
$stmt = $pdo->query("SELECT * FROM manga ORDER BY id DESC");
$mangas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Manga Reader</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

    <nav class="p-4 bg-gray-800 flex justify-between items-center shadow-lg">
    <a href="index.php"><h1 class="text-2xl font-bold text-yellow-500">MangaLite</h1></a>
    
    <div class="flex items-center space-x-4">
        <?php if (isset($_SESSION['username'])): ?>
            <span class="text-gray-300">Welcome, 
                <a href="profile.php" class="text-yellow-500 font-bold hover:underline">
                    <?= htmlspecialchars($_SESSION['username']) ?>
                </a>
            </span>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="text-xs bg-blue-600 px-2 py-1 rounded">Admin</a>
            <?php endif; ?>

            <a href="logout.php" class="text-sm text-gray-400 hover:text-white">Logout</a>
        <?php else: ?>
            <a href="login.php" class="px-4 py-2 hover:text-yellow-500">Login</a>
            <a href="register.php" class="bg-yellow-500 text-black px-4 py-2 rounded font-bold hover:bg-yellow-400">Join</a>
        <?php endif; ?>
    </div>
</nav>
    <main class="container mx-auto mt-10 p-4">
        <h2 class="text-xl mb-6 border-l-4 border-yellow-500 pl-3">Latest Updates</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php if (count($mangas) > 0): ?>
                <?php foreach ($mangas as $manga): ?>
                    <a href="read.php?id=<?= $manga['id'] ?>&page=1" class="group">
                        <div class="overflow-hidden rounded-lg bg-gray-800 transition transform group-hover:scale-105">
                            <img src="<?= $manga['cover_image'] ? 'uploads/covers/'.$manga['cover_image'] : 'https://via.placeholder.com/200x300' ?>" 
                                 alt="<?= htmlspecialchars($manga['title']) ?>" 
                                 class="w-full h-64 object-cover">
                            <div class="p-3">
                                <h3 class="font-semibold text-sm truncate"><?= htmlspecialchars($manga['title']) ?></h3>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="col-span-full text-gray-500 text-center py-10">No manga found. Go to Admin to upload some!</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
