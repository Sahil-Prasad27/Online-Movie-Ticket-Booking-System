<?php

$correct_code = "12345";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_code = $_POST['code'] ?? '';

    if ($input_code === $correct_code) {
        
        header("Location: boxOffice.php");
        exit();
    } else {
        $error = "Invalid code. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-sm bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4 text-center">Enter Verification Code</h2>
    <?php if (!empty($error)): ?>
        <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="code" class="block text-gray-700 font-bold mb-2">Verification Code:</label>
        <input type="text" name="code" id="code" required
               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md mt-4 hover:bg-blue-600">
            Verify
        </button>
    </form>
</div>

</body>
</html>
