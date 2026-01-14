<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'configs/db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    
    // 1. Handle Cover Upload
    $cover_name = time() . "_" . $_FILES['cover']['name'];
    move_uploaded_file($_FILES['cover']['tmp_name'], "uploads/covers/" . $cover_name);

    // 2. Insert Manga into DB
    $stmt = $pdo->prepare("INSERT INTO manga (title, description, cover_image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $desc, $cover_name]);
    $manga_id = $pdo->lastInsertId();

    // 3. Create Manga Folder & Handle Pages
    $target_dir = "uploads/manga/" . $manga_id . "/";
    mkdir($target_dir, 0777, true);

    foreach ($_FILES['pages']['tmp_name'] as $key => $tmp_name) {
        $page_num = $key + 1; // Page 1, 2, 3...
        $file_name = $_FILES['pages']['name'][$key];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $page_num . "." . $ext;
        
        if (move_uploaded_file($tmp_name, $target_dir . $new_file_name)) {
            $stmt = $pdo->prepare("INSERT INTO pages (manga_id, page_number, image_path) VALUES (?, ?, ?)");
            $stmt->execute([$manga_id, $page_num, $target_dir . $new_file_name]);
        }
    }
    echo "<p class='text-green-500'>Manga uploaded successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-10">
    <div class="max-w-2xl mx-auto bg-gray-800 p-8 rounded">
        <h1 class="text-2xl font-bold mb-6">Upload New Manga</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Manga Title" class="w-full p-2 mb-4 bg-gray-700 rounded" required>
            <textarea name="description" placeholder="Description" class="w-full p-2 mb-4 bg-gray-700 rounded"></textarea>
            
            <label class="block mb-2 text-sm">Cover Image:</label>
            <input type="file" name="cover" class="mb-4" required>

            <label class="block mb-2 text-sm">Manga Pages (Select Multiple):</label>
            <input type="file" name="pages[]" multiple class="mb-6" required>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-500 py-2 rounded font-bold">Publish Manga</button>
        </form>
    </div>
</body>
</html><?php
session_start();
require 'config/db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    
    // 1. Handle Cover Upload
    $cover_name = time() . "_" . $_FILES['cover']['name'];
    move_uploaded_file($_FILES['cover']['tmp_name'], "uploads/covers/" . $cover_name);

    // 2. Insert Manga into DB
    $stmt = $pdo->prepare("INSERT INTO manga (title, description, cover_image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $desc, $cover_name]);
    $manga_id = $pdo->lastInsertId();

    // 3. Create Manga Folder & Handle Pages
    $target_dir = "uploads/manga/" . $manga_id . "/";
    mkdir($target_dir, 0777, true);

    foreach ($_FILES['pages']['tmp_name'] as $key => $tmp_name) {
        $page_num = $key + 1; // Page 1, 2, 3...
        $file_name = $_FILES['pages']['name'][$key];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $page_num . "." . $ext;
        
        if (move_uploaded_file($tmp_name, $target_dir . $new_file_name)) {
            $stmt = $pdo->prepare("INSERT INTO pages (manga_id, page_number, image_path) VALUES (?, ?, ?)");
            $stmt->execute([$manga_id, $page_num, $target_dir . $new_file_name]);
        }
    }
    echo "<p class='text-green-500'>Manga uploaded successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-10">
    <div class="max-w-2xl mx-auto bg-gray-800 p-8 rounded">
        <h1 class="text-2xl font-bold mb-6">Upload New Manga</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Manga Title" class="w-full p-2 mb-4 bg-gray-700 rounded" required>
            <textarea name="description" placeholder="Description" class="w-full p-2 mb-4 bg-gray-700 rounded"></textarea>
            
            <label class="block mb-2 text-sm">Cover Image:</label>
            <input type="file" name="cover" class="mb-4" required>

            <label class="block mb-2 text-sm">Manga Pages (Select Multiple):</label>
            <input type="file" name="pages[]" multiple class="mb-6" required>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-500 py-2 rounded font-bold">Publish Manga</button>
        </form>
    </div>
</body>
</html>
