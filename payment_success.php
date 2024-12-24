<?php
session_start();
require "config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files (update with actual path to PHPMailer folder)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Check if M_ID is present in the URL
if (!isset($_GET['M_ID'])) {
    echo "Movie ID missing.";
    exit;
}

$movieId = $_GET['M_ID'];

$sql = "SELECT M_name, M_duration FROM movie WHERE M_ID = :movieId";
$stmt = $conn->prepare($sql);
$stmt->execute(['movieId' => $movieId]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if ($movie) {
    $movieName = htmlspecialchars($movie['M_name']);
    $movieDuration = $movie['M_duration'];
    
    
    $hours = intdiv($movieDuration, 60);
    $minutes = $movieDuration % 60;
    $durationFormatted = sprintf("%d hrs %02d mins", $hours, $minutes);
} else {
    echo "Movie not found.";
    exit;
}


$availableSeats = "A12";


if (isset($_SESSION['C_name']) && isset($_SESSION['C_email'])) {
    $customerName = htmlspecialchars($_SESSION['C_name']);
    $customerEmail = $_SESSION['C_email'];
} else {
    echo "User not logged in.";
    exit;
}


$subject = "Your Movie Ticket for " . $movieName;
$message = "
Hello $customerName,

Your ticket for the movie \"$movieName\" has been successfully generated!

Movie Details:
- Movie: $movieName
- Duration: $durationFormatted
- Seat Number: $availableSeats

Enjoy your movie!

Thank you for choosing our service.

Best regards,
HaruMov Team
";


$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    // $mail->Username = 'your email'; 
    // $mail->Password = 'your email password ';  
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    
    // $mail->setFrom('your email', 'HaruMov Team');
    $mail->addAddress($customerEmail, $customerName);

    
    $mail->Subject = $subject;
    $mail->Body = $message;

    // Send email
    $mail->send();
    
} catch (Exception $e) {
    echo "Failed to send email. Error: " . $mail->ErrorInfo;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Additional styling to make it look like a ticket */
        .ticket-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background: #fef3c7;
            border: 2px dashed #fbbf24;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="ticket-container">
        <h1 class="text-2xl font-bold mb-4">🎉 Payment Successful!</h1>
        <p class="mb-2 text-lg">Your ticket for <strong><?php echo $movieName; ?></strong> has been generated!</p>
        <p class="mb-2">Duration: <?php echo $durationFormatted; ?></p>
        <p class="mb-4">Seat Number: <strong><?php echo $availableSeats; ?></strong></p>
        <p class="text-gray-600 mb-6">Enjoy your movie!</p>
        <a href="userpage.php" class="bg-blue-500 text-white py-2 px-4 rounded">Return to Home</a>
    </div>
</body>
</html>
