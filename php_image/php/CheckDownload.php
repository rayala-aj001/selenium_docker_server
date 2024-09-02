<?php
// Define the directory where the files are stored
$directory = '/var/www/html/filesfolder';

// Scan the directory and get all files, excluding '.' and '..'
$files = array_diff(scandir($directory), array('..', '.'));

// If a file is requested via the 'file' GET parameter
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Get the file name, ensuring no directory traversal
    $filePath = $directory . '/' . $file; // Build the full path to the file

    if (realpath($filePath) === false || strpos(realpath($filePath), $directory) !== 0) {
        echo "Invalid file path.";
        exit;
    }

    // Check if the file exists in the directory
    if (file_exists($filePath)) {
        // Set headers to initiate the download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        // Read the file and output its contents
        readfile($filePath);
        exit; // Stop further script execution
    } else {
        echo "File not found."; // Display an error if the file does not exist
    }
    
} else {
    // If no file is requested, list all available files in the directory
    if (count($files) > 0) {
        echo "<h2>Available Files:</h2><ul>";
        foreach ($files as $file) {
            $fileUrl = 'CheckDownload.php?file=' . urlencode($file); // URL-encode the file name for the link
            echo "<li><a href='$fileUrl'>" . htmlspecialchars($file) . "</a></li>"; // Display the file as a clickable link
        }
        echo "</ul>";
    } else {
        echo "No files available for download."; // Display a message if no files are found
    }
}
?>