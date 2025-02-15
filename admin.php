<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit;
}

include 'db.php';

// Handle file deletion (both from database and server)
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];  // Cast to integer to prevent SQL injection
    $stmt = $conn->prepare("SELECT filepath FROM uploads WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file) {
        // Delete the file from the server
        if (file_exists($file['filepath'])) {
            unlink($file['filepath']);
        }

        // Delete the file record from the database
        $stmt = $conn->prepare("DELETE FROM uploads WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: admin.php");  // Redirect after deletion
    exit;
}

$result = $conn->query("SELECT * FROM uploads ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Uploaded Files</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Uploaded Files</h2>

    <p><a href="logout.php">Logout</a></p> <!-- Logout link -->

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Filename</th>
            <th>Filepath</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['filename']); ?></td>
            <td><a href="download.php?file=<?php echo urlencode($row['filename']); ?>">Download</a></td>
            <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
            <td><a href="admin.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
