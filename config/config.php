<?php

$host ="localhost";

$dbname = "movieticket";

$user = "root";

$pass = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname",$user,$pass);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if($conn){

}
else{
    echo "data base issue";
}