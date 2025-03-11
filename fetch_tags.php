<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect('localhost','root','','tms_db');
if(!$conn){
    die('Connection Failed: ' . mysqli_connect_error());
}

$sql = "SELECT DISTINCT tags FROM files";
$result = $conn->query($sql);

$tags = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tags[] = $row["tags"];
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($tags);
?>