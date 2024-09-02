<?php
include '../../selenium/mysql_var.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");

$fileName = $_POST["fileName"];
$file = $_FILES['calBlob']['tmp_name'];
$blob = addslashes(file_get_contents($file));

echo $fileName;
echo $blob;

//Create the connection and select the database
$db = new mysqli($host, $username, $password, $db);

// Check the connection
if($db->connect_error){
	die("Connexion error: " . $db->connect_error);
};

//Insert the blob into the database
$query = $db->query("REPLACE INTO Calendar_SVC (file_name, ics) VALUES ('{$fileName}','{$blob}')");
if($query){
	echo "File uploaded successfully.";
}else{
	echo "File upload failed.". $db->error;
};
$db->close();
?>