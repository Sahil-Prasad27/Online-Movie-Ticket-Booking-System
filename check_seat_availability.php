<?php


$mysqli = new mysqli("your_host", "your_user", "your_password", "movieticket");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$movieId = $_GET['movieId'];
$seatNo = $_GET['seatNo'];


$query = "SELECT Ce_SeatNo FROM cinemahall WHERE Ce_SeatNo = ?"; 
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $seatNo);
$stmt->execute();
$stmt->store_result();

$response = array();
$response['available'] = $stmt->num_rows == 0; 

$stmt->close();
$mysqli->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
