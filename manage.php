<?php
require "config/config.php";
require "includes/header.php";
session_start();


if (!isset($_SESSION['user_email'])) {
    header("Location: admin.php");
    exit();
}


$movie = [
    'M_ID' => '',
    'M_name' => '',
    'M_duration' => '',
    'M_description' => '',
    'M_image' => '',
    'M_Amount' => '',
    'category' => '',
    'M_date' => ''
];

try {

    if (isset($_POST['add_movie'])) {
        $movie_id = $_POST['movie_id'];
        $movie_name = $_POST['movie_name'];
        $movie_duration = $_POST['movie_duration'];
        $movie_description = $_POST['movie_description'];
        $movie_image = $_POST['movie_image'];
        $movie_amount = $_POST['movie_amount'];
        $movie_category = $_POST['movie_category'];
        $movie_date = $_POST['movie_date'];
        $ce_place = $_POST['ce_place'];
        $ce_seat_no = $_POST['ce_seat_no'];
        

        try {

            $conn->beginTransaction();


            $sql = "INSERT INTO movie (M_ID, M_name, M_duration, M_description, M_image, M_Amount ,category,M_date)
                VALUES (:movie_id, :movie_name, :movie_duration, :movie_description, :movie_image, :movie_amount, :movie_category, :movie_date)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':movie_id' => $movie_id,
                ':movie_name' => $movie_name,
                ':movie_duration' => $movie_duration,
                ':movie_description' => $movie_description,
                ':movie_image' => $movie_image,
                ':movie_amount' => $movie_amount,
                ':movie_category' => $movie_category,
                ':movie_date' => $movie_date
            ]);


            $movie_id = $conn->lastInsertId();


            $sql = "INSERT INTO cinemahall (Ce_place, Ce_SeatNo, M_ID) 
                VALUES (:ce_place, :ce_seat_no, :movie_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':ce_place' => $ce_place,
                ':ce_seat_no' => $ce_seat_no,
                ':movie_id' => $movie_id
            ]);


            $conn->commit();

            echo "<script>alert('Movie added successfully!');</script>";
        } catch (PDOException $e) {

            $conn->rollBack();
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }


    
    if (isset($_POST['update_movie'])) {
        $movie_id = $_POST['movie_id'];

        $sql = "SELECT * FROM movie WHERE M_ID = :movie_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':movie_id' => $movie_id]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($movie) {
            echo "<script>alert('Movie fetched successfully!');</script>";
        } else {
            echo "<script>alert('Movie not found.');</script>";
            $movie['M_ID']="";

        }
    }

    
    if (isset($_POST['save_update'])) {
        $movie_id = $_POST['movie_id'];
        $movie_name = $_POST['movie_name'];
        $movie_duration = $_POST['movie_duration'];
        $movie_description = $_POST['movie_description'];
        $movie_image = $_POST['movie_image'];
        $movie_amount = $_POST['movie_amount'];
        $movie_category = $_POST['movie_category'];
        $movie_date = $_POST['movie_date'];
    
        $sql = "UPDATE movie SET M_name = :movie_name, M_duration = :movie_duration, 
                M_description = :movie_description, M_image = :movie_image, M_Amount = :movie_amount , category = :movie_category , M_date = :movie_date WHERE M_ID = :movie_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':movie_id' => $movie_id,
            ':movie_name' => $movie_name,
            ':movie_duration' => $movie_duration,
            ':movie_description' => $movie_description,
            ':movie_image' => $movie_image,
            ':movie_amount' => $movie_amount,
            ':movie_category' => $movie_category,
            ':movie_date' => $movie_date
        ]);
    
        echo "<script>alert('Movie updated successfully!');</script>";
    }
    

    
    if (isset($_POST['delete_movie'])) {
        $movie_id = $_POST['movie_id'];

        
        $sql = "DELETE FROM cinemahall WHERE Ce_ID = :movie_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':movie_id' => $movie_id]);

        
        $sql = "DELETE FROM movie WHERE M_ID = :movie_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':movie_id' => $movie_id]);

        echo "<script>alert('Movie deleted successfully!');</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
}


