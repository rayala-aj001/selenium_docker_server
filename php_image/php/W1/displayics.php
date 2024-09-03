<?php
include '../../selenium/mysql_var.php';
$tablename = basename(dirname(__FILE__));

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET");
if(!empty($_GET['id'])){    
    //Create the connection and select the database
    $db = new mysqli($host, $username, $password, $db);
    
    // Check the connection
    if($db->connect_error){
        die("Connexion error: " . $db->connect_error);
    }
    
    //Get the image from the database
    $res = $db->query("SELECT ics FROM Calendar_{$tablename} WHERE file_name = '".htmlspecialchars($_GET['id'])."'");
    
    if($res->num_rows > 0){
        $blob = $res->fetch_assoc();
        
        //Render the blob
        header("Content-type: text/calendar"); 
        echo $blob['ics']; 
    }else{
        echo ' ics not found...';
    }
}
?>