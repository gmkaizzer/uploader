<?php
// Check if the 'file' parameter is set in the URL query string
if (isset($_GET['file'])) {
    // Sanitize the filename input to avoid directory traversal and other security risks
    $file = basename($_GET['file']);
    $filePath = "uploads/" . $file;

    // Ensure the file exists and is a valid file
    if (file_exists($filePath) && is_file($filePath)) {
        // Set the headers to force download the file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Read the file and send it to the browser
        readfile($filePath);
        exit;
    } else {
        // If the file doesn't exist, show an error message
        echo "File not found.";
    }
} else {
    // If the 'file' parameter is missing, inform the user
    echo "No file specified.";
}
?>
