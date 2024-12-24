<?php
require "config/config.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $phoneNumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $uniqueCode = $_POST['unique-code'];
    
    if ($uniqueCode == 12345) {
        try {
            $stmt = $conn->prepare("INSERT INTO admin (A_phoneNo, A_email, A_pass) VALUES (:phone, :email, :password)");
            $stmt->bindParam(':phone', $phoneNumber);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            
            if ($stmt->execute()) {
                echo "<p class='text-center text-green-500'>Registration successful!</p>";
                header("Location: admin.php");
            } else {
                echo "<p class='text-center text-red-500'>Execution error: " . implode(", ", $stmt->errorInfo()) . "</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='text-center text-red-500'>Preparation failed: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='text-center text-red-500'>Invalid unique code.</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegistrationAdmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow p-8 w-full max-w-md dark:bg-gray-800">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create an admin account</h1>
                <p class="text-sm font-light text-gray-500 dark:text-gray-400">Join HaruMov today!</p>
            </div>
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="phonenumber" class="block text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                    <input type="number" name="phonenumber" id="phonenumber" class="input-field" placeholder="1234567890" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                    <input type="email" name="email" id="email" class="input-field" placeholder="name@company.com" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
                </div>
                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                    <input type="password" name="confirm-password" id="confirm-password" class="input-field" placeholder="••••••••" required>
                </div>
                <div>
                    <label for="unique-code" class="block text-sm font-medium text-gray-900 dark:text-white">Enter the unique code</label>
                    <input type="number" name="unique-code" id="unique-code" class="input-field" placeholder="12345" required>
                </div>
                <div class="flex items-center">
                    <input id="terms" type="checkbox" class="h-4 w-4 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" required>
                    <label for="terms" class="ml-2 text-sm text-gray-500 dark:text-gray-300">I accept the <a href="#" class="text-primary-600 hover:underline dark:text-primary-500">Terms and Conditions</a></label>
                </div>
                <button type="submit" class="btn-primary w-full">Create Account</button>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    Already have an account? <a href="admin.php" class="text-primary-600 hover:underline dark:text-primary-500">Login here</a>
                </p>
            </form>
        </div>
    </section>

    <style>
        .input-field {
            background-color: #F9FAFB;
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            color: #374151;
            padding: 0.625rem;
            width: 100%;
        }

        .btn-primary {
            background-color: #3B82F6;
            color: #FFFFFF;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #2563EB;
        }
    </style>
</body>
</html>

<?php require "includes/footer.php"; ?>