$sql = "SELECT * FROM movie";
$stmt = $conn->prepare($sql);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">
    <div class="container mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Movie Management</h1>

        
        <h2 class="text-lg font-semibold mb-4">Add Movie</h2>
        <form action="" method="POST" class="space-y-4">
            <input type="hidden" name="movie_id" value="">
            <div>
                <label for="movie_name">Movie Name</label>
                <input type="text" name="movie_name" id="movie_name" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="movie_duration">Duration (in minutes)</label>
                <input type="number" name="movie_duration" id="movie_duration" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="movie_description">Description</label>
                <textarea name="movie_description" id="movie_description" class="w-full p-2 border rounded"></textarea>
            </div>
            <div>
                <label for="movie_image">Image URL</label>
                <input type="text" name="movie_image" id="movie_image" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="movie_amount">Amount</label>
                <input type="number" name="movie_amount" id="movie_amount" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="ce_place">Cinema Hall Place</label>
                <input type="text" name="ce_place" id="ce_place" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="ce_seat_no">Seat Number</label>
                <input type="number" name="ce_seat_no" id="ce_seat_no" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="movie_category">Category</label>
                <input type="text" name="movie_category" id="movie_category" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label for="movie_date">Release Date</label>
                <input type="datetime-local" name="movie_date" id="movie_date" class="w-full p-2 border rounded" required>
            </div>

            <button type="submit" name="add_movie" class="px-4 py-2 bg-blue-500 text-white rounded">Add Movie</button>
        </form>

        
        <h2 class="text-lg font-semibold mt-8 mb-4">Update Movie</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="movie_id">Movie ID</label>
                <input type="text" name="movie_id" id="movie_id" class="w-full p-2 border rounded" value="<?= htmlspecialchars($movie['M_ID']) ?>">
            </div>
            <button type="submit" name="update_movie" class="px-4 py-2 bg-green-500 text-white rounded">Fetch Movie</button>
        </form>

        
        <?php if ($movie['M_ID']): ?>
            <form action="" method="POST" class="space-y-4 mt-4">
                <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie['M_ID']) ?>">
                <div>
                    <label for="movie_name">Movie Name</label>
                    <input type="text" name="movie_name" id="movie_name" class="w-full p-2 border rounded" value="<?= htmlspecialchars($movie['M_name']) ?>" required>
                </div>
                <div>
                    <label for="movie_duration">Duration (in minutes)</label>
                    <input type="number" name="movie_duration" id="movie_duration" class="w-full p-2 border rounded" value="<?= htmlspecialchars($movie['M_duration']) ?>" required>
                </div>
                <div>
                    <label for="movie_description">Description</label>
                    <textarea name="movie_description" id="movie_description" class="w-full p-2 border rounded"><?= htmlspecialchars($movie['M_description']) ?></textarea>
                </div>
                <div>
                    <label for="movie_image">Image URL</label>
                    <input type="text" name="movie_image" id="movie_image" class="w-full p-2 border rounded" value="<?= htmlspecialchars($movie['M_image']) ?>" required>
                </div>
                <div>
                    <label for="movie_amount">Amount</label>
                    <input type="number" name="movie_amount" id="movie_amount" class="w-full p-2 border rounded" value="<?= htmlspecialchars($movie['M_Amount']) ?>" required>
                </div>
                <div>
                    <label for="movie_category">Category</label>
                    <input type="text" name="movie_category" id="movie_category" class="w-full p-2 border rounded" required value="<?= htmlspecialchars($movie['category']) ?>" required>
                </div>
                <div>
                    <label for="movie_date">Release Date</label>
                    <input type="datetime-local" name="movie_date" id="movie_date" class="w-full p-2 border rounded" required value="<?= htmlspecialchars($movie['M_date']) ?>">
                </div>

                <button type="submit" name="save_update" class="px-4 py-2 bg-yellow-500 text-white rounded">Save Updates</button>
            </form>
        <?php endif; ?>

        
        <h2 class="text-lg font-semibold mt-8 mb-4">Movie List</h2>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4">Movie ID</th>
                    <th class="py-2 px-4">Movie Name</th>
                    <th class="py-2 px-4">Duration</th>
                    <th class="py-2 px-4">Description</th>
                    <th class="py-2 px-4">Image</th>
                    <th class="py-2 px-4">Amount</th>
                    <th class="py-2 px-4">Category</th>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td class="py-2 px-4"><?= htmlspecialchars($movie['M_ID']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($movie['M_name']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($movie['M_duration']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($movie['M_description']) ?></td>
                        <td class="border p-2">
                            <img src="<?= htmlspecialchars($movie['M_image']) ?>" alt="Movie Image" class="w-20 h-20 object-cover">
                        </td>

                        <td class="py-2 px-4"><?= htmlspecialchars($movie['M_Amount']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($movie['category']) ?></td>
                        <td class="py-2 px-4"><?= htmlspecialchars($movie['M_date']) ?></td>
                        <td class="py-2 px-4">
                            <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this movie?')">
                                <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie['M_ID']) ?>">
                                <button type="submit" name="delete_movie" class="px-4 py-2 bg-red-500 text-white rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<?php require "includes/footer.php"; ?>