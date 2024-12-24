<script>
    document.addEventListener("DOMContentLoaded", function() {
        const movieList = document.getElementById('movieList');
        document.getElementById('rightBtn').addEventListener('click', () => {
            movieList.scrollBy({
                left: 200,
                behavior: 'smooth'
            });
        });
        document.getElementById('leftBtn').addEventListener('click', () => {
            movieList.scrollBy({
                left: -200,
                behavior: 'smooth'
            });
        });

        
        const movieDetailModal = document.getElementById('movieDetailModal');
        const modalMovieName = document.getElementById('modalMovieName');
        const modalMovieImage = document.getElementById('modalMovieImage');
        const modalMovieDescription = document.getElementById('modalMovieDescription');
        const modalMovieDuration = document.getElementById('modalMovieDuration');
        const closeModal = document.getElementById('closeModal');
        const bookShowBtn = document.getElementById('bookShowBtn');

        
        const movieCards = document.querySelectorAll('.movie');
        movieCards.forEach(card => {
            card.addEventListener('click', () => {
                modalMovieName.textContent = card.getAttribute('data-movie-name');
                modalMovieImage.src = card.getAttribute('data-movie-image');
                modalMovieDescription.textContent = card.getAttribute('data-movie-description');
                modalMovieDuration.textContent = card.getAttribute('data-movie-duration');
                
                
                const movieId = card.getAttribute('data-movie-id');
                modalMovieName.dataset.movieId = movieId; 

                movieDetailModal.classList.remove('hidden'); 
            });
        });

        
        closeModal.addEventListener('click', () => {
            movieDetailModal.classList.add('hidden'); 
        });

        
        bookShowBtn.addEventListener('click', () => {
            const movieId = modalMovieName.dataset.movieId;
            const seatNumber = modalMovieDuration.textContent; 

           
            fetch(`check_seat_availability.php?movieId=${movieId}&seatNo=${seatNumber}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        window.location.href = `booking.php?movieId=${movieId}&seatNo=${seatNumber}`;
                    } else {
                        alert('Sorry, the seat is not available.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while checking seat availability.');
                });
        });
    });
</script>
