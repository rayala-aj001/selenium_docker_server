<?php
session_start();
include '../../selenium/mysql_var.php';
$currentUri = $_SERVER['REQUEST_URI'];
$servername = $_SERVER['SERVER_NAME'];
$urlloc = str_replace('listofName.php', 'displayics.php?id=', 'https://' .$servername . $currentUri);
$tablename = basename(dirname(__FILE__));

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
    
    $con=mysqli_connect($host, $username, $password, $db);
    // Check connection
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $result = mysqli_query($con,"SELECT file_name FROM Calendar_{$tablename}");

    echo "<table border='1'>
    <tr>
    <th>File Name</th>
    <th>URL</th>
    </tr>";

    while($row = mysqli_fetch_array($result))
    {
    echo "<tr>";
    echo "<td>" . $row['file_name'] . "</td>"; 
    echo "<td>" . $urlloc .rawurlencode($row['file_name']) . "</td>";
    echo "</tr>";
    }
    echo "</table>";

    mysqli_close($con);
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