<?php
session_start();
require 'configs/db.php';

$manga_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 1. Fetch Manga Info
$stmt = $pdo->prepare("SELECT title FROM manga WHERE id = ?");
$stmt->execute([$manga_id]);
$manga = $stmt->fetch();

if (!$manga) {
    die("Manga not found.");
}

// 2. Fetch Current Page Image
$stmt = $pdo->prepare("SELECT image_path FROM pages WHERE manga_id = ? AND page_number = ?");
$stmt->execute([$manga_id, $current_page]);
$page_data = $stmt->fetch();

// 3. Check for Next Page (to enable/disable button)
$stmt = $pdo->prepare("SELECT id FROM pages WHERE manga_id = ? AND page_number = ?");
$next_page_num = $current_page + 1;
$stmt->execute([$manga_id, $next_page_num]);
$has_next = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($manga['title']) ?> - Page <?= $current_page ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white">

    <nav class="p-4 bg-gray-900 flex justify-between items-center sticky top-0 z-50 opacity-90 hover:opacity-100 transition">
        <a href="index.php" class="text-yellow-500 font-bold">← Back</a>
        <h1 class="text-lg font-semibold"><?= htmlspecialchars($manga['title']) ?> - Page <?= $current_page ?></h1>
        <div class="space-x-4">
            <?php if ($current_page > 1): ?>
                <a href="read.php?id=<?= $manga_id ?>&page=<?= $current_page - 1 ?>" class="bg-gray-700 px-4 py-1 rounded">Prev</a>
            <?php endif; ?>
            
            <?php if ($has_next): ?>
                <a href="read.php?id=<?= $manga_id ?>&page=<?= $next_page_num ?>" class="bg-yellow-500 text-black px-4 py-1 rounded font-bold">Next</a>
            <?php else: ?>
                <span class="text-gray-500">End</span>
            <?php endif; ?>
        </div>
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

    <main class="flex flex-col items-center mt-4">
        <?php if ($page_data): ?>
            <a href="<?= $has_next ? "read.php?id=$manga_id&page=$next_page_num" : "#" ?>" class="max-w-4xl">
                <img src="<?= $page_data['image_path'] ?>" 
                     alt="Page <?= $current_page ?>" 
                     class="shadow-2xl border border-gray-800">
            </a>
        <?php else: ?>
            <div class="p-20 text-center">
                <p class="text-xl text-gray-400">Page not found.</p>
                <a href="index.php" class="text-yellow-500 mt-4 block underline">Return to Library</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="p-10 flex justify-center space-x-10">
        <?php if ($current_page > 1): ?>
            <a href="read.php?id=<?= $manga_id ?>&page=<?= $current_page - 1 ?>" class="text-gray-400 hover:text-white">← Previous Page</a>
        <?php endif; ?>
        
        <?php if ($has_next): ?>
            <a href="read.php?id=<?= $manga_id ?>&page=<?= $next_page_num ?>" class="text-yellow-500 hover:text-yellow-400 font-bold">Next Page →</a>
        <?php endif; ?>
    </footer>

</body>
</html>
