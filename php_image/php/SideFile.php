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
?>

<!DOCTYPE html>
<html>
<body>

	<form action="" method="post" enctype="multipart/form-data">
	Select file to upload:
	<input type="file" name="fileToUpload" id="fileToUpload">
	<input type="submit" value="Upload File" name="submit">
	</form>
  
	<?php
		// Check if file is a real file
		if(isset($_POST["submit"])) {

			$target_dir = "/var/www/selenium/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

			// Check file size (optional)
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				$uploadOk = 0;
			}

			// Allow certain file formats (optional)
			if($fileType != "side" ) {
				echo "Sorry, only selenium side.";
				$uploadOk = 0;
			}

			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			// If everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
	?>
  
</body>
</html>

<?php
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