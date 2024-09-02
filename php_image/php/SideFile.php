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
