<?php

include '../../selenium/mysql_var.php';
$currentUri = $_SERVER['REQUEST_URI'];
$urlloc = str_replace('listofName.php', 'displayics.php?id=', $currentUri);

$con=mysqli_connect($host, $username, $password, $db);
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT file_name FROM Calendar_WM_a");

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
?>