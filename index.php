<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "db.php";

$upload_dir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zipfile'])) {
    $filename = basename($_FILES['zipfile']['name']);
    $target = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['zipfile']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO files (filename) VALUES (?)");
        $stmt->bind_param("s", $filename);
        $stmt->execute();
        echo "<p>✅ File berhasil diupload!</p>";
    } else {
        echo "<p>❌ Upload gagal.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload ZIP CTF</title>
</head>
<body>
    <h2>Upload File ZIP</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="zipfile" accept=".zip" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Download ZIP File:</h3>
    <ul>
        <?php
        $result = $conn->query("SELECT * FROM files ORDER BY uploaded_at DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='uploads/{$row['filename']}'>{$row['filename']}</a></li>";
        }
        ?>
    </ul>
</body>
</html>
