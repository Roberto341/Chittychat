<?php
$conn = mysqli_connect("localhost", "root", "", "chat");

$sql = "SELECT * FROM users WHERE kicked='1'";
$result= mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)){
	echo "<li>". $row['user_name'].", ". $row['kicked']."</li>";
}
?>
