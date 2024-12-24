<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaruMov</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    
    <div class="flex items-center justify-between px-6 py-4 bg-white shadow">
        <img src="img/logo.png" alt="Logo" class="w-32">
        <input type="text" placeholder="Search for Movies, Events, Plays, Sports and Activities" class="flex-grow px-4 py-2 ml-4 mr-6 border border-gray-300 rounded-lg">
        <button class="px-4 py-2 text-white bg-red-500 rounded-lg" href="login.php">Sign In</button>
    </div>

    
    <div class="px-8 py-6">
        <h2 class="mb-4 text-2xl font-bold">Movies</h2>
        <div class="relative flex items-center">
            <button id="leftBtn" class="absolute left-0 px-3 py-2 text-white bg-gray-800 rounded-full hover:bg-gray-900">&lt;</button>
            <div id="movieList" class="flex overflow-x-auto scroll-smooth w-full space-x-4 pl-10">
                
            </div>
            <button id="rightBtn" class="absolute right-0 px-3 py-2 text-white bg-gray-800 rounded-full hover:bg-gray-900">&gt;</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const movieList = document.getElementById('movieList');
            const leftBtn = document.getElementById('leftBtn');
            const rightBtn = document.getElementById('rightBtn');

            
            const movies = [
                {
                    M_image: "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRzneJsLoxHJtPqBcyucXBcsSMERY75nB7A5KXXEHdBKFidLiCleHQf5ECgbb0-gMdQOitL",
                    M_name: "Harold and the Purple Crayon",
                    M_description: "Fantasy",
                    M_duration: "2h 30m"
                },
                {
                    M_image: "https://upload.wikimedia.org/wikipedia/en/c/c3/Tekka_poster_%282%29.jpg",
                    M_name: "Tekka",
                    M_description: "Thriller",
                    M_duration: "1h 45m"
                },
                {
                    M_image: "https://m.media-amazon.com/images/M/MV5BZjdmYTE1N2ItZDE5Yi00ZGFhLWIxOGUtYzUyMGI5OGUxZGZmXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg",
                    M_name: "Phullwanti",
                    M_description: "Romantic",
                    M_duration: "2h"
                },
                {
                    M_image: "https://upload.wikimedia.org/wikipedia/en/e/e8/Kanguva_poster.jpg",
                    M_name: "Kanguva",
                    M_description: "Fantasy",
                    M_duration: "2h 10m"
                },
                {
                    M_image: "https://upload.wikimedia.org/wikipedia/en/9/98/Brother_poster.jpg",
                    M_name: "Brother",
                    M_description: "Family",
                    M_duration: "2h 15m"
                },
                {
                    M_image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8hhF1sytF7BwSt6PoRRJsD2USbQN5Z27WYw&s",
                    M_name: "The King: Eternal Monarch",
                    M_description: "Family",
                    M_duration: "2h 15m"
                }
            ];

            
            movies.forEach(movie => {
                const movieItem = document.createElement('div');
                movieItem.classList.add('movie', 'w-48', 'flex-shrink-0', 'bg-white', 'rounded-lg', 'shadow-lg', 'p-4', 'text-center');

                movieItem.innerHTML = `
                    <img src="${movie.M_image}" alt="${movie.M_name}" class="w-full h-64 object-cover rounded-lg mb-4">
                    <h3 class="text-lg font-semibold">${movie.M_name}</h3>
                    <p class="text-sm text-gray-600">${movie.M_description}</p>
                    <p class="text-sm text-gray-500 mt-2">${movie.M_duration}</p>
                `;
                movieList.appendChild(movieItem);
            });

            
            rightBtn.addEventListener('click', () => {
                movieList.scrollBy({ left: 200, behavior: 'smooth' });
            });

            leftBtn.addEventListener('click', () => {
                movieList.scrollBy({ left: -200, behavior: 'smooth' });
            });

            
            document.addEventListener('click', function(event) {
                event.preventDefault(); 
                window.location.href = 'login.php'; 
            });
        });
    </script>
</body>
</html>
<?php require "includes/footer.php"; ?>
