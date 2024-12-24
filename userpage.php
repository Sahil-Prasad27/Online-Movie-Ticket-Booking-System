<?php
session_start();
require "config/config.php";

if (!isset($_SESSION['C_ID'])) {
    header("Location: login.php");
    exit;
}

$query = "SELECT M_ID, M_name, M_duration, M_description, M_image, M_amount, M_date, category FROM movie";
$stmt = $conn->prepare($query);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$randomMovieQuery = "SELECT M_ID, M_name, M_image FROM movie ORDER BY RAND() LIMIT 1";
$randomMovieStmt = $conn->prepare($randomMovieQuery);
$randomMovieStmt->execute();
$recommendedMovie = $randomMovieStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Carousel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .recommendation {
            height: 200px;
        }
    </style>
</head>

<body class="bg-gray-100">


    <div class="bg-white shadow">
        <div class="flex items-center justify-between px-6 py-4">
            
            <img src="img/logo.png" alt="Logo" class="w-24">

            
            <div class="hidden flex-grow md:flex items-center">
                <input
                    id="searchInput"
                    type="text"
                    placeholder="Search for Movies, Events, Plays, Sports and Activities"
                    class="flex-grow px-4 py-2 ml-4 border border-gray-300 rounded-lg" />
            </div>

            
            <div class="hidden md:flex items-center space-x-4">
                <a href="profile.php" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg">Profile</a>
                <a href="logout.php" class="px-4 py-2 text-white bg-red-500 rounded-lg">Logout</a>
            </div>

            
            <button
                class="block md:hidden text-gray-700 focus:outline-none"
                onclick="toggleMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        
        <div id="mobileMenu" class="hidden md:hidden">
            <div class="px-6 py-4">
                <input
                    id="mobileSearchInput"
                    type="text"
                    placeholder="Search for Movies, Events, Plays, Sports and Activities"
                    class="w-full px-4 py-2 mb-4 border border-gray-300 rounded-lg" />
                <a href="profile.php" class="block px-4 py-2 text-gray-700 border border-gray-300 rounded-lg mb-2">Profile</a>
                <a href="logout.php" class="block px-4 py-2 text-white bg-red-500 rounded-lg">Logout</a>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById("mobileMenu");
            menu.classList.toggle("hidden");
        }
    </script>



    <h2 class="mb-4 text-2xl font-bold px-8">Recommendation</h2>
    <div class="px-8 py-6 bg-white shadow mt-4 rounded-lg recommendation flex items-center">
        <?php if ($recommendedMovie): ?>
            <img src="<?php echo htmlspecialchars($recommendedMovie['M_image']); ?>" alt="<?php echo htmlspecialchars($recommendedMovie['M_name']); ?>" class="w-32 h-48 rounded-lg object-cover mr-4">
            <div>
                <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($recommendedMovie['M_name']); ?></h4>
                <a href="payment.php?movieId=<?php echo htmlspecialchars($recommendedMovie['M_ID']); ?>&user_id=<?php echo htmlspecialchars($_SESSION['C_ID']); ?>" class="mt-2 inline-block text-center text-white bg-blue-500 hover:bg-blue-600 rounded py-2 px-4">Book Now</a>
            </div>
        <?php else: ?>
            <p class="text-gray-700">No recommendations available at this time.</p>
        <?php endif; ?>
    </div>


    <div class="flex justify-start space-x-4 px-8 py-4">
        <button class="filter-button px-4 py-2 text-white bg-blue-500 rounded-lg" data-category="all">All</button>
        <button class="filter-button px-4 py-2 text-white bg-green-500 rounded-lg" data-category="Romantic">Romantic</button>
        <button class="filter-button px-4 py-2 text-white bg-yellow-500 rounded-lg" data-category="Funny">Funny</button>
        <button class="filter-button px-4 py-2 text-white bg-red-500 rounded-lg" data-category="Action">Action</button>
    </div>


    <div class="px-8 py-6">
        <h2 class="mb-4 text-2xl font-bold">Movies</h2>
        <div class="relative flex items-center">
            <button id="leftBtn" class="absolute left-0 px-3 py-2 text-white bg-gray-800 rounded-full hover:bg-gray-900">&lt;</button>
            <div id="movieList" class="flex overflow-x-auto scroll-smooth w-full space-x-4">
                <?php foreach ($movies as $movie): ?>
                    <div class="flex-none w-1/4 bg-white rounded-lg shadow-lg movie-item"
                        data-name="<?php echo htmlspecialchars($movie['M_name']); ?>"
                        data-category="<?php echo htmlspecialchars($movie['category']); ?>">
                        <img src="<?php echo htmlspecialchars($movie['M_image']); ?>" alt="<?php echo htmlspecialchars($movie['M_name']); ?>" class="w-full h-48 rounded-t-lg object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($movie['M_name']); ?></h3>
                            <p class="text-gray-700"><?php echo htmlspecialchars($movie['M_duration']); ?> min</p>
                            <p class="text-gray-800 font-bold">Price: Rs<?php echo htmlspecialchars($movie['M_amount']); ?></p>
                            <p class="text-gray-800 font-bold">Movie Type: <?php echo htmlspecialchars($movie['category']); ?></p>
                            <p class="text-gray-800 font-bold">Date and Time: <?php echo htmlspecialchars($movie['M_date']); ?></p>
                            <a href="#" class="mt-2 book-now block text-center text-white bg-blue-500 hover:bg-blue-600 rounded py-2" data-movie-id="<?php echo $movie['M_ID']; ?>">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button id="rightBtn" class="absolute right-0 px-3 py-2 text-white bg-gray-800 rounded-full hover:bg-gray-900">&gt;</button>
        </div>
    </div>

    <script>
        const leftBtn = document.getElementById('leftBtn');
        const rightBtn = document.getElementById('rightBtn');
        const movieList = document.getElementById('movieList');
        const searchInput = document.getElementById('searchInput');


        leftBtn.onclick = () => {
            movieList.scrollBy({
                left: -300,
                behavior: 'smooth'
            });
        };

        rightBtn.onclick = () => {
            movieList.scrollBy({
                left: 300,
                behavior: 'smooth'
            });
        };


        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const movieItems = document.querySelectorAll('.movie-item');
            movieItems.forEach(item => {
                const movieName = item.getAttribute('data-name').toLowerCase();
                if (movieName.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });


        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                const movieItems = document.querySelectorAll('.movie-item');
                movieItems.forEach(item => {
                    const movieCategory = item.getAttribute('data-category');
                    if (category === 'all' || movieCategory === category) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });


        document.querySelectorAll('.book-now').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const movieId = this.getAttribute('data-movie-id');
                window.location.href = 'payment.php?movieId=' + movieId + '&user_id=<?php echo htmlspecialchars($_SESSION["C_ID"]); ?>';
            });
        });
    </script>
</body>

</html>
<?php require "includes/footer.php"; ?>