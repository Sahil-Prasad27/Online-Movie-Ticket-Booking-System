<?php
session_start();
require "config/config.php";



if (!isset($_SESSION['C_ID'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['movieId']) || empty($_GET['movieId'])) {
    echo "Movie ID not provided.";
    exit;
}

try {
    $movieId = $_GET['movieId'];


    $query = "SELECT M_name, M_duration, M_description, M_image, M_amount FROM movie WHERE M_ID = :movieId";
    $stmt = $conn->prepare($query);
    $stmt->execute(['movieId' => $movieId]);
    $movie = $stmt->fetch();


    if (!$movie) {
        echo "Movie not found.";
        exit;
    }


    $durationInMinutes = (int)$movie['M_duration'];
    $hours = floor($durationInMinutes / 60);
    $minutes = $durationInMinutes % 60;
    $durationFormatted = sprintf('%02d:%02d', $hours, $minutes);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .payment-container {
            max-width: 400px;

        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="payment-container bg-white p-6 rounded-lg shadow-lg">

        <h2 class="text-2xl mb-4">Payment for <?php echo htmlspecialchars($movie['M_name']); ?></h2>
        <img src="<?php echo htmlspecialchars($movie['M_image']); ?>" alt="<?php echo htmlspecialchars($movie['M_name']); ?>" class="mb-4 w-2/3 h-auto rounded mx-auto">
        <p class="mb-2">Duration: <?php echo $durationFormatted; ?> (hour)</p>
        <p class="mb-2">Price: Rs <?php echo htmlspecialchars($movie['M_amount']); ?></p>
        <p class="mb-4">Description: <?php echo nl2br(htmlspecialchars($movie['M_description'])); ?></p>
        <p>Please confirm your payment.</p>

        <form method="POST" action="confirm_payment.php">

            <input type="hidden" name="M_ID" value="<?php echo $movieId; ?>">
            <input type="hidden" name="M_Amount" value="<?php echo htmlspecialchars($movie['M_amount']); ?>">
            <?php
            require('config1.php');
            ?>
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo $publishableKey ?>"
                data-amount="<?php echo $movie['M_amount'] * 100 ?>"
                data-name="HaruMov"
                data-description="Watch and Enjoy"
                data-image=""
                data-currency="inr">
            </script>
        </form>
        <div class="items-end space-x-4">

            <a href="userpage.php" class="px-4 py-2 text-white bg-red-500 rounded-lg">Cancel</a>
        </div>
    </div>
</body>

</html>