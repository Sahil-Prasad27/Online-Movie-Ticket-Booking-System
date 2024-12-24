<?php
session_start();
require "config/config.php";
require "config1.php"; 


if (!isset($_SESSION['C_ID'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['M_ID'], $_POST['M_Amount'])) {
        $M_ID = $_POST['M_ID'];
        $amount = $_POST['M_Amount'];
        $customerId = $_SESSION['C_ID'];

       
        try {
            
            $conn->beginTransaction();

            
            $query = "SELECT Ce_SeatNo, Ce_ID FROM cinemahall WHERE Ce_ID = (SELECT Ce_ID FROM cinemahall WHERE M_ID = :M_ID LIMIT 1) LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute(['M_ID' => $M_ID]);
            $cinemaHall = $stmt->fetch();

            if (!$cinemaHall) {
                throw new Exception("Cinema Hall data not found.");
            }

            $availableSeats = (int)$cinemaHall['Ce_SeatNo'];
            if ($availableSeats <= 0) {
                throw new Exception("No seats available.");
            }

            
            $updateSeatsQuery = "UPDATE cinemahall SET Ce_SeatNo = Ce_SeatNo - 1 WHERE Ce_ID = :ceId";
            $updateSeatsStmt = $conn->prepare($updateSeatsQuery);
            $updateSeatsStmt->execute(['ceId' => $cinemaHall['Ce_ID']]);

            
            $insertTicketQuery = "INSERT INTO ticket (M_ID, Ce_ID, Ce_SeatNo, C_ID) VALUES (:M_ID, :ceId, :seatNo, :customerId)";
            $insertTicketStmt = $conn->prepare($insertTicketQuery);
            $insertTicketStmt->execute([
                'M_ID' => $M_ID,
                'ceId' => $cinemaHall['Ce_ID'],
                'seatNo' => $availableSeats,
                'customerId' => $customerId
            ]);

            $conn->commit();

            
            header("Location: payment_success.php?M_ID=" . $M_ID);
            exit;

        } catch (Exception $e) {
            $conn->rollBack();
            echo "Payment error: " . $e->getMessage();
        }
    } else {
        echo "Payment error: Movie ID or Amount is missing.";
    }
} else {
    echo "Invalid request method.";
}
?>
