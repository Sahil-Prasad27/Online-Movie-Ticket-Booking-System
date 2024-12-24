<?php require "config/config.php"; ?>
<?php require "includes/registrationHeader.php"; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $type = "user";

    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customer (C_name, C_email, c_phonenumber, C_pass, type) 
                    VALUES (:name, :email, :phonenumber, :password, :type)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phonenumber', $phonenumber);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':type', $type);

        $stmt->execute();
        echo "<script>alert('Account created successfully.');</script>";
        header("Location: login.php");
    }
}
$conn = null;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow p-8 w-full max-w-md dark:bg-gray-800">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create an account</h1>
                <p class="text-sm font-light text-gray-500 dark:text-gray-400">Join HaruMov today!</p>
            </div>
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="name" id="name" class="input-field" placeholder="Your name" required>
                </div>
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
                    <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required minlength="8">
                </div>
                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                    <input type="password" name="confirm-password" id="confirm-password" class="input-field" placeholder="••••••••" required minlength="8">
                </div>
                <div class="flex items-center">
                    <input id="terms" type="checkbox" class="h-4 w-4 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" required>
                    <label for="terms" class="ml-2 text-sm text-gray-500 dark:text-gray-300">I accept the <a href="#" class="text-primary-600 hover:underline dark:text-primary-500">Terms and Conditions</a></label>
                </div>
                <button type="submit" class="btn-primary w-full">Create an account</button>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    Already have an account? <a href="login.php" class="text-primary-600 hover:underline dark:text-primary-500">Login here</a>
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