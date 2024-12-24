<?php
session_start();
require "config/config.php";


if (!isset($_SESSION['C_ID'])) {
    header("Location: login.php");
    exit;
}

// Fetch user data
$user_id = $_SESSION['C_ID']; // Use C_ID from session
$query = "SELECT * FROM customer WHERE C_ID = :user_id";
$stmt = $conn->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user was found
if (!$user) {
    // Handle case where user is not found
    echo "User not found.";
    exit();
}

// Fetch user tickets
$query_tickets = "SELECT * FROM ticket WHERE C_ID = :user_id";
$stmt_tickets = $conn->prepare($query_tickets);
$stmt_tickets->execute(['user_id' => $user_id]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

// Fetch user tickets with movie details
$query_tickets = "
    SELECT t.*, m.M_date 
    FROM ticket t 
    JOIN movie m ON t.M_ID = m.M_ID 
    WHERE t.C_ID = :user_id
";
$stmt_tickets = $conn->prepare($query_tickets);
$stmt_tickets->execute(['user_id' => $user_id]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script><!-- Add your Tailwind CSS link here -->
</head>

<body class="bg-gray-100">
    <div class="flex items-center space-x-4">

        <a href="userpage.php" class="px-4 py-2 text-white bg-red-500 rounded-lg">Back</a>
    </div>
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">User Profile</h1>

        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 border">Field</th>
                    <th class="py-3 px-4 border">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-gray-50">
                    <td class="py-2 px-4 border">Name</td>
                    <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['C_name']); ?></td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="py-2 px-4 border">Email</td>
                    <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['C_email']); ?></td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="py-2 px-4 border">Phone Number</td>
                    <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['c_phonenumber']); ?></td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="py-2 px-4 border">Account Created</td>
                    <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['C_time']); ?></td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="py-2 px-4 border">User Type</td>
                    <td class="py-2 px-4 border"><?php echo htmlspecialchars($user['type']); ?></td>
                </tr>
            </tbody>
        </table>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8">Your Tickets</h2>
        <table class="min-w-full border border-gray-300 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 border">Ticket ID</th>
                    <th class="py-3 px-4 border">Movie ID</th>
                    <th class="py-3 px-4 border">Seat No</th>
                    <th class="py-3 px-4 border">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tickets): ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr class="bg-gray-50">
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($ticket['T_ID']); ?></td>
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($ticket['M_ID']); ?></td>
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($ticket['Ce_SeatNo']); ?></td>
                            <td class="py-2 px-4 border"><?php echo htmlspecialchars($ticket['M_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="py-2 px-4 border text-center">No tickets found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>