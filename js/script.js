<script>
document.getElementById("uploadForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    let formData = new FormData();
    formData.append("file", document.getElementById("fileInput").files[0]);

    // Show loading message
    document.getElementById("message").innerText = "Uploading...";

    // Send AJAX request
    fetch("upload.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Update the message based on the response status
        if (data.status === "success") {
            document.getElementById("message").innerText = "File uploaded successfully!";
            document.getElementById("fileInput").value = '';  // Clear file input
        } else {
            document.getElementById("message").innerText = "Upload failed. Please try again!";
        }
    })
    .catch(error => {
        // Handle errors (e.g., network issues)
        document.getElementById("message").innerText = "An error occurred. Please try again!";
        console.error('Error uploading file:', error);
    });
});
</script>
