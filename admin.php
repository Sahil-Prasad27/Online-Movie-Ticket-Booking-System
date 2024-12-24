<?php require "config/config.php"; ?>


<?php 
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT A_pass, A_email FROM admin WHERE A_email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['A_pass'])) {
        $_SESSION['user_email'] = $email; 
        echo "<script>alert('Login successful. Welcome, " . htmlspecialchars($user['A_email']) . "!');</script>";
        header("Location: manage.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}
$conn = null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow p-8 w-full max-w-md dark:bg-gray-800">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Login</h1>
                <p class="text-sm font-light text-gray-500 dark:text-gray-400">Welcome back! Please log in.</p>
            </div>
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                    <input type="email" name="email" id="email" class="input-field" placeholder="name@company.com" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn-primary w-full">Login</button>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    Don't have an account? <a href="registeradmin.php" class="text-primary-600 hover:underline dark:text-primary-500">| Create one</a><a href="login.php" class="text-primary-600 hover:underline dark:text-primary-500"> | User Login</a>
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
