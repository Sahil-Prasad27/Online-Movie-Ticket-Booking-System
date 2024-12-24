<?php
require "config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? null;
    $new_password = $_POST['new_password'] ?? null;

    try {
        if (isset($email) && !isset($new_password)) {
            $query = "SELECT * FROM customer WHERE C_email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $step = 2; 
            } else {
                $error = "Email not found. Please try again.";
            }
        } elseif (isset($email) && isset($new_password)) {
            
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Encrypt the password
            $update_query = "UPDATE customer SET C_pass = :new_password WHERE C_email = :email";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':new_password', $hashed_password);
            $update_stmt->bindParam(':email', $email);

            if ($update_stmt->execute()) {
                $success = "Password successfully updated. You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    } catch (PDOException $e) {
        $error = "A database error occurred: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">


<div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold mb-4 text-center">Forgot Password</h2>
    <?php if (!empty($error)): ?>
        <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
    <?php elseif (!empty($success)): ?>
        <p class="text-green-500 text-center mb-4"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (empty($step) || $step == 1): ?>
        
        
        <form method="POST" action="">
            <label for="email" class="block text-gray-700 font-bold mb-2">Enter Your Email:</label>
            <input type="email" name="email" id="email" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md mt-4 hover:bg-blue-600">
                Verify Email
            </button>
            <div class=" items-start space-x-4">

<a href="userpage.php" class="px-4 py-2 text-white bg-red-500 rounded-lg">Back</a>
</div>
        </form>
    <?php elseif ($step == 2): ?>
       
        <form method="POST" action="">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <label for="new_password" class="block text-gray-700 font-bold mb-2">Enter New Password:</label>
            <input type="password" name="new_password" id="new_password" required
                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required minlength="8">
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-md mt-4 hover:bg-green-600">
                Reset Password
            </button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
