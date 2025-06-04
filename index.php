<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['zipfile'])) {
    $filename = $_FILES['zipfile']['name'];
    $tmpname = $_FILES['zipfile']['tmp_name'];
    $destination = "uploads/" . basename($filename);

    if (move_uploaded_file($tmpname, $destination)) {
        $stmt = $conn->prepare("INSERT INTO files (filename) VALUES (?)");
        $stmt->bind_param("s", $filename);
        $stmt->execute();
        echo "File uploaded successfully!";
    } else {
        echo "Upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CTF File Server</title>
</head>
<body>
    <h2>Upload ZIP File</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="zipfile" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Available ZIP File</h2>
    <ul>
        <?php
        $res = $conn->query("SELECT * FROM files");
        while ($row = $res->fetch_assoc()) {
            echo "<li><a href='uploads/{$row['filename']}' download>{$row['filename']}</a></li>";
        }
        ?>
    </ul>
</body>
</html>
