<?php
session_start();

// Define your password here
$correct_password =  $_ENV['SE_VNC_PASSWORD'];


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $correct_password) {
        $_SESSION['authenticated'] = true;
    } else {
        $error = "Invalid password. Please try again.";
    }
}

// If the user is authenticated, show the protected content
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {

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
} else {

?>

<!DOCTYPE html>
<html lang="en">
<body>
    <h1>Please enter the password to continue</h1>
    <form method="post">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Submit</button>
    </form>

    <?php
    // Display an error message if the password is incorrect
    if (isset($error)) {
        echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
    }
    ?>
</body>
</html>

<?php
}
?>