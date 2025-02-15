<?php
include 'db.php';

$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);  // Create the directory if it doesn't exist
}

// Sanitize the file name to avoid issues with special characters or paths
$fileName = basename($_FILES["file"]["name"]);
$fileName = preg_replace("/[^a-zA-Z0-9._-]/", "_", $fileName); // Sanitize

$targetFile = $targetDir . $fileName;
$uploadOk = 1;

// Check if the file is a valid image or type (optional)
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];  // Allow these file types

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(["status" => "error", "message" => "Invalid file type. Only JPG, JPEG, PNG, GIF, and PDF are allowed."]);
    $uploadOk = 0;
}

// Check file size (e.g., limit to 5MB)
if ($_FILES["file"]["size"] > 5000000) { // 5MB limit
    echo json_encode(["status" => "error", "message" => "File is too large."]);
    $uploadOk = 0;
}

if ($uploadOk == 1) {
    // Try to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        // Prepare and execute the SQL insert
        $stmt = $conn->prepare("INSERT INTO uploads (filename, filepath) VALUES (?, ?)");
        $stmt->bind_param("ss", $fileName, $targetFile);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "file" => $fileName]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to save file information to database."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "There was an error uploading the file."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "File upload failed."]);
}
?>
